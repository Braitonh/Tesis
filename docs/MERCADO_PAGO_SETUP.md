# Configuración de Mercado Pago Checkout Pro

Esta guía explica cómo configurar Mercado Pago Checkout Pro en modo test para el sistema de pedidos.

## Requisitos Previos

- Cuenta de Mercado Pago (puedes crear una en [mercadopago.com](https://www.mercadopago.com))
- Acceso a las credenciales de test de Mercado Pago

## Paso 1: Obtener Credenciales de Test

1. Inicia sesión en tu cuenta de Mercado Pago
2. Ve a la sección de **Desarrolladores** → **Tus integraciones**
3. Crea una nueva aplicación o selecciona una existente
4. En la sección **Credenciales de prueba**, encontrarás:
   - **Access Token** (Token de acceso)
   - **Public Key** (Clave pública)

### Credenciales de Test

Las credenciales de test tienen el prefijo `TEST-` y te permiten realizar pagos de prueba sin usar dinero real.

## Paso 2: Configurar Variables de Entorno

Agrega las siguientes variables a tu archivo `.env`:

```env
# Mercado Pago - Modo Test
MERCADOPAGO_ACCESS_TOKEN_TEST=TEST-tu_access_token_aqui
MERCADOPAGO_PUBLIC_KEY_TEST=TEST-tu_public_key_aqui
MERCADOPAGO_MODE=test

# Para producción (cuando estés listo)
# MERCADOPAGO_ACCESS_TOKEN_PRODUCTION=tu_access_token_produccion
# MERCADOPAGO_PUBLIC_KEY_PRODUCTION=tu_public_key_produccion
# MERCADOPAGO_MODE=production
```

**Importante:** 
- Reemplaza `tu_access_token_aqui` y `tu_public_key_aqui` con tus credenciales reales
- No compartas estas credenciales públicamente
- Las credenciales de test solo funcionan en el entorno de pruebas de Mercado Pago

## Paso 3: Ejecutar Migraciones

Ejecuta las migraciones para agregar los campos necesarios a la base de datos:

```bash
php artisan migrate
```

Esto agregará los siguientes campos a la tabla `transacciones`:
- `mercado_pago_preference_id`: ID de la preferencia de pago
- `mercado_pago_payment_id`: ID del pago procesado
- `mercado_pago_status`: Estado del pago en Mercado Pago

También actualizará los enums para incluir `mercado_pago` como método de pago.

## Paso 4: Configurar Webhook (Opcional pero Recomendado)

Para recibir notificaciones automáticas de Mercado Pago cuando se procese un pago:

1. Ve a tu aplicación en Mercado Pago
2. Configura la URL del webhook: `https://tu-dominio.com/api/mercado-pago/webhook`
3. Mercado Pago enviará notificaciones POST a esta URL cuando cambie el estado de un pago

**Nota:** En desarrollo local, puedes usar herramientas como [ngrok](https://ngrok.com) para exponer tu servidor local y recibir webhooks.

## Tarjetas de Prueba

**IMPORTANTE:** Para usar las tarjetas de prueba en el sandbox de Mercado Pago, **debes estar logueado** con tu cuenta de Mercado Pago. Si no estás logueado, verás el error "No es posible continuar el pago con esta tarjeta".

**Pasos para probar:**
1. Cuando Mercado Pago te redirija al checkout, haz clic en "Iniciar sesión" o "Login"
2. **Recomendación:** Usa tu cuenta real de Mercado Pago (no una cuenta de test)
   - Las tarjetas de prueba funcionan perfectamente con cuentas reales en el entorno sandbox
   - Las cuentas de test pueden requerir validación de email que puede no llegar
3. Si usas una cuenta de test y te pide validación de email:
   - El código puede tardar en llegar o no llegar
   - Mejor opción: usa tu cuenta personal de Mercado Pago
4. Una vez logueado, podrás usar las tarjetas de prueba listadas abajo

Mercado Pago proporciona tarjetas de prueba para simular diferentes escenarios. Usa estas tarjetas en el checkout de Mercado Pago:

### Tarjetas que Siempre Aproban

| Tarjeta | CVV | Fecha Vencimiento | Nombre |
|---------|-----|-------------------|--------|
| 5031 7557 3453 0604 | 123 | 11/25 | APRO |
| 4509 9535 6623 3704 | 123 | 11/25 | APRO |

### Tarjetas que Siempre Rechazan

| Tarjeta | CVV | Fecha Vencimiento | Nombre | Motivo |
|---------|-----|-------------------|--------|--------|
| 5031 4332 1540 6351 | 123 | 11/25 | OTHE | Rechazo genérico |
| 5031 7557 3453 0604 | 123 | 11/25 | CONT | Fondos insuficientes |
| 5031 7557 3453 0604 | 123 | 11/25 | CALL | Tarjeta no autorizada |

### Tarjetas para Otros Escenarios

| Tarjeta | CVV | Fecha | Nombre | Escenario |
|---------|-----|-------|--------|-----------|
| 5031 4332 1540 6351 | 123 | 11/25 | CONT | Fondos insuficientes |
| 5031 4332 1540 6351 | 123 | 11/25 | EXPI | Tarjeta vencida |
| 5031 4332 1540 6351 | 123 | 11/25 | FORM | Error en formulario |

**Nota:** El CVV puede ser cualquier número de 3 dígitos. La fecha de vencimiento debe ser futura.

## Flujo de Pago

1. **Usuario selecciona Mercado Pago** en el checkout
2. **Usuario confirma el pedido** → Se crea el pedido y la transacción
3. **Sistema crea preferencia** en Mercado Pago con los datos del pedido
4. **Usuario es redirigido** a la página de pago de Mercado Pago
5. **Usuario completa el pago** en Mercado Pago
6. **Mercado Pago redirige** al usuario de vuelta con el resultado
7. **Webhook confirma** el pago (procesamiento asíncrono)

## Estados de Pago

El sistema mapea los estados de Mercado Pago a estados internos:

| Estado Mercado Pago | Estado Interno | Descripción |
|---------------------|----------------|-------------|
| `approved` | `aprobado` | Pago aprobado exitosamente |
| `rejected` | `rechazado` | Pago rechazado |
| `cancelled` | `rechazado` | Pago cancelado |
| `refunded` | `rechazado` | Pago reembolsado |
| `pending` | `procesando` | Pago pendiente |
| `in_process` | `procesando` | Pago en proceso |

## Solución de Problemas

### Error: "Mercado Pago access token no configurado"

- Verifica que las variables de entorno estén configuradas correctamente
- Asegúrate de que el archivo `.env` tenga las credenciales
- Ejecuta `php artisan config:clear` para limpiar la caché de configuración

### Error: "No se pudo crear la preferencia de pago"

- Verifica que las credenciales sean válidas y de test
- Revisa los logs en `storage/logs/laravel.log`
- Asegúrate de que la URL de callback sea accesible

### El webhook no se recibe

- Verifica que la URL del webhook sea pública y accesible
- En desarrollo, usa ngrok o similar para exponer tu servidor local
- Revisa los logs del servidor para ver si llegan las peticiones

### El pago se procesa pero no se actualiza el pedido

- Verifica que el webhook esté configurado correctamente
- Revisa los logs para ver si hay errores al procesar el webhook
- Verifica que el `external_reference` coincida con el ID del pedido

## Cambiar a Producción

Cuando estés listo para usar Mercado Pago en producción:

1. Obtén tus credenciales de producción (sin prefijo `TEST-`)
2. Actualiza las variables de entorno:
   ```env
   MERCADOPAGO_ACCESS_TOKEN_PRODUCTION=tu_access_token_produccion
   MERCADOPAGO_PUBLIC_KEY_PRODUCTION=tu_public_key_produccion
   MERCADOPAGO_MODE=production
   ```
3. Configura el webhook con la URL de producción
4. Prueba con un pago real de bajo monto antes de lanzar

## Recursos Adicionales

- [Documentación oficial de Mercado Pago](https://www.mercadopago.com/developers/es/docs)
- [SDK de PHP de Mercado Pago](https://github.com/mercadopago/dx-php)
- [Checkout Pro - Documentación](https://www.mercadopago.com/developers/es/docs/checkout-pro/landing)

## Soporte

Si tienes problemas con la integración:
1. Revisa los logs en `storage/logs/laravel.log`
2. Verifica la configuración de las credenciales
3. Consulta la documentación oficial de Mercado Pago

