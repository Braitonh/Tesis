# Design System - Sistema de Autenticación FoodDesk

## Paleta de Colores

### 🟠 Login (Tema Naranja)
| Elemento | Tailwind CSS | Valor Hex | Uso |
|----------|-------------|-----------|-----|
| **Fondo principal** | `from-orange-500 to-orange-600` | `#f97316` → `#ea580c` | Fondo animado exterior |
| **Panel lateral** | `from-amber-900 to-amber-800` | `#78350f` → `#92400e` | Sección de bienvenida |
| **Logo icon** | `text-orange-500` | `#f97316` | Ícono del logo en círculo blanco |
| **Focus inputs** | `focus:border-orange-500` | `#f97316` | Border activo en campos |
| **Botón principal** | `from-orange-500 to-orange-600` | `#f97316` → `#ea580c` | Botón "Iniciar Sesión" |
| **Enlaces activos** | `text-orange-500` | `#f97316` | "Regístrate aquí", "Olvidaste contraseña" |
| **Hover enlaces** | `text-orange-600` | `#ea580c` | Estado hover de enlaces |
| **Decoración 1** | `bg-yellow-400/20` | `#facc15` (20% opacity) | Círculo animado superior |
| **Decoración 2** | `bg-orange-500/20` | `#f97316` (20% opacity) | Círculo animado inferior |

### 🟢 Register (Tema Verde Esmeralda)
| Elemento | Tailwind CSS | Valor Hex | Uso |
|----------|-------------|-----------|-----|
| **Fondo principal** | `from-emerald-500 to-green-600` | `#10b981` → `#16a34a` | Fondo animado exterior |
| **Panel lateral** | `from-green-900 to-emerald-800` | `#14532d` → `#065f46` | Sección de bienvenida |
| **Logo icon** | `text-emerald-600` | `#059669` | Ícono del logo en círculo blanco |
| **Focus inputs** | `focus:border-emerald-500` | `#10b981` | Border activo en campos |
| **Botón principal** | `from-emerald-600 to-green-600` | `#059669` → `#16a34a` | Botón "Crear Cuenta" |
| **Enlaces activos** | `text-emerald-600` | `#059669` | "Inicia sesión aquí", términos |
| **Hover enlaces** | `text-emerald-700` | `#047857` | Estado hover de enlaces |
| **Decoración 1** | `bg-green-400/20` | `#4ade80` (20% opacity) | Círculo animado superior |
| **Decoración 2** | `bg-emerald-500/20` | `#10b981` (20% opacity) | Círculo animado inferior |

### 🎨 Colores Neutros (Compartidos)
| Elemento | Tailwind CSS | Valor Hex | Uso |
|----------|-------------|-----------|-----|
| **Contenedor principal** | `bg-white` | `#ffffff` | Fondo del formulario |
| **Texto principal** | `text-gray-800` | `#1f2937` | Títulos y labels |
| **Texto secundario** | `text-gray-600` | `#4b5563` | Subtítulos y descripciones |
| **Placeholder** | `text-gray-400` | `#9ca3af` | Iconos de campos y placeholders |
| **Borders inactivos** | `border-gray-200` | `#e5e7eb` | Borders por defecto |
| **Fondo inputs** | `bg-gray-50` | `#f9fafb` | Fondo inactivo de campos |
| **Errores** | `text-red-500` | `#ef4444` | Mensajes de error |
| **Errores border** | `border-red-500` | `#ef4444` | Border de error en campos |

---

## Tipografía

