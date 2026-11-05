import { Alpine } from './alpine';

/**
 * Homepage hero slideshow (progressive enhancement).
 * Without JS, the first slide remains visible.
 */
export function registerHomeHeroSlideshow() {
    Alpine.data('homeHeroSlideshow', (config = {}) => ({
        index: 0,
        count: Number(config.count ?? 1),
        intervalMs: Number(config.interval ?? 6000),
        timer: null,
        reducedMotion: false,

        init() {
            this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (this.count < 2 || this.reducedMotion) {
                return;
            }

            this.start();
        },

        start() {
            this.stop();
            this.timer = window.setInterval(() => this.next(), this.intervalMs);
        },

        stop() {
            if (this.timer !== null) {
                window.clearInterval(this.timer);
                this.timer = null;
            }
        },

        next() {
            this.index = (this.index + 1) % this.count;
        },

        prev() {
            this.index = (this.index - 1 + this.count) % this.count;
            this.start();
        },

        go(index) {
            this.index = index;
            this.start();
        },

        destroy() {
            this.stop();
        },
    }));
}
