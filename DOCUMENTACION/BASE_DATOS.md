# Diseño de Base de Datos

## Esquema de Base de Datos

### Tablas Principales

#### 1. users
Gestiona usuarios con múltiples roles (cliente, empleado, administrador)

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NULL,
    address TEXT NULL,
    role ENUM('cliente', 'empleado', 'admin') DEFAULT 'cliente',
    status ENUM('activo', 'inactivo', 'suspendido') DEFAULT 'activo',
    avatar VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 2. empleado_roles
Tabla para gestionar múltiples roles de empleados

```sql
CREATE TABLE empleado_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    rol ENUM('ventas', 'cocina', 'delivery') NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

#### 3. categorias
Categorías de productos del menú

```sql
CREATE TABLE categorias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    imagen VARCHAR(255) NULL,
    orden INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 4. productos
Catálogo de productos disponibles

```sql
CREATE TABLE productos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200) NOT NULL,
    descripcion TEXT NULL,
    precio DECIMAL(8,2) NOT NULL,
    categoria_id BIGINT UNSIGNED NOT NULL,
    imagen VARCHAR(255) NULL,
    stock INT DEFAULT 0,
    tiempo_preparacion INT DEFAULT 15, -- minutos
    activo BOOLEAN DEFAULT TRUE,
    destacado BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);
```

#### 5. pedidos
Órdenes de compra de los clientes

```sql
CREATE TABLE pedidos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    numero_pedido VARCHAR(20) UNIQUE NOT NULL,
    cliente_id BIGINT UNSIGNED NULL, -- NULL para guests
    cliente_nombre VARCHAR(255) NOT NULL,
    cliente_email VARCHAR(255) NOT NULL,
    cliente_telefono VARCHAR(20) NOT NULL,
    direccion_entrega TEXT NULL,
    tipo_entrega ENUM('delivery', 'pickup') NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    impuestos DECIMAL(10,2) DEFAULT 0,
    descuento DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'confirmado', 'preparando', 'listo', 'en_camino', 'entregado', 'cancelado') DEFAULT 'pendiente',
    metodo_pago ENUM('efectivo', 'tarjeta', 'transferencia') NOT NULL,
    estado_pago ENUM('pendiente', 'pagado', 'fallido') DEFAULT 'pendiente',
    empleado_ventas_id BIGINT UNSIGNED NULL,
    empleado_cocina_id BIGINT UNSIGNED NULL,
    empleado_delivery_id BIGINT UNSIGNED NULL,
    tiempo_estimado INT DEFAULT 30, -- minutos
    fecha_entrega TIMESTAMP NULL,
    notas TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (cliente_id) REFERENCES users(id),
    FOREIGN KEY (empleado_ventas_id) REFERENCES users(id),
    FOREIGN KEY (empleado_cocina_id) REFERENCES users(id),
    FOREIGN KEY (empleado_delivery_id) REFERENCES users(id)
);
```

#### 6. pedido_detalles
Items específicos de cada pedido

```sql
CREATE TABLE pedido_detalles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    producto_id BIGINT UNSIGNED NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(8,2) NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    notas_especiales TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
```

#### 7. pedido_historiales
Seguimiento de cambios de estado de pedidos

```sql
CREATE TABLE pedido_historiales (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    estado_anterior ENUM('pendiente', 'confirmado', 'preparando', 'listo', 'en_camino', 'entregado', 'cancelado') NULL,
    estado_nuevo ENUM('pendiente', 'confirmado', 'preparando', 'listo', 'en_camino', 'entregado', 'cancelado') NOT NULL,
    usuario_id BIGINT UNSIGNED NULL,
    notas TEXT NULL,
    created_at TIMESTAMP NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES users(id)
);
```

#### 8. valoraciones
Sistema de valoraciones y comentarios

```sql
CREATE TABLE valoraciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pedido_id BIGINT UNSIGNED NOT NULL,
    cliente_id BIGINT UNSIGNED NULL,
    puntuacion INT NOT NULL CHECK (puntuacion >= 1 AND puntuacion <= 5),
    comentario TEXT NULL,
    respuesta_admin TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (pedido_id) REFERENCES pedidos(id),
    FOREIGN KEY (cliente_id) REFERENCES users(id)
);
```

#### 9. cupones
Sistema de cupones y descuentos

```sql
CREATE TABLE cupones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    tipo ENUM('porcentaje', 'monto_fijo') NOT NULL,
    valor DECIMAL(8,2) NOT NULL,
    minimo_compra DECIMAL(8,2) DEFAULT 0,
    usos_maximos INT NULL,
    usos_actuales INT DEFAULT 0,
    fecha_inicio DATE NOT NULL,
    fecha_fin DATE NOT NULL,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

#### 10. configuraciones
Configuraciones generales del sistema

```sql
CREATE TABLE configuraciones (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    clave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT NOT NULL,
    descripcion VARCHAR(255) NULL,
    tipo ENUM('string', 'number', 'boolean', 'json') DEFAULT 'string',
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

## Relaciones entre Tablas

### Relaciones Principales

1. **users → empleado_roles** (1:N)
   - Un usuario puede tener múltiples roles de empleado

2. **categorias → productos** (1:N)
   - Una categoría puede tener múltiples productos

3. **users → pedidos** (1:N)
   - Un cliente puede tener múltiples pedidos
   - Un empleado puede atender múltiples pedidos

4. **pedidos → pedido_detalles** (1:N)
   - Un pedido puede tener múltiples items

5. **productos → pedido_detalles** (1:N)
   - Un producto puede estar en múltiples pedidos

6. **pedidos → pedido_historiales** (1:N)
   - Un pedido puede tener múltiples cambios de estado

7. **pedidos → valoraciones** (1:1)
   - Un pedido puede tener una valoración

## Índices Recomendados

```sql
-- Índices para mejora de rendimiento
CREATE INDEX idx_pedidos_estado ON pedidos(estado);
CREATE INDEX idx_pedidos_fecha ON pedidos(created_at);
CREATE INDEX idx_pedidos_cliente ON pedidos(cliente_id);
CREATE INDEX idx_productos_categoria ON productos(categoria_id);
CREATE INDEX idx_productos_activo ON productos(activo);
CREATE INDEX idx_empleado_roles_user ON empleado_roles(user_id);
CREATE INDEX idx_valoraciones_pedido ON valoraciones(pedido_id);
```

## Datos Iniciales (Seeders)

### Configuraciones Básicas
```php
// Configuraciones iniciales del sistema
'horario_apertura' => '08:00',
'horario_cierre' => '22:00',
'tiempo_preparacion_default' => 25,
'costo_delivery' => 15.00,
'zona_delivery_km' => 5,
'moneda' => 'PEN',
'impuesto_porcentaje' => 18
```

### Usuario Administrador por Defecto
```php
// Usuario admin inicial
'name' => 'Administrador',
'email' => 'admin@tesis.com',
'password' => Hash::make('admin123'),
'role' => 'admin'
```

### Categorías Iniciales
```php
// Categorías básicas de comida rápida
'Hamburguesas', 'Pizzas', 'Pollo Frito', 
'Bebidas', 'Postres', 'Combos'
```

## Consideraciones de Performance

### Optimizaciones
- Índices en campos de búsqueda frecuente
- Particionado de tabla `pedido_historiales` por fecha
- Cache de consultas frecuentes (categorías, productos activos)
- Soft deletes para productos y categorías

### Backup y Mantenimiento
- Backup diario automatizado
- Limpieza de historiales antiguos (>1 año)
- Monitoreo de queries lentas
- Optimización de índices periódicamente