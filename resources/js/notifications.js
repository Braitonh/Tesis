/**
 * Sistema de Notificaciones Toast
 * Gestiona notificaciones visuales y sonoras en tiempo real
 */

// Configuración
const CONFIG = {
    maxToasts: 3,
    defaultDuration: 5000,
    soundEnabled: true,
    soundVolume: 0.5
};

// Estado global
let toastCount = 0;
let badgeCount = 0;
let audioContext = null;

/**
 * Mostrar una notificación toast
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de notificación (success, warning, error, info)
 * @param {number} duration - Duración en milisegundos
 */
window.showToast = function(message, type = 'info', duration = CONFIG.defaultDuration) {
    const container = document.getElementById('toast-container');
    if (!container) {
        console.error('Toast container no encontrado');
        return;
    }

    // Limitar número de toasts
    const existingToasts = container.querySelectorAll('.toast');
    if (existingToasts.length >= CONFIG.maxToasts) {
        // Remover el toast más antiguo
        removeToast(existingToasts[0]);
    }

    // Crear elemento toast
    const toastId = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
    const toast = createToastElement(toastId, message, type, duration);

    // Insertar en el container
    container.appendChild(toast);

    // Animar entrada
    setTimeout(() => {
        toast.classList.add('toast-enter');
    }, 10);

    // Auto-eliminar después de la duración
    const timeoutId = setTimeout(() => {
        removeToast(toast);
    }, duration);

    // Permitir cerrar manualmente
    const closeBtn = toast.querySelector('.toast-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            clearTimeout(timeoutId);
            removeToast(toast);
        });
    }

    // Cerrar al hacer click en el toast
    toast.addEventListener('click', () => {
        clearTimeout(timeoutId);
        removeToast(toast);
    });

    toastCount++;
};

/**
 * Crear elemento HTML del toast
 */
function createToastElement(id, message, type, duration) {
    const toast = document.createElement('div');
    toast.id = id;
    toast.className = `toast toast-${type}`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'polite');
    toast.style.setProperty('--duration', `${duration}ms`);

    // Iconos según tipo
    const icons = {
        success: '<i class="fas fa-check-circle"></i>',
        warning: '<i class="fas fa-exclamation-triangle"></i>',
        error: '<i class="fas fa-times-circle"></i>',
        info: '<i class="fas fa-info-circle"></i>'
    };

    toast.innerHTML = `
        <div class="toast-icon">
            ${icons[type] || icons.info}
        </div>
        <div class="toast-content">
            ${message}
        </div>
        <button class="toast-close" aria-label="Cerrar notificación">
            <i class="fas fa-times"></i>
        </button>
        <div class="toast-progress"></div>
    `;

    return toast;
}

/**
 * Remover un toast con animación
 */
function removeToast(toast) {
    if (!toast || !toast.parentNode) return;

    toast.classList.remove('toast-enter');
    toast.classList.add('toast-exit');

    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 300);
}

/**
 * Reproducir sonido de notificación
 */
window.playNotificationSound = function() {
    if (!CONFIG.soundEnabled) return;

    // Intentar reproducir el archivo de audio
    const audio = new Audio('/sounds/notification.mp3');
    audio.volume = CONFIG.soundVolume;
    
    audio.play().catch(err => {
        // Si falla el archivo, usar un beep generado
        console.log('Archivo de sonido no disponible, usando beep generado');
        playBeep();
    });
};

/**
 * Generar un beep usando Web Audio API (fallback)
 */
function playBeep() {
    try {
        // Crear contexto de audio si no existe
        if (!audioContext) {
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
        }

        const oscillator = audioContext.createOscillator();
        const gainNode = audioContext.createGain();

        oscillator.connect(gainNode);
        gainNode.connect(audioContext.destination);

        oscillator.frequency.value = 800; // Frecuencia en Hz
        oscillator.type = 'sine';

        gainNode.gain.setValueAtTime(CONFIG.soundVolume, audioContext.currentTime);
        gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);

        oscillator.start(audioContext.currentTime);
        oscillator.stop(audioContext.currentTime + 0.3);
    } catch (err) {
        console.error('Error al reproducir sonido:', err);
    }
}

/**
 * Actualizar contador del badge de notificaciones
 * @param {number} count - Número a mostrar
 */
window.updateBadgeCount = function(count) {
    const badge = document.getElementById('notification-badge');
    if (!badge) return;

    badgeCount = count;

    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.classList.remove('hidden');
        
        // Animación de pulso
        badge.classList.add('animate-pulse');
        setTimeout(() => {
            badge.classList.remove('animate-pulse');
        }, 1000);
    } else {
        badge.classList.add('hidden');
    }
};

/**
 * Incrementar badge
 */
window.incrementBadge = function() {
    window.updateBadgeCount(badgeCount + 1);
};

/**
 * Decrementar badge
 */
window.decrementBadge = function() {
    window.updateBadgeCount(Math.max(0, badgeCount - 1));
};

/**
 * Resetear badge
 */
window.resetBadge = function() {
    window.updateBadgeCount(0);
};

/**
 * Habilitar/deshabilitar sonido
 */
window.toggleNotificationSound = function(enabled) {
    CONFIG.soundEnabled = enabled;
    localStorage.setItem('notificationSoundEnabled', enabled);
};

/**
 * Configurar volumen del sonido
 */
window.setNotificationVolume = function(volume) {
    CONFIG.soundVolume = Math.max(0, Math.min(1, volume));
    localStorage.setItem('notificationVolume', CONFIG.soundVolume);
};

// Cargar preferencias del usuario
document.addEventListener('DOMContentLoaded', () => {
    const savedSoundEnabled = localStorage.getItem('notificationSoundEnabled');
    if (savedSoundEnabled !== null) {
        CONFIG.soundEnabled = savedSoundEnabled === 'true';
    }

    const savedVolume = localStorage.getItem('notificationVolume');
    if (savedVolume !== null) {
        CONFIG.soundVolume = parseFloat(savedVolume);
    }

    console.log('✅ Sistema de notificaciones inicializado');
    console.log('📦 Funciones disponibles:', {
        showToast: typeof window.showToast,
        playNotificationSound: typeof window.playNotificationSound,
        updateBadgeCount: typeof window.updateBadgeCount,
        incrementBadge: typeof window.incrementBadge,
        decrementBadge: typeof window.decrementBadge,
        resetBadge: typeof window.resetBadge
    });
});

