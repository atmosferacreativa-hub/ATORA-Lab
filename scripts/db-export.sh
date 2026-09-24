#!/usr/bin/env bash
# Exporta la base de datos de ATORA Lab a db/atora_lab.sql para respaldarla en git.
#
# Por seguridad:
#  - Las tablas de sesiones, tokens, claves de API y webhooks se exportan solo
#    con su estructura (sin filas).
#  - De wp_options se omiten los transitorios y las opciones que guardan
#    credenciales (webhooks salientes, PayPal, claves de recuperación).
#    Tras restaurar, vuelve a configurarlas desde el admin.
set -euo pipefail

cd "$(dirname "$0")/.."

CONTAINER="${ATORA_DB_CONTAINER:-atora-database}"
DB="${ATORA_DB_NAME:-atora_lab}"
ROOT_PASS="${ATORA_DB_ROOT_PASSWORD:-atora_root_password}"
OUT="db/atora_lab.sql"

mysql_q() {
	docker exec "$CONTAINER" mysql -uroot -p"$ROOT_PASS" -N -e "$1" 2>/dev/null
}

# Tablas sin datos: sesiones, tokens, credenciales, claves y webhooks.
SENSITIVE_REGEX='token|oauth|session|secret|credential|api_key|webhook'
SENSITIVE=$(mysql_q "SELECT table_name FROM information_schema.tables WHERE table_schema='$DB' AND table_name REGEXP '$SENSITIVE_REGEX'")

IGNORE_ARGS=()
for t in $SENSITIVE wp_options; do
	IGNORE_ARGS+=("--ignore-table=$DB.$t")
done

OPTIONS_WHERE="option_name NOT LIKE '%transient%' AND option_name NOT IN ('atora_outbound_webhooks','woocommerce_paypal_settings','recovery_keys','mailserver_pass')"

DUMP_ARGS=(--single-transaction --skip-dump-date --default-character-set=utf8mb4 --no-tablespaces)

mkdir -p db
{
	echo "-- ATORA Lab: volcado de $DB ($(date '+%Y-%m-%d'))"
	echo "-- Generado con scripts/db-export.sh"
	echo
	docker exec "$CONTAINER" mysqldump -uroot -p"$ROOT_PASS" "${DUMP_ARGS[@]}" "${IGNORE_ARGS[@]}" "$DB" 2>/dev/null
	# shellcheck disable=SC2086
	docker exec "$CONTAINER" mysqldump -uroot -p"$ROOT_PASS" "${DUMP_ARGS[@]}" --no-data "$DB" $SENSITIVE 2>/dev/null
	docker exec "$CONTAINER" mysqldump -uroot -p"$ROOT_PASS" "${DUMP_ARGS[@]}" --where="$OPTIONS_WHERE" "$DB" wp_options 2>/dev/null
} > "$OUT"

echo "Exportado: $OUT ($(du -h "$OUT" | cut -f1))"
