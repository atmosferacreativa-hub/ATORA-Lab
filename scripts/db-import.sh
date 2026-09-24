#!/usr/bin/env bash
# Restaura db/atora_lab.sql en la base de datos de Docker (reemplaza el contenido actual).
set -euo pipefail

cd "$(dirname "$0")/.."

CONTAINER="${ATORA_DB_CONTAINER:-atora-database}"
DB="${ATORA_DB_NAME:-atora_lab}"
ROOT_PASS="${ATORA_DB_ROOT_PASSWORD:-atora_root_password}"
IN="db/atora_lab.sql"

read -r -p "Esto reemplaza la base '$DB' con $IN. ¿Continuar? (s/N) " answer
[[ "$answer" == "s" || "$answer" == "S" ]] || { echo "Cancelado."; exit 1; }

docker exec -i "$CONTAINER" mysql -uroot -p"$ROOT_PASS" "$DB" < "$IN"
echo "Base restaurada. Si cambia la URL del sitio, ejecuta:"
echo "  docker exec atora-wordpress wp --allow-root --path=/var/www/html search-replace 'http://192.168.1.16:8080' 'NUEVA_URL' --all-tables"
