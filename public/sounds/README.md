# Archivos de Sonido para Notificaciones

## Archivo Requerido: `notification.mp3`

Para que las notificaciones sonoras funcionen correctamente, necesitas agregar un archivo de sonido llamado `notification.mp3` en este directorio.

### Características Recomendadas:

- **Formato**: MP3 (compatibilidad universal)
- **Duración**: 1-2 segundos
- **Volumen**: Moderado (el sistema ajusta a 0.5 por defecto)
- **Tipo**: Sonido sutil, no intrusivo (ej: "ding", "pop", "beep suave")

### Opciones para Obtener el Sonido:

#### 1. Descargar de Bibliotecas Gratuitas
- [Freesound.org](https://freesound.org/) - Buscar "notification" o "alert"
- [Zapsplat.com](https://www.zapsplat.com/) - Categoría "UI Sounds"
- [Mixkit.co](https://mixkit.co/free-sound-effects/) - Sound Effects gratuitos

#### 2. Usar Sonidos del Sistema
Puedes usar un sonido simple de notificación de tu sistema operativo.

#### 3. Generar con IA
Usar herramientas como:
- [Soundraw](https://soundraw.io/)
- [AIVA](https://www.aiva.ai/)

### Instalación:

1. Descarga o crea tu archivo de sonido
2. Renómbralo a `notification.mp3`
3. Colócalo en este directorio: `public/sounds/notification.mp3`
4. Verifica que el archivo sea accesible en: `http://tu-dominio/sounds/notification.mp3`

### Fallback Automático:

Si el archivo `notification.mp3` no existe, el sistema automáticamente utilizará un **beep generado** mediante Web Audio API como alternativa. El sistema seguirá funcionando sin el archivo mp3.

### Configuración de Usuario:

Los usuarios pueden:
- Activar/desactivar el sonido con `window.toggleNotificationSound(true/false)`
- Ajustar el volumen con `window.setNotificationVolume(0.0 - 1.0)`
- Las preferencias se guardan en localStorage

### Testing:

Para probar el sonido manualmente en la consola del navegador:

```javascript
// Probar el sonido
window.playNotificationSound();

// Deshabilitar sonido
window.toggleNotificationSound(false);

// Habilitar sonido
window.toggleNotificationSound(true);

// Ajustar volumen (0.0 a 1.0)
window.setNotificationVolume(0.3);
```

---

**Nota**: El archivo `notification.mp3` no está incluido en el repositorio por razones de licencia y tamaño. Cada instalación debe agregar su propio archivo de sonido.


