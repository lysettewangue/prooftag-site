import Alpine from 'alpinejs';

/**
 * Start Alpine after vanilla public enhancements.
 * Page modules may register Alpine.data() before calling this.
 */
export function startAlpine() {
    window.Alpine = Alpine;
    Alpine.start();
}

export { Alpine };
