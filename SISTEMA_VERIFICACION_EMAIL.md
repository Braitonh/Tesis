# Sistema de Verificación de Email con Mailtrap

## Descripción General

Se implementó un sistema dual de manejo de usuarios que diferencia entre **clientes** y **empleados** con diferentes flujos de verificación:

- **Clientes**: Se registran desde el formulario público, crean su contraseña directamente y son verificados automáticamente
- **Empleados**: Se crean desde el dashboard admin, reciben un email para crear su contraseña de forma segura

### ✨ Nueva Funcionalidad: Pantalla de Verificación Pendiente
Se agregó una pantalla dedicada para usuarios no verificados que evita loops de redirección y proporciona una mejor experiencia de usuario.

## Configuración de Mailtrap

### Variables de Entorno (.env)
```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=2988e0707ae8ad
MAIL_PASSWORD=c742568b4babb4
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="admin@fooddesk.local"
MAIL_FROM_NAME="FoodDesk Restaurant"
```

## Estructura de Base de Datos

### Migración: `add_email_verification_fields_to_users_table`
```php
Schema::table('users', function (Blueprint $table) {
    $table->string('verification_token')->nullable();
    $table->boolean('password_created')->default(false);
});
```

**Campos agregados:**
- `verification_token`: Token único para verificación de email
- `password_created`: Flag que indica si el usuario ya creó su contraseña
- `email_verified_at`: Ya existía en Laravel por defecto

## Modelo User Actualizado

### Implementación de MustVerifyEmail
```php
class User extends Authenticatable implements MustVerifyEmail
{
    protected $fillable = [
        // ... campos existentes
        'verification_token',
        'password_created',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token', // Oculto por seguridad
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_created' => 'boolean',
        ];
    }
}
```

### Métodos Personalizados
- `generateVerificationToken()`: Genera y guarda un token único
- `verifyEmailWithToken($token)`: Verifica el email con el token
- `markEmailAsVerified()`: Marca el email como verificado

## Notificación de Bienvenida

### WelcomeUserNotification
```php
class WelcomeUserNotification extends Notification
{
    public function toMail(object $notifiable): MailMessage
    {
        $url = url('/create-password/' . $this->token);
        
        return (new MailMessage)
            ->subject('¡Bienvenido a FoodDesk!')
            ->greeting('¡Hola ' . $this->user->name . '!')
            ->line('Tu cuenta ha sido creada exitosamente.')
            ->action('Crear mi contraseña', $url)
            ->line('Este enlace expirará en 24 horas por seguridad.')
            ->salutation('¡Gracias por elegirnos!');
    }
}
```

## Sistema de Rutas

### Rutas de Verificación
```php
Route::middleware('guest')->group(function () {
    // Formulario de creación de contraseña
    Route::get('/create-password/{token}', [PasswordCreationController::class, 'show'])
        ->name('password.show');
    
    // Procesamiento de creación de contraseña
    Route::post('/create-password/{token}', [PasswordCreationController::class, 'store'])
        ->name('password.create');
});
```

### Rutas Protegidas
```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Todas las rutas del dashboard requieren email verificado
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Resto de rutas protegidas...
});
```

## Controlador de Creación de Contraseña

### PasswordCreationController
```php
class PasswordCreationController extends Controller
{
    public function show(string $token)
    {
        $user = User::where('verification_token', $token)->first();
        
        if (!$user) {
            return redirect('/')->with('error', 'Token de verificación inválido.');
        }
        
        if ($user->password_created) {
            return redirect('/login')->with('info', 'Tu contraseña ya ha sido creada. Puedes iniciar sesión.');
        }
        
        // NO cerrar sesiones para evitar conflictos con otros usuarios
        return view('auth.create-password', compact('user', 'token'));
    }
    
    public function store(Request $request, string $token)
    {
        // Validación y creación de contraseña
        $user->update([
            'password' => Hash::make($request->password),
            'password_created' => true,
        ]);
        
        $user->markEmailAsVerified();
        
        // Redirige al login SIN login automático para evitar conflictos de sesión
        return redirect('/login')->with('success', '¡Contraseña creada exitosamente! Ya puedes iniciar sesión con tu email y nueva contraseña.');
    }
}
```

## Middleware de Verificación

### EnsureEmailIsVerified
```php
class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && !$user->hasVerifiedEmail()) {
            return redirect('/login')->with('error', 'Debes verificar tu email.');
        }
        
        return $next($request);
    }
}
```

Registrado en `bootstrap/app.php`:
```php
$middleware->alias([
    'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
]);
```

## Flujos de Usuario

### 1. Registro de Cliente (Sin Verificación)
1. Usuario completa formulario de registro público
2. Se crea con `role='cliente'` y `email_verified_at=now()`
3. Login automático y redirección al dashboard
4. **No requiere verificación de email**

