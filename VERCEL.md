# Desplegar este proyecto en Vercel (Laravel + vercel-php)

El proyecto incluye `api/index.php`, `vercel.json` y `package.json` para usar el runtime **[vercel-php](https://github.com/vercel-community/php)** (`vercel-php@0.9.0`, compatible con PHP **8.4**).

## Antes de desplegar

1. **Base de datos:** usa Neon (u otro Postgres remoto). Copia `DATABASE_URL` (sin pooler para migraciones locales; en runtime puedes usar pooler si Neon lo permite).
2. **Migraciones:** ejecútalas desde tu PC contra Neon (`composer migrate:remote`). No dependas del build de Vercel para crear tablas salvo que configures un workflow aparte.
3. **Límite de tamaño:** las funciones serverless tienen un límite de ~250 MB descomprimido. Si `vendor/` lo supera, habría que optimizar dependencias o valorar otro host.

## Configuración del proyecto en Vercel

- **Framework Preset:** Other  
- **Root Directory:** `./` (raíz del repo)  
- **Node.js:** 22.x (coherente con `package.json`; si el panel muestra 24.x y falla el build, fija 22.x en *Settings → General*).  
- **Instalar dependencias:** el script Composer `vercel` ejecuta `composer install --no-dev ...`. El archivo `.vercelignore` excluye `vendor/` para que se instale en el build.

## Variables de entorno (Project → Settings → Environment Variables)

Añade al menos **Production** (y Preview si quieres URLs de PR):

| Variable | Valor / notas |
|----------|----------------|
| `APP_NAME` | `Sistema Comercial Pro` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Salida de `php artisan key:generate --show` en local |
| `APP_URL` | URL **exacta** de tu deploy, p. ej. `https://sistema-ventas-xxx.vercel.app` |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `cookie` (recomendado en serverless; evita escribir sesiones en disco) |
| `SESSION_SECURE_COOKIE` | `true` |
| `CACHE_STORE` | `array` |
| `VIEW_COMPILED_PATH` | `/tmp/views` |
| `DATABASE_URL` | Connection string de Neon (Postgres) |

Opcionales (caché en `/tmp` si usas `php artisan config:cache` / `route:cache` en el build; en muchos casos no hace falta tocarlas):

| Variable | Valor |
|----------|--------|
| `APP_CONFIG_CACHE` | `/tmp/config.php` |
| `APP_ROUTES_CACHE` | `/tmp/routes-v7.php` |
| `APP_EVENTS_CACHE` | `/tmp/events.php` |
| `APP_PACKAGES_CACHE` | `/tmp/packages.php` |
| `APP_SERVICES_CACHE` | `/tmp/services.php` |

No pegues secretos en `vercel.json`; todo sensible va en el panel de Vercel.

## Después del primer deploy

1. Abre la URL del proyecto y prueba `/up` (health de Laravel 11).
2. Si ves **500**, revisa **Functions → Logs** en Vercel y comprueba `APP_KEY`, `APP_URL` y `DATABASE_URL`.
3. Si los assets en `/public` dan **404**, haz **Redeploy** sin caché y verifica las rutas en `vercel.json`.

## Archivos relevantes

- `api/index.php` — entrada serverless (incluye `public/index.php`).
- `vercel.json` — runtime PHP, rutas estáticas y fallback a Laravel.
- `.vercelignore` — no sube `vendor/` (se regenera en build).

## Ya configurado en código

- `bootstrap/app.php` incluye `trustProxies(at: '*')` para proxies de Vercel.
