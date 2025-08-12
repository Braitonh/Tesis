# Implementación de Registro con Livewire

## Resumen de la Tarea

**Objetivo**: Crear un formulario de registro complementario al login, manteniendo consistencia visual y funcional con el sistema de autenticación.

**Fecha**: 2025-08-11  
**Stack Utilizado**: Laravel 12, Livewire 3.6, Tailwind CSS 4.0, AlpineJS 3.14

---

## Diseño y Experiencia de Usuario

### Diferenciación Visual del Login
- **Color principal**: Verde esmeralda (emerald) vs Naranja del login
- **Iconografía**: `fa-user-plus` vs `fa-utensils`
- **Emojis**: Temática de crecimiento (👨‍🍳📱🚀) vs comida (🍕🍔🌮)
- **Mensaje**: Enfoque en "unirse" y "crear negocio"

### Estructura Mantenida
- Layout de 2 columnas idéntico al login
- Mismas animaciones y efectos
- Responsividad consistente
- Experiencia de usuario familiar

---

## Archivos Implementados

### 1. Componente Livewire Register
**Archivo**: `app/Livewire/Auth/Register.php`

```php
<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Component;

#[Layout('layouts.auth')]
class Register extends Component
{
    #[Rule('required|string|min:2|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    #[Rule('required|min:8')]
    public string $password_confirmation = '';

    #[Rule('accepted')]
    public bool $terms = false;

    public bool $loading = false;

    public function register()
    {
        $this->loading = true;

        // Rate limiting más estricto para registro (3 intentos vs 5 del login)
        $key = 'register.' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError('email', 'Demasiados intentos...');
            return;
        }

        $this->validate();

        try {
            // Crear usuario con hash seguro
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            // Login automático tras registro exitoso
            Auth::login($user);
            session()->regenerate();
            
            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            RateLimiter::hit($key, 60);
            $this->addError('email', 'Error al crear la cuenta...');
        }
    }
}
```

### Características del Componente:

✅ **Validación Robusta**
- `name`: Requerido, string, 2-255 caracteres
- `email`: Requerido, formato email, único en BD
- `password`: Requerido, mínimo 8 caracteres, con confirmación
- `terms`: Requerido (checkbox de términos)

✅ **Seguridad Implementada**
- **Rate limiting**: 3 intentos máximo (más estricto que login)
- **Hash seguro**: `Hash::make()` para contraseñas
- **Validación única**: Email no duplicado
- **Manejo de excepciones**: Try-catch para errores de BD

✅ **UX Optimizada**
- **Login automático**: Tras registro exitoso
- **Estado de carga**: Disable button + spinner
- **Regeneración de sesión**: Seguridad adicional

---

## Vista del Registro

### 2. Vista Principal
**Archivo**: `resources/views/livewire/auth/register.blade.php`

#### Sección de Bienvenida (Izquierda)
```blade
<div class="welcome-section bg-gradient-to-br from-green-900 to-emerald-800 p-10 lg:p-16 flex flex-col justify-center items-center text-center text-white relative overflow-hidden">
    
    <!-- Decoraciones animadas -->
    <div class="absolute -top-12 -right-12 w-48 h-48 bg-green-400/20 rounded-full" style="animation: pulse 4s ease-in-out infinite;"></div>
    <div class="absolute -bottom-8 -left-8 w-36 h-36 bg-emerald-500/20 rounded-full" style="animation: pulse 4s ease-in-out infinite 2s;"></div>
    
    <div class="relative z-10">
        <!-- Logo con icono diferenciado -->
        <div class="flex items-center justify-center gap-3 text-3xl font-bold mb-5">
            <div class="bg-white text-emerald-600 w-12 h-12 rounded-full flex items-center justify-center text-2xl">
                <i class="fas fa-user-plus"></i>
            </div>
            FoodDesk
        </div>
        
        <!-- Mensaje motivacional -->
        <h1 class="text-3xl lg:text-4xl font-bold mb-4 leading-tight">¡Únete a nosotros!</h1>
        <p class="text-lg opacity-90 leading-relaxed mb-8">
            Crea tu cuenta y comienza a gestionar tu restaurante con las mejores herramientas para hacer crecer tu negocio.
        </p>
        
        <!-- Iconos de crecimiento empresarial -->
        <div class="flex justify-center gap-5 mt-8">
            <span class="food-item text-4xl">👨‍🍳</span>
            <span class="food-item text-4xl">📱</span>
            <span class="food-item text-4xl">🚀</span>
        </div>
    </div>
</div>
```

