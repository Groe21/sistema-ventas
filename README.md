# Sistema Ventas

Sistema de facturación y gestión comercial para mini negocios en Ecuador.  
Desarrollado con **Laravel 11** + **PostgreSQL** + **Bootstrap 5**.

---

## Características

- Punto de Venta (POS) con búsqueda en tiempo real
- Gestión de inventario y productos (con IVA 15% Ecuador)
- Clientes con tipos de identificación ecuatorianos (Cédula, RUC, Pasaporte)
- Flujo de caja (apertura, movimientos, cierre con diferencia)
- Historial de ventas con comprobantes imprimibles
- Dashboard con estadísticas en tiempo real
- Multi-tenant (múltiples negocios)
- Diseño 100% responsive (PC, tablet, móvil)

---

## Demo - Usuarios de Prueba

### Super Administrador
| Campo    | Valor              |
|----------|--------------------|
| Email    | `admin@sistema.com`|
| Password | `admin123`         |
| Acceso   | Panel de administración general |

### Administrador del Negocio
| Campo    | Valor                |
|----------|----------------------|
| Email    | `emilio@negocio.com` |
| Password | `admin123`           |
| Acceso   | Todas las funciones del negocio |

### Cliente Demo (Empleado)
| Campo    | Valor              |
|----------|--------------------|
| Email    | `cliente@demo.com` |
| Password | `demo1234`         |
| Acceso   | POS, Ventas, Caja, Productos, Clientes |

---

## Despliegue en Railway

### 1. Crear proyecto en Railway
1. Ve a [railway.app](https://railway.app) y crea un nuevo proyecto
2. Conecta este repositorio de GitHub
3. Agrega un servicio **PostgreSQL** al proyecto

### 2. Variables de entorno requeridas
Railway conecta PostgreSQL automáticamente con `DATABASE_URL`. Agrega estas variables manualmente:

```
APP_NAME="Sistema Comercial Pro"
APP_ENV=production
APP_KEY=           # genera con: php artisan key:generate --show
APP_DEBUG=false
APP_URL=https://tu-dominio.up.railway.app
DB_CONNECTION=pgsql
SESSION_DRIVER=file
```

> **Nota:** `DATABASE_URL` es inyectada automáticamente por Railway al vincular PostgreSQL.

### 3. Deploy automático
Railway ejecutará automáticamente:
- `composer install`
- `php artisan migrate --force`
- `php artisan db:seed --force`

---

## Instalación Local

```bash
# Clonar
git clone https://github.com/Groe21/sistema-ventas.git
cd sistema-ventas

# Instalar dependencias
composer install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Configurar la base de datos PostgreSQL en .env
# DB_CONNECTION=pgsql
# DB_DATABASE=sistema_comercial_pro
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_password

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Iniciar servidor
php artisan serve
```

Accede a `http://localhost:8000` y usa las credenciales de arriba.

---

## Stack Tecnológico

- **Backend:** Laravel 11 (PHP 8.2+)
- **Base de datos:** PostgreSQL
- **Frontend:** Blade + Bootstrap 5.3 + Bootstrap Icons
- **Autenticación:** Laravel Auth nativo
- **Arquitectura:** Multi-tenant por `business_id`

---

## Licencia

MIT
