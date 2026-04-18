# 🔐 Credenciales del Sistema - Ambiente de Desarrollo

> **Actualizado:** 16 de abril de 2026  
> **Suscripción válida hasta:** 16 de abril de 2031

---

## 👨‍💼 Usuario Super Administrador

**Para gestionar planes, negocios y configuración global del sistema:**

- **Email:** `admin@sistema.com`
- **Contraseña:** `admin123`
- **Rol:** Super Admin
- **Acceso:** Panel super admin en `/super-admin`

---

## 🏢 Usuarios del Negocio "Tienda Emilio"

### 1️⃣ Administrador del Negocio

**Usuario principal con todos los permisos:**

- **Email:** `emilio@negocio.com`
- **Contraseña:** `admin123`
- **Rol:** Admin
- **Negocio:** Tienda Emilio
- **Plan:** Business (válido hasta 2031)
- **Acceso:** Dashboard completo en `/admin/dashboard`

### 2️⃣ Empleado Demo

**Usuario con permisos de empleado:**

- **Email:** `empleado@negocio.com`
- **Contraseña:** `admin123`
- **Rol:** Employee
- **Negocio:** Tienda Emilio
- **Acceso:** Funciones de POS, ventas, productos

### 3️⃣ Cliente Demo

**Usuario con acceso al portal de clientes:**

- **Email:** `cliente@demo.com`
- **Contraseña:** `demo1234`
- **Rol:** Employee
- **Negocio:** Tienda Emilio
- **Acceso:** Portal de clientes (puntos de lealtad)

---

## 📊 Información del Plan Actual

- **Plan:** Business
- **Precio:** $24.99/mes
- **Estado:** Activo (trial hasta 2031)
- **Límite de usuarios:** 5
- **Límite de productos:** 5,000

### Características Habilitadas:
- ✅ Punto de Venta (POS)
- ✅ Gestión de Productos e Inventario
- ✅ Gestión de Clientes
- ✅ Caja Registradora
- ✅ Reportes Básicos y Avanzados
- ✅ Dashboard Avanzado
- ✅ Alertas de Stock Bajo
- ✅ Exportar a Excel/PDF
- ✅ Pagos con Tarjeta y Transferencia
- ⚠️ Programa de Lealtad (solo en Premium)
- ⚠️ Portal de Clientes (solo en Premium)

---

## 🔄 Cambiar de Usuario

Para probar diferentes niveles de acceso:

1. Cerrar sesión
2. Iniciar sesión con las credenciales correspondientes
3. Cada usuario verá funcionalidades según su rol y plan

---

## 🆘 Solución de Problemas

### Error: "La suscripción del negocio ha expirado"
✅ **Solucionado:** La suscripción se actualizó hasta 2031.

Si vuelve a aparecer, ejecutar:
```bash
php artisan tinker
```
```php
$business = \App\Models\Business::find(1);
$business->subscription_end = now()->addYears(5);
$business->save();
```

### Restablecer Contraseñas
Para cambiar contraseñas de prueba:
```bash
php artisan tinker
```
```php
$user = \App\Models\User::where('email', 'EMAIL_AQUI')->first();
$user->password = Hash::make('NUEVA_CONTRASEÑA');
$user->save();
```

### Recrear Datos de Prueba
```bash
php artisan migrate:fresh --seed
```
⚠️ **ADVERTENCIA:** Esto eliminará TODOS los datos actuales.

---

## 📝 Notas Importantes

1. **Estas credenciales son solo para desarrollo local**
2. En producción, cambiar todas las contraseñas
3. El Super Admin no tiene negocio asociado, es para administración global
4. Los usuarios regulares deben pertenecer a un negocio con suscripción activa
5. El portal de clientes requiere el plan Premium

---

## 🎯 Próximos Pasos

Para implementar el portal de clientes completo:
1. Actualizar el negocio al plan Premium
2. Configurar el sistema de puntos de lealtad
3. Activar las rutas del portal público `/portal-clientes`

¿Necesitas upgrade a Premium? Usa el panel Super Admin.