### Jerarquía de Texto
| Elemento | Tailwind CSS | Tamaño | Peso | Uso |
|----------|-------------|---------|------|-----|
| **Título principal** | `text-3xl lg:text-4xl font-bold` | 30px/36px | 700 | "¡Bienvenido de vuelta!", "¡Únete a nosotros!" |
| **Título formulario** | `text-2xl lg:text-3xl font-bold` | 24px/30px | 700 | "Iniciar Sesión", "Crear Cuenta" |
| **Logo marca** | `text-3xl font-bold` | 30px | 700 | "FoodDesk" |
| **Labels** | `text-sm font-semibold` | 14px | 600 | Labels de campos |
| **Inputs** | `text-base` | 16px | 400 | Texto dentro de campos |
| **Subtítulos** | `text-lg opacity-90` | 18px | 400 | Descripción en panel lateral |
| **Descripciones** | `text-gray-600` | 16px | 400 | "Accede a tu panel..." |
| **Enlaces** | `text-sm font-semibold` | 14px | 600 | "Regístrate aquí", "Términos" |
| **Texto pequeño** | `text-sm` | 14px | 400 | Texto de ayuda y dividers |

---

## Espaciado y Dimensiones

### Contenedor Principal
```css
max-width: 900px;
grid-template-columns: 1fr 1fr; /* Desktop */
grid-template-columns: 1fr; /* Mobile */
border-radius: 32px;
box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
```

### Padding y Margins
| Elemento | Desktop | Mobile | Uso |
|----------|---------|--------|-----|
| **Sección lateral** | `p-10 lg:p-16` | 40px | 64px | Padding interno |
| **Sección formulario** | `p-10 lg:p-16` | 40px | 64px | Padding interno |
| **Entre campos** | `space-y-6` | 24px | Separación vertical |
| **Entre campos register** | `space-y-5` | 20px | Más compacto |
| **Margin botones** | `mt-6` | 24px | Separación superior |

### Tamaños de Campos
```css
/* Inputs */
padding: 16px 20px 16px 50px; /* top right bottom left */
border-radius: 12px;
border-width: 2px;

/* Botones */  
padding: 16px 24px;
border-radius: 12px;

/* Logo circle */
width: 50px;
height: 50px;
border-radius: 50%;
```

---

## Animaciones

### Keyframes Definidos
```css
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
    50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
}

@keyframes pulse {
    0%, 100% { transform: scale(1); opacity: 0.7; }
    50% { transform: scale(1.1); opacity: 1; }
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

@keyframes slideUp {
    to { opacity: 1; transform: translateY(0); }
}
```

### Aplicación de Animaciones
| Elemento | Animación | Duración | Delay | Uso |
|----------|-----------|----------|-------|-----|
| **Formas flotantes** | `float` | 6s | 0s, 2s, 4s, 1s | Fondo animado |
| **Decoraciones panel** | `pulse` | 4s | 0s, 2s | Círculos en sección lateral |
| **Emojis comida** | `bounce` | 2s | 0s, 0.5s, 1s | Ilustraciones |
| **Container entrada** | `slideUp` | 0.8s | - | Aparición del formulario |
| **Focus inputs** | `scale-105` | 300ms | - | Efecto al enfocar campo |
| **Hover botones** | `translate-y-0.5` | 300ms | - | Elevación en hover |

---

## Estados Interactivos

### Estados de Campos (Inputs)
```css
/* Estado normal */
border: 2px solid #e5e7eb;
background: #f9fafb;

/* Estado focus */
border-color: #f97316; /* orange-500 login */
border-color: #10b981; /* emerald-500 register */
background: white;
box-shadow: 0 0 0 3px rgba(255,107,53,0.1); /* login */
box-shadow: 0 0 0 3px rgba(16,185,129,0.1); /* register */

/* Estado error */
border-color: #ef4444; /* red-500 */
```

### Estados de Botones
```css
/* Estado normal */
background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); /* login */
background: linear-gradient(135deg, #059669 0%, #16a34a 100%); /* register */

/* Estado hover */
transform: translateY(-2px);
box-shadow: 0 8px 25px rgba(255,107,53,0.3); /* login */
box-shadow: 0 8px 25px rgba(16,185,129,0.3); /* register */

/* Estado loading */
opacity: 0.75;
cursor: not-allowed;
```