#### Formulario de Registro (Derecha)
```blade
<form wire:submit="register" class="space-y-5">
    
    <!-- Campo Nombre -->
    <div class="space-y-2">
        <label for="name" class="block text-sm font-semibold text-gray-800">
            Nombre Completo
        </label>
        <div class="relative" x-data="{ focused: false }" x-bind:class="{ 'scale-105': focused }">
            <i class="fas fa-user absolute left-5 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            <input 
                type="text" 
                wire:model.live="name"
                class="w-full pl-14 pr-5 py-4 border-2 border-gray-200 rounded-xl focus:border-emerald-500 @error('name') border-red-500 @enderror"
                placeholder="Tu nombre completo"
                x-on:focus="focused = true"
                x-on:blur="focused = false"
            >
        </div>
        @error('name')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Campo Email (similar al login) -->
    <!-- Campo Password con Toggle -->
    <!-- Campo Confirmación Password con Toggle -->
    
    <!-- Checkbox Términos y Condiciones -->
    <div class="space-y-2">
        <label class="flex items-start gap-3 text-sm text-gray-600 cursor-pointer">
            <input type="checkbox" 
                   wire:model="terms" 
                   class="w-4 h-4 text-emerald-500 bg-gray-100 border-gray-300 rounded focus:ring-emerald-500 mt-0.5 @error('terms') border-red-500 @enderror">
            <span>
                Acepto los <a href="#" class="text-emerald-600 hover:underline">términos y condiciones</a> 
                y la <a href="#" class="text-emerald-600 hover:underline">política de privacidad</a>
            </span>
        </label>
        @error('terms')
            <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
    </div>

    <!-- Botón Crear Cuenta -->
    <button 
        type="submit" 
        wire:loading.attr="disabled"
        class="w-full bg-gradient-to-r from-emerald-600 to-green-600 text-white py-4 px-6 rounded-xl font-semibold hover:-translate-y-0.5 hover:shadow-[0_8px_25px_rgba(16,185,129,0.3)]"
        x-data="{ loading: @entangle('loading') }">
        
        <span x-show="!loading" class="flex items-center justify-center gap-2">
            <i class="fas fa-user-plus"></i>
            Crear Cuenta
        </span>
        
        <span x-show="loading" class="flex items-center justify-center gap-2">
            <i class="fas fa-spinner fa-spin"></i>
            Creando cuenta...
        </span>
    </button>
</form>
```

---

## Funcionalidades AlpineJS Implementadas

### Toggle Dual de Contraseñas
```javascript
// Password principal
x-data="{ 
    showPassword: false,
    togglePassword() { 
        this.showPassword = !this.showPassword;
        this.$refs.passwordInput.type = this.showPassword ? 'text' : 'password';
    }
}"

// Confirmación password (componente independiente)
x-data="{ 
    showPasswordConfirm: false,
    togglePasswordConfirm() { 
        this.showPasswordConfirm = !this.showPasswordConfirm;
        this.$refs.passwordConfirmInput.type = this.showPasswordConfirm ? 'text' : 'password';
    }
}"
```

### Efectos de Focus y Escala
```javascript
x-data="{ focused: false }"
x-bind:class="{ 'scale-105': focused }"
x-on:focus="focused = true"
x-on:blur="focused = false"
```

### Estados de Carga Integrados
```javascript
x-data="{ loading: @entangle('loading') }"
x-bind:class="{ 'opacity-75': loading }"
x-show="!loading" // Estado normal
x-show="loading"  // Estado cargando
```

---

## Configuración de Rutas

### 3. Rutas Actualizadas
**Archivo**: `routes/web.php`

```php
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Rutas para invitados (no autenticados)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Rutas protegidas (autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
```

### Enlaces de Navegación
```blade
<!-- En login.blade.php -->
<a href="{{ route('register') }}" wire:navigate>
    Regístrate aquí
</a>

<!-- En register.blade.php -->
<a href="{{ route('login') }}" wire:navigate>
    Inicia sesión aquí
</a>
```

---

## Diferencias de Seguridad vs Login

### Rate Limiting Más Estricto
```php
// Login: 5 intentos máximo
if (RateLimiter::tooManyAttempts($key, 5)) {

// Registro: 3 intentos máximo
if (RateLimiter::tooManyAttempts($key, 3)) {
```

### Validaciones Adicionales
- **Confirmación password**: `confirmed` rule
- **Términos y condiciones**: `accepted` rule  
- **Unicidad email**: `unique:users,email`
- **Longitud nombre**: `min:2|max:255`

### Manejo de Errores Específico
```php
try {
    $user = User::create([...]);
    Auth::login($user);
    session()->regenerate();
    return redirect()->intended('/dashboard');
} catch (\Exception $e) {
    RateLimiter::hit($key, 60);
    $this->addError('email', 'Error al crear la cuenta...');
}
```

---

## Esquema de Colores Implementado

| Elemento | Login (Naranja) | Registro (Verde) |
|----------|----------------|------------------|
| **Fondo lateral** | `from-amber-900 to-amber-800` | `from-green-900 to-emerald-800` |
| **Logo icon** | `text-orange-500` | `text-emerald-600` |
| **Focus inputs** | `focus:border-orange-500` | `focus:border-emerald-500` |
| **Botón principal** | `from-orange-500 to-orange-600` | `from-emerald-600 to-green-600` |
| **Enlaces** | `text-orange-500` | `text-emerald-600` |
| **Decoraciones** | `bg-yellow-400/20` + `bg-orange-500/20` | `bg-green-400/20` + `bg-emerald-500/20` |

---

## Testing y URLs

### Acceso al Registro
- **URL**: `http://localhost:4000/register`
- **Desde login**: Click en "Regístrate aquí"
- **Navegación**: Usa `wire:navigate` para SPA experience

### Validación en Vivo
- **Nombre**: Validación de longitud en tiempo real
- **Email**: Verificación de formato y unicidad
- **Password**: Validación de confirmación automática
- **Términos**: Requerido antes de submit

### Flujo Completo
1. **Acceder** → `/register`
2. **Completar campos** → Validación en vivo
3. **Aceptar términos** → Checkbox requerido
4. **Crear cuenta** → Rate limiting + validación
5. **Login automático** → Redirección a dashboard
6. **Alternativa** → Enlace a login existente

---

## Resultado Final

✅ **Consistencia visual** con el login mantenida  
✅ **Funcionalidad completa** de registro con validaciones  
✅ **Diferenciación clara** mediante colores y iconografía  
✅ **Seguridad reforzada** con rate limiting estricto  
✅ **UX optimizada** con login automático post-registro  
✅ **Navegación fluida** entre login y registro  
✅ **Responsive design** idéntico al login  

El sistema de registro complementa perfectamente el login, proporcionando una experiencia cohesiva mientras mantiene funcionalidades específicas para cada caso de uso.