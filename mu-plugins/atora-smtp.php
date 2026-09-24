<?php
/**
 * ATORA Lab — SMTP (env-based)
 *
 * Configura SMTP vía variables de entorno para evitar guardar credenciales
 * en la BD. Pensado para entornos locales Docker.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'phpmailer_init',
	static function ( $phpmailer ) {
		$host = getenv( 'ATORA_SMTP_HOST' );
		$port = getenv( 'ATORA_SMTP_PORT' );
		$user = getenv( 'ATORA_SMTP_USER' );
		$pass = getenv( 'ATORA_SMTP_PASS' );
		$from = getenv( 'ATORA_SMTP_FROM' );

		if ( ! $host || ! $port || ! $user || ! $pass || ! $from ) {
			return;
		}

		$secure = getenv( 'ATORA_SMTP_SECURE' );
		$name   = getenv( 'ATORA_SMTP_FROM_NAME' );

		$phpmailer->isSMTP();
		$phpmailer->Host       = (string) $host;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Port       = (int) $port;
		$phpmailer->Username   = (string) $user;
		$phpmailer->Password   = (string) $pass;
		$phpmailer->SMTPSecure = $secure ? (string) $secure : 'tls';

		$phpmailer->setFrom( (string) $from, $name ? (string) $name : 'ATORA Lab', false );
	}
);