---

## Iconografía

### Font Awesome Icons
| Contexto | Login | Register | Uso |
|----------|-------|----------|-----|
| **Logo principal** | `fas fa-utensils` | `fas fa-user-plus` | Identificación de marca/función |
| **Campo email** | `fas fa-envelope` | `fas fa-envelope` | Consistente en ambos |
| **Campo password** | `fas fa-lock` | `fas fa-lock` | Consistente en ambos |
| **Campo nombre** | - | `fas fa-user` | Específico del registro |
| **Toggle password** | `fas fa-eye / fa-eye-slash` | `fas fa-eye / fa-eye-slash` | Visibilidad contraseña |
| **Botón submit** | `fas fa-sign-in-alt` | `fas fa-user-plus` | Acción específica |
| **Loading state** | `fas fa-spinner fa-spin` | `fas fa-spinner fa-spin` | Estado de carga |
| **Social login** | `fab fa-google`, `fab fa-facebook-f` | Igual | Redes sociales |

### Emojis Temáticos
| Contexto | Emojis | Significado |
|----------|--------|-------------|
| **Login** | 🍕🍔🌮 | Comida, producto principal |
| **Register** | 👨‍🍳📱🚀 | Crecimiento empresarial, herramientas |

---

## Responsive Design

### Breakpoints
```css
/* Mobile First */
.container {
    grid-template-columns: 1fr; /* < 1024px */
    max-width: 400px;
    margin: 20px;
}

/* Desktop */
@media (min-width: 1024px) {
    .container {
        grid-template-columns: 1fr 1fr;
        max-width: 900px;
    }
}
```

### Ajustes por Tamaño
| Elemento | Mobile | Desktop |
|----------|---------|---------|
| **Padding secciones** | `p-10` (40px) | `p-16` (64px) |
| **Título principal** | `text-3xl` (30px) | `text-4xl` (36px) |
| **Título formulario** | `text-2xl` (24px) | `text-3xl` (30px) |
| **Botones sociales** | `flex-col` | `flex-row` |
| **Emojis** | `text-3xl` (30px) | `text-4xl` (36px) |

---

## Implementación con Tailwind CSS 4.0

### Clases Personalizadas Necesarias
```css
/* En app.css o componente */
.login-container {
    animation: slideUp 0.8s ease forwards;
    opacity: 0;
    transform: translateY(30px);
}

.bg-shape {
    animation: float 6s ease-in-out infinite;
}

.food-item {
    animation: bounce 2s ease-in-out infinite;
}

/* Sombras personalizadas */
.shadow-custom {
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
}

.shadow-button-orange {
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

.shadow-button-emerald {
    box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
}
```

### Configuración Tailwind Extendida
```javascript
// tailwind.config.js
module.exports = {
  theme: {
    extend: {
      colors: {
        'food-orange': {
          50: '#fff7ed',
          500: '#f97316',
          600: '#ea580c',
        },
        'food-emerald': {
          50: '#ecfdf5',
          500: '#10b981', 
          600: '#059669',
        }
      },
      animation: {
        'float': 'float 6s ease-in-out infinite',
        'slide-up': 'slideUp 0.8s ease forwards',
      }
    }
  }
}
```

---

## Consistencia Visual

### Elementos Que Deben Mantenerse Iguales
- ✅ Estructura de layout (2 columnas)
- ✅ Animaciones de fondo
- ✅ Tipografía y jerarquías  
- ✅ Espaciado y padding
- ✅ Estados interactivos
- ✅ Iconos de campos comunes

### Elementos Que Pueden Diferir
- 🎨 Colores principales (naranja vs verde)
- 🎨 Decoraciones del panel lateral
- 🎨 Iconografía específica (utensils vs user-plus)
- 🎨 Emojis temáticos
- 🎨 Mensajes y copy

Este design system asegura consistencia visual mientras permite diferenciación funcional entre login y registro.