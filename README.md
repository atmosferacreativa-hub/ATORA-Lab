# ATORA Lab

Entorno completo de **ATORA Lab**, la academia de comunicación y fotografía que funciona sobre el ecosistema ATORA (WordPress + plugin ATORA LMS + tema ATORA).

## Contenido

| Ruta | Qué es |
|---|---|
| `atora-theme/` | Tema ATORA (Meridian): home institucional, blog con sidebar del ecosistema, plantillas de curso, programa, lección y mentor. |
| `atora-lms/` | Plugin ATORA LMS + CRM, como submódulo de [Atora-LMS-6](https://github.com/atmosferacreativa-hub/Atora-LMS-6). |
| `mu-plugins/atora-smtp.php` | Envío de correo por SMTP, configurado con variables de entorno. |
| `php/conf.d/atora-uploads.ini` | Límites de subida de PHP. |
| `docker-compose.yml` | WordPress 6.8 (PHP 8.1), MySQL 8 y phpMyAdmin. |
| `db/atora_lab.sql` | Respaldo de la base de datos (ver abajo). |
| `scripts/` | Exportar e importar la base de datos. |
| `*.png`, `*.jpg`, `*.svg` | Recursos de marca y de prueba. |

## Puesta en marcha

```bash
git clone --recurse-submodules https://github.com/atmosferacreativa-hub/ATORA-Lab.git
cd ATORA-Lab
cp .env.example .env   # completa los datos SMTP
docker compose up -d
```

- Sitio: http://localhost:8080
- phpMyAdmin: http://localhost:8081

Si ya tienes el plugin en otra carpeta, indica su ruta en `.env` con `ATORA_LMS_PATH`.

## Base de datos

`db/atora_lab.sql` es un respaldo completo del contenido: páginas, cursos, mentores, artículos, menús y ajustes.

```bash
./scripts/db-export.sh   # actualiza db/atora_lab.sql desde Docker
./scripts/db-import.sh   # restaura db/atora_lab.sql en Docker (pide confirmación)
```

Por seguridad, el volcado **no** incluye:
- filas de las tablas de sesiones, tokens, credenciales, claves de API y webhooks (solo su estructura);
- transitorios de `wp_options`;
- las opciones `atora_outbound_webhooks`, `woocommerce_paypal_settings`, `recovery_keys` y `mailserver_pass`.

Tras restaurar, vuelve a configurar los webhooks salientes y PayPal desde el admin.

El volcado sí contiene datos personales (usuarios con contraseñas cifradas y contactos del CRM): mantén este repositorio **privado**.

Los medios (`wp-content/uploads`, ~1 GB) no están en el repositorio: viven en el volumen `atora_wordpress`. Cópialos aparte si migras el sitio.

## Idioma

El sitio está configurado en español (`es_ES`). Las traducciones del tema están en `atora-theme/languages/es.php`.
