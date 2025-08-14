# Plan: Sistema de Verificación de Email para Empleados

## 📋 Objetivo
Implementar un sistema completo de verificación por email cuando se crea un nuevo empleado, cambiando su estado de "Pendiente" a "Activo" una vez confirmado.

## 🗂️ Componentes a Implementar

### 1. **Configuración de Email**
- Actualizar `.env` con configuración SMTP (Gmail/Mailgun/SendGrid)
- Verificar que `config/mail.php` esté correctamente configurado

### 2. **Modelo User**
- Implementar `MustVerifyEmail` interface
- Agregar método para verificar si el usuario está activo
- Personalizar lógica de verificación

### 3. **Notification/Mailable**
- Crear `WelcomeEmployeeNotification` personalizada
- Diseñar template de email profesional
- Incluir link de verificación y datos del empleado

### 4. **Controlador de Verificación**
- Crear ruta para manejar verificación: `/verify-employee/{token}`
- Implementar lógica de activación de cuenta
- Manejar casos de tokens expirados/inválidos

### 5. **Actualizar Componente Livewire**
- Modificar `saveEmpleado()` para enviar email de verificación
- Actualizar lógica de creación de empleados
- Agregar feedback visual del envío de email

### 6. **Vista de Verificación**
- Página de confirmación exitosa
- Página de error/token expirado
- Redirección a login con mensaje de éxito

### 7. **Mejoras en la UI**
- Actualizar estados visuales en tabla de empleados
- Mostrar claramente usuarios "Pendientes de verificación"
- Agregar opción de reenviar email de verificación

## 🔧 Flujo Completo

1. **Admin crea empleado** → Empleado guardado con `email_verified_at = null`
2. **Sistema envía email** → Notification con link único de verificación
3. **Empleado hace clic** → Link redirige a página de verificación
4. **Verificación exitosa** → `email_verified_at` se actualiza, estado cambia a "Activo"
5. **Empleado puede acceder** → Cuenta activada, puede iniciar sesión

## 📊 Cambios en BD
- La tabla `users` ya tiene `email_verified_at` (timestamp nullable)
- No requiere migración adicional
- Estado "Activo/Pendiente" basado en si `email_verified_at` es null

## ⚙️ Configuración Sugerida
- **Email Provider**: Mailtrap (ideal para demostración académica)
- **Tiempo de expiración**: 24 horas para el link de verificación
- **Plantilla**: Diseño profesional que coincida con el theme de la app

## 🎯 Configuración Mailtrap para Demostración Académica

### **Configuración .env**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username_mailtrap
MAIL_PASSWORD=tu_password_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@fooddesk.local
MAIL_FROM_NAME="FoodDesk Restaurant"
```

### **Flujo de Demostración**
1. **Admin crea empleado** → Laravel envía email a Mailtrap
2. **Abrir Mailtrap en navegador** → Mostrar email recibido con diseño profesional
3. **Click en "Verificar Email"** → Link redirige a aplicación local
4. **Página de confirmación** → Estado del empleado cambia a "Activo"
5. **Volver al dashboard** → Ver empleado activado en tiempo real

### **Ventajas para Demostración**
✅ **Visual impactante**: Email real en Mailtrap con diseño profesional
✅ **Flujo completo**: Desde creación hasta activación
✅ **Tiempo real**: Cambios visibles inmediatamente
✅ **Profesional**: Muestra conocimiento de sistemas de email
✅ **Interactivo**: Los evaluadores pueden hacer click y ver el proceso

## 📝 Orden de Implementación

1. Configurar SMTP en `.env`
2. Actualizar modelo User con `MustVerifyEmail`
3. Crear notification personalizada
4. Crear controlador y rutas de verificación
5. Modificar componente Livewire
6. Crear vistas de verificación
7. Actualizar UI para mostrar estados
8. Testing completo del flujo

## 🧪 Testing
- Verificar envío de emails
- Probar links de verificación
- Validar cambios de estado
- Comprobar casos de error (tokens expirados, etc.)