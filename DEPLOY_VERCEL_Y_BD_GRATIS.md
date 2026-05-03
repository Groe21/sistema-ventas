# Guía: Vercel + base de datos remota (gratis) y opinión sobre reescribir el sistema

Este documento resume **qué es realista** con Vercel para este proyecto (Laravel 11 + PostgreSQL) y cómo montar una **BD remota gratis** si decides seguir por ahí.

---

## 1. Base de datos remota gratis (PostgreSQL)

Opciones con **tier gratuito** (límites de almacenamiento, conexiones o tiempo; revisa términos actuales en cada sitio):

| Proveedor | Motor | Notas |
|-----------|--------|--------|
| **Neon** | PostgreSQL | Muy usado con serverless; conexión por URL. |
| **Supabase** | PostgreSQL | Panel cómodo; también auth/storage (no obligatorio usarlos). |

**Pasos típicos (Neon o Supabase, idea general):**

1. Crear cuenta y un **nuevo proyecto** de base de datos.
2. Elegir región cercana a tus usuarios (ej. `South America` si existe, o `US East`).
3. Copiar la **connection string** (URI) o los datos: `host`, `port`, `database`, `user`, `password`.
4. En Laravel (`.env` de producción), por ejemplo:

```env
DB_CONNECTION=pgsql
DB_HOST=xxx.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=...
DB_PASSWORD=...
# Si el proveedor te da DATABASE_URL:
# DATABASE_URL=postgres://user:pass@host/db?sslmode=require
```

5. En el servidor donde corra Laravel: `php artisan migrate --force` (y seed si aplica).

**SSL:** muchas BDs en la nube exigen SSL. En Laravel con `pgsql`, suele bastar con el host/credenciales que te da el panel; si falla, revisa la documentación del proveedor para `sslmode=require`.

---

## 2. ¿Se puede subir **todo** este Laravel a Vercel?

**Resumen:** técnicamente **sí hay intentos y paquetes de la comunidad**, pero Vercel está pensado para **Node / estáticos / serverless corto**. Un Laravel con panel, sesiones, archivos locales y jobs **no es el camino feliz** en Vercel: más mantenimiento y límites que en un VPS o un PaaS tipo Render/Fly.

Si insistes en Vercel para el **backend PHP**, tendrías que alinear:

- **Sesiones y caché:** no depender solo de `file` en disco efímero; usar **base de datos** o **Redis** (Redis gratis tier limitado, ej. Upstash).
- **Subidas / `storage`:** usar **S3 compatible** o similar (el disco local en serverless no es fiable).
- **Colas / cron:** otro servicio o triggers externos.
- **Build:** runtime PHP en Vercel vía configuración y runtime de comunidad (no es el flujo “oficial” principal de Vercel como Node).

**Qué sí encaja muy bien en Vercel sin pelear:**

- Solo la **landing** (HTML estático o export estático).
- Un **frontend** (Next.js, etc.) que consuma una **API** alojada en otro sitio.

---

## 3. Arquitectura recomendada si quieres Vercel + BD gratis + menos dolor

**Opción A — Mínimo cambio de mentalidad**

- **App Laravel completa:** en un VPS gratis (ej. Oracle Cloud Always Free) o PaaS barato.
- **BD:** Neon o Supabase (gratis con límites).
- **Landing:** opcional en Vercel apuntando al dominio del sistema.

**Opción B — Vercel “de verdad” para la web**

- **Vercel:** Next.js (o similar) con las pantallas principales.
- **API:** Laravel en otro host **o** reescritura gradual de endpoints.
- **BD:** Neon/Supabase.

Eso ya es **proyecto grande** (no es solo “subir el zip”).

---

## 4. ¿Reescribir todo el sistema en otras tecnologías solo para Vercel?

**Opinión directa:**

- **Reescribir desde cero** (misma lógica: multi-tenant, POS, caja, facturación, reportes, permisos) es **muchas semanas o meses** de trabajo y **alto riesgo** de bugs frente a lo que ya tienes funcionando en Laravel.
- Tiene sentido si tu objetivo es **aprender stack moderno** o si planeas un **producto SaaS nuevo** con otro equipo/timeline.
- Para **seguir vendiendo o usando el sistema ya**, lo rentable es **mantener Laravel** y ponerlo donde corre bien (VPS gratis + Neon gratis, o un PaaS con free tier limitado).

**Camino intermedio (sin tirar Laravel):**

1. Dejar **Laravel + BD (Neon/Supabase)** en un host adecuado.
2. Más adelante, si quieres marketing en Vercel, **solo la landing** en Vercel con enlaces al panel.

---

## 5. Checklist rápido si montas BD remota (cualquier host del app)

- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] `APP_KEY` generado y fijo en el host
- [ ] `APP_URL` = URL pública HTTPS
- [ ] `DB_*` apuntando al proveedor remoto
- [ ] Migraciones ejecutadas en producción
- [ ] `SESSION_DRIVER` y `CACHE_STORE` acordes al entorno (no solo `file` si el disco no es persistente)
- [ ] Revisar `MAIL_*` si envías correos
- [ ] No commitear `.env` con secretos (usar variables del proveedor)

---

## 6. Paso a paso: ya creaste el proyecto en Neon

1. **En Neon:** pestaña **Connection string**, copia el string completo (incluye `?sslmode=require`).
2. **En tu PC**, abre el `.env` del proyecto (no lo subas a Git).
3. **Elige una forma** (solo una, para no mezclar):
   - **Opción A (recomendada):** agrega una línea  
     `DATABASE_URL=postgresql://...`  
     y **comenta o borra** las líneas `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` para que Laravel use solo la URL.
   - **Opción B:** deja `DB_HOST`, `DB_PORT`, etc. rellenados con los valores que muestra Neon en **Connection parameters** (mismo host, usuario, base `neondb`, contraseña).
4. Mantén `DB_CONNECTION=pgsql`.
5. Para SSL estricto con parámetros sueltos, puedes poner en `.env`:  
   `DB_SSLMODE=require`
6. En la carpeta del proyecto ejecuta:
   ```bash
   php artisan config:clear
   php artisan db:show
   ```
   Si `db:show` muestra la base remota, la conexión está bien.
7. **Primera vez en esa BD vacía:** crea tablas con  
   `php artisan migrate`  
   Si quieres datos de prueba:  
   `php artisan migrate --seed`  
   (**Ojo:** `--seed` en BD nueva está bien; en una BD con datos reales revisa antes qué hace el seeder.)
8. Arranca la app (`php artisan serve`) y prueba login/dashboard.

**Seguridad:** no pegues la URL con contraseña en chats públicos ni en commits. Rota la contraseña en Neon si crees que se filtró.

**Neon: host `...-pooler...` vs conexión directa:** el connection string con **pooler** (PgBouncer) a veces falla en `php artisan migrate` porque Laravel envuelve las migraciones en transacción y el pooler en modo transacción no encaja bien con varias sentencias DDL. Si ves errores tipo `current transaction is aborted` al crear la primera tabla, en el panel de Neon elige el endpoint **sin** `-pooler` (conexión directa al compute) o duplica en `.env` la URL de “Direct connection” para migrar; luego puedes volver al pooler para la app en producción si lo prefieres.

---

## 6b. Migraciones: qué modificar en el repo vs qué no

**En el código del proyecto casi no hay que cambiar nada** para usar Neon: Laravel ya usa `DATABASE_URL` en `config/database.php` para `pgsql`.

Lo que importa es **fuera del repo**:

1. Tu archivo **`.env` local** (no se sube a Git) con `DATABASE_URL=...` apuntando a Neon (y sin líneas `DB_HOST`/`DB_PASSWORD` duplicadas que lo pisen).
2. Ejecutar migraciones **desde tu PC** contra esa URL:

```bash
composer db:check
composer migrate:remote
```

- `composer db:check` → limpia config caché y muestra si Laravel ve la base remota.
- `composer migrate:remote` → `migrate --force` (útil para CI o cuando ya estás seguro).

**Datos demo:** solo si quieres seeders en esa BD:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### ¿Y Vercel?

Las migraciones **no viven “en Vercel”**: se ejecutan donde corra PHP con acceso a `DATABASE_URL` (tu laptop, GitHub Actions, o el build del host si lo configuras así).

- **Forma directa y estable:** migrar desde aquí (o desde un workflow de GitHub) con Neon ya configurado.
- **Forma en Vercel:** solo tendría sentido si logras desplegar PHP/Laravel en Vercel y defines `DATABASE_URL` en el panel de Vercel; el comando de migración iría en el **build** o en un **script manual** — es más frágil que migrar desde tu máquina o CI.

Si más adelante fijas **cómo** subirás el PHP (Vercel u otro), se puede documentar el comando exacto de deploy en un solo sitio.

---

## 7. Deploy en Vercel (Laravel)

En el repo hay configuración lista: **`vercel.json`**, **`api/index.php`**, **`package.json`** y la guía **`VERCEL.md`** (variables de entorno, Node 22.x, runtime `vercel-php@0.9.0`). Las migraciones siguen siendo contra Neon desde tu PC o CI; en Vercel solo corre la app PHP como función serverless.

---

## 8. Próximo paso concreto en este repo

Cuando elijas **Neon** o **Supabase**, se puede añadir un `.env.production.example` (sin secretos) con las variables mínimas y, si más adelante fijas el host del Laravel, un `Dockerfile` o script de deploy ya alineado con ese host.

Este archivo es solo guía; **no sustituye** la lectura de la documentación actual de cada proveedor (los free tiers cambian con el tiempo).
