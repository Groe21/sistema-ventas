# 🚀 Guía de Despliegue en Railway

## Pasos para desplegar en Railway

### 1. Crear cuenta en Railway
- Ve a [railway.app](https://railway.app)
- Regístrate con GitHub

### 2. Crear nuevo proyecto
1. Click en "New Project"
2. Selecciona "Deploy from GitHub repo"
3. Selecciona este repositorio

### 3. Agregar Base de Datos PostgreSQL
1. En tu proyecto, click "+ New"
2. Selecciona "Database"
3. Selecciona "Add PostgreSQL"
4. Railway creará automáticamente las variables: `PGHOST`, `PGPORT`, `PGDATABASE`, `PGUSER`, `PGPASSWORD`

### 4. Configurar Variables de Entorno

En la sección "Variables" de tu servicio, agrega:

#### Variables Requeridas:
```bash
APP_NAME=Sistema Comercial Pro
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.up.railway.app
APP_KEY=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_SECURE_COOKIE=true

# Cache
CACHE_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=info

# Mail (Opcional)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@example.com

# Deployment
RUN_SEEDS=false
PORT=8080
```

### 5. Generar APP_KEY

**Opción A - Desde Railway CLI:**
```bash
railway run php artisan key:generate --show
```

**Opción B - Localmente:**
```bash
php artisan key:generate --show
```

Copia la clave generada (ej: `base64:abc123...`) y agrégala a las variables de entorno en Railway.

### 6. Desplegar

Railway detectará automáticamente el `Dockerfile` y comenzará el build.

### 7. Ejecutar Migraciones (Primera vez)

Una vez desplegado, ejecuta las migraciones desde Railway CLI o la consola web:

```bash
railway run php artisan migrate --force
```

### 8. (Opcional) Seed de datos iniciales

Si es tu primera vez y necesitas datos de prueba:

```bash
railway run php artisan db:seed --force
```

O activa `RUN_SEEDS=true` en las variables de entorno (solo para primera vez).

## 🔍 Verificar Despliegue

1. **Health Check**: Visita `https://tu-app.up.railway.app/health`
   - Deberías ver: `{"status":"ok","timestamp":"...","app":"Sistema Comercial Pro"}`

2. **Login**: Visita `https://tu-app.up.railway.app/login`
   - Deberías ver la página de login

3. **Landing**: Visita `https://tu-app.up.railway.app/`
   - Deberías ver la landing page

## ⚠️ Troubleshooting

### Error: "Application key not set"
- Asegúrate de haber generado y configurado `APP_KEY` en las variables de entorno

### Error: "Connection refused" o "Service unavailable"
- Verifica que la base de datos PostgreSQL esté vinculada
- Revisa los logs en Railway: `railway logs`
- Verifica que las variables `DB_*` estén configuradas

### Error: "SQLSTATE[08006] Connection failure"
- Las credenciales de la base de datos son incorrectas
- Asegúrate de usar las variables de referencia: `${{PGHOST}}`, etc.

### Healthcheck falla
- Verifica que el servidor esté corriendo en el puerto correcto (`$PORT`)
- Revisa los logs: `railway logs --follow`
- El endpoint `/health` debe responder correctamente

### Migraciones fallan
- Verifica que la base de datos esté accesible
- Ejecuta manualmente: `railway run php artisan migrate:status`
- Revisa los logs de errores

## 📊 Comandos Útiles

```bash
# Ver logs en tiempo real
railway logs --follow

# Ejecutar comando en Railway
railway run [comando]

# Conectar a la base de datos
railway run psql

# Ver migraciones
railway run php artisan migrate:status

# Limpiar caché
railway run php artisan cache:clear
railway run php artisan config:clear

# Ver variables de entorno
railway vars
```

## 🔄 Actualizar Despliegue

Cada vez que hagas push a la rama principal, Railway automáticamente:
1. Detectará los cambios
2. Reconstruirá la imagen Docker
3. Ejecutará las migraciones (si están en start.sh)
4. Reiniciará el servicio

## 📝 Notas Importantes

1. **APP_KEY**: Nunca commitees la APP_KEY al repositorio
2. **Seeders**: Desactiva `RUN_SEEDS` después del primer despliegue
3. **Debug**: Mantén `APP_DEBUG=false` en producción
4. **Logs**: Usa `LOG_LEVEL=info` o `error` en producción
5. **Session**: Usa `SESSION_DRIVER=database` para múltiples instancias

## 🎯 Checklist de Despliegue

- [ ] PostgreSQL agregado al proyecto
- [ ] `APP_KEY` generado y configurado
- [ ] Todas las variables de entorno configuradas
- [ ] Migraciones ejecutadas exitosamente
- [ ] `/health` responde correctamente
- [ ] `/login` es accesible
- [ ] Landing page funciona
- [ ] Logs no muestran errores críticos