### 2. Creación de Empleado (Con Verificación)
1. Admin crea empleado desde dashboard
2. Se crea con contraseña temporal y `password_created=false`
3. Se genera token de verificación único
4. Se envía email con enlace para crear contraseña
5. Empleado accede al enlace y crea su contraseña
6. Email se marca como verificado automáticamente

## Vista de Creación de Contraseña

### auth/create-password.blade.php
- Formulario seguro con validación
- Confirma contraseña
- Muestra requisitos de contraseña
- Interfaz consistente con el sistema

## Pantalla de Verificación Pendiente

### EmailVerificationController
```php
class EmailVerificationController extends Controller
{
    public function notice()
    {
        $user = Auth::user();
        
        if ($user->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }
        
        return view('auth.verify-email', compact('user'));
    }
    
    public function resend(Request $request)
    {
        $user = $request->user();
        
        // Solo reenviar si es un empleado que no ha creado contraseña
        if (!$user->password_created && $user->role !== 'cliente') {
            $token = $user->generateVerificationToken();
            $user->notify(new WelcomeUserNotification($user, $token));
            
            return back()->with('status', 'Email de verificación reenviado exitosamente.');
        }
        
        return back()->with('error', 'No se puede reenviar el email de verificación.');
    }
}
```

### Vista: auth/verify-email.blade.php
- Pantalla informativa para usuarios no verificados
- Botón para reenviar email de verificación
- Instrucciones paso a paso
- Previene loops de redirección
- Opción para cerrar sesión

## Middleware Actualizado

### EnsureEmailIsVerified (Mejorado)
```php
class EnsureEmailIsVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if ($user && !$user->hasVerifiedEmail()) {
            // Si ya está en la página de verificación, permitir acceso
            if ($request->routeIs('verification.notice') || $request->routeIs('verification.resend')) {
                return $next($request);
            }
            
            // Redirigir a la página de verificación pendiente
            return redirect()->route('verification.notice');
        }
        
        return $next($request);
    }
}
```

## Rutas Actualizadas

```php
// Email verification routes (authenticated but not necessarily verified)
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    
    Route::post('/email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->name('verification.resend');
});
```

## Archivos Modificados

### Archivos Creados
- `database/migrations/2025_08_18_134448_add_email_verification_fields_to_users_table.php`
- `app/Notifications/WelcomeUserNotification.php`
- `app/Http/Controllers/Auth/PasswordCreationController.php`
- `app/Http/Controllers/Auth/EmailVerificationController.php` ✨ **NUEVO**
- `app/Http/Middleware/EnsureEmailIsVerified.php`
- `resources/views/auth/create-password.blade.php`
- `resources/views/auth/verify-email.blade.php` ✨ **NUEVO**

### Archivos Modificados
- `app/Models/User.php` - Implementa MustVerifyEmail y métodos personalizados
- `app/Livewire/Auth/Register.php` - Crear clientes sin verificación
- `app/Livewire/Empleados/Empleados.php` - Crear empleados con verificación
- `resources/views/components/modals/empleados/form-empleado.blade.php` - Info sobre email
- `routes/web.php` - Rutas de verificación ✨ **ACTUALIZADO**
- `bootstrap/app.php` - Registro de middleware
- `app/Http/Middleware/EnsureEmailIsVerified.php` - Previene loops ✨ **MEJORADO**

## Seguridad Implementada

1. **Tokens únicos**: Cada empleado recibe un token único no reutilizable
2. **Middleware de verificación**: Protege rutas sensibles
3. **Validación robusta**: Contraseñas seguras requeridas
4. **Ocultación de tokens**: Los tokens no se exponen en respuestas JSON
5. **Gestión de estados**: Previene uso de tokens ya utilizados
6. **Aislamiento de sesiones**: NO interfiere con sesiones activas de otros usuarios ✨ **NUEVO**
7. **Flujo seguro**: Login manual después de crear contraseña evita conflictos

## Flujo de Email con Mailtrap

1. **Empleado creado** → Token generado → Email enviado a Mailtrap
2. **Email recibido** → Usuario hace clic en enlace → Formulario de contraseña
3. **Contraseña creada** → Email marcado como verificado → **Redirección a login**
4. **Login manual** → Usuario inicia sesión con sus nuevas credenciales → Acceso completo

### ⚠️ Cambio Importante en el Flujo
El sistema ya **NO realiza login automático** después de crear la contraseña. Esto evita conflictos de sesiones y cierre de sesiones de administradores que están creando empleados desde otros navegadores.

## Comandos de Prueba

```bash
# Levantar contenedores
make up

# Ejecutar migraciones
make migrate

# Acceder al dashboard de empleados
http://localhost:8000/empleados

# Crear empleado y revisar email en Mailtrap
# Acceso: https://mailtrap.io/inboxes
```

Esta implementación proporciona un sistema seguro y diferenciado de verificación de email que se adapta a las necesidades específicas de clientes y empleados en FoodDesk.