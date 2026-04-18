# Sistema Comercial Pro — Resumen de Estado

> **Actualización:** 15 marzo 2026 | **Progreso general: ~75%** | **Deploy:** Railway (Docker + PostgreSQL)

---

## Módulos completados (75%)

| # | Módulo | Ruta | Estado |
|---|--------|------|--------|
| 1 | Autenticación (login, roles, 5 middleware, multi-tenant) | `/login` | ✅ 100% |
| 2 | Dashboard negocio (KPIs, gráficos, stock bajo) | `/dashboard` | ✅ 100% |
| 3 | Dashboard super admin (stats globales) | `/super-admin/dashboard` | ✅ 100% |
| 4 | Productos (CRUD, stock, IVA/ICE, código barras, categorías) | `/products` | ✅ 100% |
| 5 | Clientes (CRUD, cédula/RUC/pasaporte, crédito) | `/customers` | ✅ 100% |
| 6 | POS (carrito, 4 métodos pago, series billetes, descuentos, email factura) | `/pos` | ✅ 100% |
| 7 | Ventas (historial, filtros, detalle factura, 3 estados) | `/sales` | ✅ 100% |
| 8 | Caja (apertura/cierre, denominaciones, series $50/$100, movimientos) | `/cash` | ✅ 100% |
| 9 | Usuarios admin (CRUD empleados, límites plan) | `/users` | ✅ 100% |
| 10 | Usuarios super admin (CRUD global, filtros) | `/super-admin/users` | ✅ 100% |
| 11 | Negocios super admin (CRUD, RUC, suscripción) | `/super-admin/businesses` | ✅ 100% |
| 12 | Planes y suscripciones (CRUD, features JSON, activate/renew) | `/super-admin/plans`, `/super-admin/subscriptions` | ✅ 100% |
| 13 | Lealtad/Puntos (acumulación, redención POS, portal público) | `/loyalty`, `/customer-points` | ✅ 95% |
| 14 | Reportes (básicos + avanzados según plan, exportación parcial) | `/reports` | ⚠️ 60% |
| 15 | Configuración (datos negocio, SMTP Gmail, encriptación) | `/settings` | ✅ 90% |
| 16 | Email de factura automático al vender | — | ✅ 100% |

---

## LO QUE FALTA — Módulos pendientes (25%)

### 1. Facturación Electrónica SRI — Prioridad ALTA (~10%)

> **Sin esto NO se puede operar legalmente en Ecuador.**

| Tarea | Detalle | Ya existe |
|-------|---------|-----------|
| Secuencial y punto de emisión | Formato `001-001-000000001` | ❌ |
| Generación XML | Esquema SRI v1.1 (factura, NC, ND) | ❌ |
| Firma electrónica | Cargar archivo `.p12`, firmar XML con OpenSSL | ❌ |
| Envío al SRI | Webservice SOAP (ambientes: pruebas + producción) | ❌ |
| Recepción autorización | Guardar en `sales.sri_authorization` y `sales.access_key` | ✅ campos listos |
| Generación RIDE (PDF) | Representación impresa del documento electrónico | ❌ |
| Notas de crédito | Anulación parcial/total de facturas | ❌ |
| Retenciones | Emisión de comprobantes de retención | ❌ |
| Configuración SRI | Ambiente, tipo contribuyente, obligado contabilidad | ⚠️ parcial en `businesses` |

**Dependencias técnicas:** `ext-soap`, librería de firma XML (ej: `robrichards/xmlseclibs`), certificado `.p12` del contribuyente.

---

### 2. Impresión de Facturas/Tickets — Prioridad ALTA (~5%)

| Tarea | Detalle |
|-------|---------|
| PDF factura completa (A4) | Formato comercial con datos fiscales + RIDE SRI |
| Ticket térmico (58mm/80mm) | Formato reducido para impresora POS |
| Vista previa PDF | Antes de imprimir desde ventas/POS |
| Impresión directa | `window.print()` o integración con impresora térmica |

**Dependencias técnicas:** `barryvdh/laravel-dompdf` o `mpdf/mpdf` para PDF, JS para impresión directa.

---

### 3. Compras / Proveedores — Prioridad MEDIA (~5%)

| Tarea | Detalle |
|-------|---------|
| CRUD Proveedores | Nombre, RUC, contacto, dirección, condiciones pago |
| Órdenes de compra | Registro de compras con items y totales |
| Ingreso mercadería | Actualizar stock automático al confirmar compra |
| Historial compras | Por proveedor, por fecha, por producto |
| Cuentas por pagar | Saldos pendientes a proveedores |

**Tablas nuevas necesarias:** `suppliers`, `purchases`, `purchase_items`

---

### 4. Cuentas por Cobrar — Prioridad MEDIA (~3%)

> El POS ya soporta ventas a crédito (`payment_method: credit`), pero falta el seguimiento.

| Tarea | Detalle |
|-------|---------|
| Listado cartera | Ventas a crédito pendientes, filtros por cliente/vencimiento |
| Registro de abonos | Pagos parciales contra una venta |
| Alertas vencimiento | Notificación de créditos vencidos |
| Reporte cartera | Total por cobrar, vencida, por vencer |

**Tablas nuevas necesarias:** `payment_receipts` (abonos a ventas a crédito)

---

### 5. API REST — Prioridad BAJA (~2%)

> Sanctum ya está instalado. Solo falta implementar endpoints.

| Tarea | Detalle |
|-------|---------|
| Endpoints CRUD | Productos, clientes, ventas vía JSON |
| Auth por tokens | Login API → token Bearer |
| Documentación | Swagger/OpenAPI |

---

## Orden recomendado de implementación

```
1° → Facturación SRI + RIDE (PDF)     ← Desbloquea operación legal
2° → Impresión tickets térmicos        ← Comprobante físico al cliente
3° → Cuentas por cobrar                ← Controlar créditos otorgados
4° → Compras / Proveedores             ← Ciclo completo de inventario
5° → API REST                          ← App móvil / integraciones
```

---

## Referencia rápida

- **Stack:** Laravel 11 / PHP 8.4 / PostgreSQL / Bootstrap 5.3
- **Repositorio:** github.com/Groe21/sistema-ventas
- **BD:** 16 tablas (14 modelos + sessions + password_resets)
- **Controladores:** 16 (13 negocio + 3 super admin)
- **Vistas Blade:** 27 archivos
- **Middleware:** 5 (BusinessAccess, Admin, SuperAdmin, PlanFeature, PlanLimit)
- **Roles:** super_admin, admin, employee
