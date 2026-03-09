# 📋 RESUMEN DEL SISTEMA - Sistema Comercial Pro

> **Última actualización:** Marzo 2026
> **Estado general:** Funcional y en producción (Railway)

---

## 🏗️ Stack Tecnológico

| Tecnología | Versión | Uso |
|-----------|---------|-----|
| Laravel | 11.x | Framework backend |
| PHP | 8.4 | Lenguaje servidor |
| PostgreSQL | — | Base de datos |
| Bootstrap 5.3 | + Bootstrap Icons | Frontend responsive |
| Sanctum | 4.0 | Autenticación API (preparado) |
| Railway | — | Hosting / Deploy |

- **Idioma:** Español (es_ES)
- **Zona horaria:** America/Guayaquil
- **País objetivo:** Ecuador (IVA 12%, RUC, cédula)

---

## 👥 Roles del Sistema

| Rol | Acceso | Descripción |
|-----|--------|-------------|
| **Super Admin** | `/super-admin/*` | Administrador global del SaaS. Ve todos los negocios y usuarios |
| **Admin** | `/dashboard`, `/users`, `/reports`, `/settings` | Dueño/administrador de un negocio |
| **Employee** | `/dashboard`, `/pos`, `/sales`, `/cash`, `/products`, `/customers` | Empleado de un negocio |

---

## ✅ Módulos COMPLETADOS

### 1. Autenticación y Seguridad
- Login / Logout con validación de usuario activo
- 3 middleware de control de acceso: `business`, `admin`, `super-admin`
- Sesiones con CSRF protection
- Soft deletes en todas las tablas principales
- Multi-tenancy por `business_id`

### 2. Dashboard del Negocio (`/dashboard`)
- Ventas de hoy y del mes (montos)
- Total de productos y clientes
- Productos con stock bajo y valor del inventario
- Gráfico de ventas de los últimos 7 días
- Ventas recientes y top 5 productos más vendidos
- Estado de la caja (abierta/cerrada)

### 3. Dashboard Super Admin (`/super-admin/dashboard`)
- Total de negocios (activos/inactivos)
- Total de usuarios del sistema
- Ventas totales del día
- Negocios registrados recientemente

### 4. Gestión de Productos (`/products`)
- CRUD completo (crear, listar, editar, eliminar)
- Filtros: búsqueda, categoría, estado de stock (bajo/agotado/ok)
- Tipo: Producto o Servicio
- IVA configurable por producto (12%)
- Código único por negocio (código de barras / SKU)
- Control de stock y stock mínimo para alertas
- Precio de costo y precio de venta

### 5. Gestión de Clientes (`/customers`)
- CRUD completo
- Tipos de identificación: Cédula, RUC, Pasaporte, Consumidor Final
- Límite de crédito y días de crédito configurables
- Búsqueda por nombre, email, identificación, teléfono

### 6. Punto de Venta - POS (`/pos`)
- Interfaz interactiva con búsqueda de productos en tiempo real
- Carrito de compras con validación de stock
- Cálculo automático de subtotal, IVA (12%) y total
- Métodos de pago: Efectivo, Tarjeta, Transferencia, Crédito
- Descuentos por venta
- Generación automática de número de factura
- Transacciones atómicas (todo o nada)
- Descuento automático de stock al vender

### 7. Historial de Ventas (`/sales`)
- Listado con filtros avanzados: fecha desde/hasta, método de pago, estado, búsqueda
- Vista de detalle de venta/factura con todos los ítems
- Estados: Completada, Cancelada, Reembolsada
- Estados de pago: Pagado, Pendiente, Parcial

### 8. Gestión de Caja (`/cash`)
- Apertura de caja con monto inicial
- Registro de movimientos (ingresos y egresos)
- Categorías: venta, gasto, retiro, depósito, ajuste
- Cierre de caja con cálculo de diferencia (faltante/sobrante)
- Historial de cajas anteriores
- Solo una caja abierta por negocio

### 9. Gestión de Usuarios - Admin (`/users`)
- CRUD de empleados dentro del negocio
- Filtros por rol, estado y búsqueda
- Estadísticas: total, admins, empleados, activos
- Protección: no se puede eliminar a sí mismo

