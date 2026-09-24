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

La base de datos y los medios (`wp-content/uploads`) **no** están en el repositorio: viven en los volúmenes de Docker (`atora_database`, `atora_wordpress`). Para migrarlos, exporta la base con phpMyAdmin o `wp db export` y copia `uploads` aparte.

## Idioma

El sitio está configurado en español (`es_ES`). Las traducciones del tema están en `atora-theme/languages/es.php`.