### 10. Gestión de Usuarios - Super Admin (`/super-admin/users`)
- CRUD de todos los usuarios del sistema
- Filtros: búsqueda, rol, negocio, estado
- Asignación de negocio a usuarios
- Puede crear usuarios de cualquier rol (super_admin, admin, employee)

### 11. Gestión de Negocios - Super Admin (`/super-admin/businesses`)
- Listar y crear negocios
- Datos: RUC, nombre comercial, email, dirección, plan, suscripción

---

## ⚠️ Módulos PENDIENTES (Placeholders)

Estas rutas existen pero muestran "Módulo en Desarrollo":

| Módulo | Ruta | Prioridad Sugerida |
|--------|------|---------------------|
| **Reportes (Admin)** | `/reports` | 🔴 Alta |
| **Reportes (Super Admin)** | `/super-admin/reports` | 🔴 Alta |
| **Suscripciones** | `/super-admin/subscriptions` | 🟡 Media |
| **Configuración (Admin)** | `/settings` | 🟡 Media |
| **Configuración (Super Admin)** | `/super-admin/settings` | 🟡 Media |

---

## 🔮 Funcionalidades Sugeridas para el FUTURO

### 🔴 Prioridad Alta

#### 1. Módulo de Reportes
- **Reporte de ventas:** Por día, semana, mes, rango de fechas
- **Reporte de productos:** Más vendidos, menos vendidos, sin movimiento
- **Reporte de inventario:** Valor total, stock bajo, productos agotados
- **Reporte de caja:** Resumen de movimientos, arqueos
- **Reporte de clientes:** Mejores clientes, créditos pendientes
- **Exportar a PDF y Excel**
- **Gráficos interactivos** (Chart.js ya se puede integrar)

#### 2. Facturación Electrónica SRI (Ecuador)
- Integración con el SRI para facturación electrónica
- Generación de XML según formato del SRI
- Firma electrónica
- Envío y recepción de autorizaciones
- Campos ya preparados en la tabla `sales`: `sri_authorization`, `access_key`

#### 3. Impresión de Facturas / Tickets
- Impresión térmica (POS 58mm / 80mm)
- Formato de factura comercial (A4)
- Vista previa antes de imprimir
- Impresión automática al completar venta

### 🟡 Prioridad Media

#### 4. Módulo de Configuración
- **Datos del negocio:** Logo, información fiscal, dirección
- **Configuración de impuestos:** Porcentaje de IVA, ICE
- **Secuencia de facturas:** Prefijo, secuencia, punto de emisión
- **Configuración de impresora:** Tipo, formato por defecto
- **Notificaciones:** Stock bajo, suscripción por vencer

#### 5. Gestión de Suscripciones (Super Admin)
- Panel para ver todas las suscripciones
- Activar/desactivar/renovar suscripciones
- Cambio de planes (trial → basic → pro → enterprise)
- Historial de pagos
- Integración con pasarela de pagos (PayPhone, Stripe)

#### 6. Compras / Proveedores
- CRUD de proveedores
- Registro de compras / órdenes de compra
- Ingreso de mercadería al inventario
- Historial de compras por proveedor
- Cuentas por pagar

#### 7. Cuentas por Cobrar
- Seguimiento de ventas a crédito
- Registro de abonos/pagos parciales
- Alertas de vencimiento
- Reporte de cartera vencida

### 🟢 Prioridad Baja (Mejoras)

#### 8. API REST Completa
- Endpoints para productos, clientes, ventas (estructura preparada con Sanctum)
- Autenticación por tokens
- Documentación con Swagger/OpenAPI
- Webhook para integraciones externas

#### 9. Notificaciones y Alertas
- Notificaciones en tiempo real (stock bajo, caja sin cerrar)
- Alertas por email (suscripción por vencer, resumen diario)
- Notificaciones push (opcional)

#### 10. Categorías y Marcas
- CRUD independiente de categorías de productos
- CRUD independiente de marcas
- Filtros mejorados en el inventario

#### 11. Códigos de Barras
- Generador de códigos de barras
- Lector de códigos de barras con cámara
- Impresión de etiquetas de precios

#### 12. Devoluciones y Notas de Crédito
- Registro de devoluciones de ventas
- Generación de notas de crédito
- Reingreso automático de stock

#### 13. Multi-Sucursal
- Un negocio con múltiples puntos de venta
- Transferencia de inventario entre sucursales
- Reportes por sucursal y consolidados

#### 14. Auditoría y Logs
- Registro de todas las acciones de usuarios
- Historial de cambios en productos, precios, stock
- Log de accesos al sistema

#### 15. App Móvil / PWA
- Convertir a Progressive Web App
- Funcionalidad offline para el POS
- Sincronización cuando hay conexión

#### 16. Importación / Exportación Masiva
- Importar productos desde Excel/CSV
- Importar clientes desde Excel/CSV
- Exportar inventario completo

#### 17. Descuentos y Promociones
- Descuentos por producto, categoría o cliente
- Cupones de descuento
- Precios por volumen
- Ofertas temporales

#### 18. Dashboard Mejorado
- KPIs personalizables
- Comparativas (este mes vs anterior)
- Pronóstico de ventas
- Widget de metas de ventas

---

## 🗄️ Base de Datos - Tablas Actuales

| Tabla | Registros Clave | Relaciones |
|-------|-----------------|------------|
| `businesses` | Negocios registrados en el SaaS | → users, products, customers, sales, cash |
| `users` | Todos los usuarios (super admin, admin, empleados) | → business, sales, cash |
| `products` | Productos e inventario por negocio | → business, sale_items |
| `customers` | Clientes por negocio | → business, sales |
| `sales` | Ventas/facturas realizadas | → business, user, customer, cash_register, items |
| `sale_items` | Detalle de cada venta (productos vendidos) | → sale, product |
| `cash_registers` | Aperturas y cierres de caja | → business, user, sales, movements |
| `cash_movements` | Movimientos de dinero (ingresos/egresos) | → business, cash_register, user, sale |

---

## 📁 Estructura del Proyecto

```
sistema-ventas/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/LoginController.php
│   │   │   ├── SuperAdmin/SuperAdminController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── ProductController.php
│   │   │   ├── CustomerController.php
│   │   │   ├── POSController.php
│   │   │   ├── SaleController.php
│   │   │   ├── CashController.php
│   │   │   └── UserController.php
│   │   └── Middleware/
│   │       ├── CheckAdmin.php
│   │       ├── CheckBusinessAccess.php
│   │       └── CheckSuperAdmin.php
│   └── Models/
│       ├── User.php
│       ├── Business.php
│       ├── Product.php
│       ├── Customer.php
│       ├── Sale.php
│       ├── SaleItem.php
│       ├── CashRegister.php
│       └── CashMovement.php
├── resources/views/
│   ├── auth/login.blade.php
│   ├── layouts/ (app, guest, partials)
│   ├── admin/ (dashboard, products, customers, pos, cash, sales, users, reports*, settings*)
│   └── super-admin/ (dashboard, businesses, users, reports*, subscriptions*, settings*)
├── routes/
│   ├── web.php (todas las rutas web)
│   └── api.php (preparado para API REST)
├── database/
│   ├── migrations/ (8 tablas principales)
│   └── seeders/DatabaseSeeder.php (datos de demo)
├── Dockerfile (deploy Railway)
├── start.sh (script de inicio)
├── railway.toml (configuración Railway)
└── README.md (documentación y credenciales)
```

*\* = Placeholder (módulo pendiente)*

---

## 🔐 Usuarios de Demo

| Rol | Email | Contraseña |
|-----|-------|------------|
| Super Admin | admin@sistema.com | admin123 |
| Admin Negocio | emilio@negocio.com | admin123 |
| Empleado | cliente@demo.com | demo1234 |

---

## 🌐 Deploy

- **Repositorio:** github.com/Groe21/sistema-ventas
- **Producción:** Railway (Docker + PostgreSQL)
- **Branch:** main

---

## 📌 Notas Técnicas

- Todas las tablas principales usan **Soft Deletes** (los registros no se borran, se marcan como eliminados)
- La arquitectura es **multi-tenant** por `business_id` (cada negocio solo ve sus datos)
- El POS usa **transacciones atómicas** para garantizar integridad de datos
- Los campos `sri_authorization` y `access_key` en ventas están listos para futura integración con el SRI
- Sanctum está instalado y listo para crear la API REST
- Las vistas son **responsive** (adaptadas para escritorio, tablet y móvil)
