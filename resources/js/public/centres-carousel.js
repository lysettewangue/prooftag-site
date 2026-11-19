export function enhanceCentresCarousel(root = document) {
    const carousels = [...root.querySelectorAll('[data-centres-carousel]')];

    if (carousels.length === 0) {
        return () => {};
    }

    const cleanups = carousels.map((carousel) => {
        const track = carousel.querySelector('[data-centres-carousel-track]');
        const previous = carousel.querySelector('[data-centres-carousel-prev]');
        const next = carousel.querySelector('[data-centres-carousel-next]');

        if (! track || ! previous || ! next) {
            return () => {};
        }

        const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let frame = 0;

        const cardStep = () => {
            const firstCard = track.firstElementChild;

            if (! firstCard) return track.clientWidth;

            const styles = window.getComputedStyle(track);
            const gap = Number.parseFloat(styles.columnGap || styles.gap || '0');

            return firstCard.getBoundingClientRect().width + gap;
        };

        const update = () => {
            frame = 0;
            const maximum = Math.max(0, track.scrollWidth - track.clientWidth);
            previous.disabled = track.scrollLeft <= 2;
            next.disabled = track.scrollLeft >= maximum - 2;
        };

        const requestUpdate = () => {
            if (frame) return;
            frame = window.requestAnimationFrame(update);
        };

        const move = (direction) => {
            track.scrollBy({
                left: cardStep() * direction,
                behavior: reducedMotion ? 'auto' : 'smooth',
            });
        };

        const onPrevious = () => move(-1);
        const onNext = () => move(1);
        const onKeydown = (event) => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                move(-1);
            } else if (event.key === 'ArrowRight') {
                event.preventDefault();
                move(1);
            }
        };

        previous.addEventListener('click', onPrevious);
        next.addEventListener('click', onNext);
        track.addEventListener('scroll', requestUpdate, { passive: true });
        track.addEventListener('keydown', onKeydown);
        window.addEventListener('resize', requestUpdate, { passive: true });
        update();

        return () => {
            previous.removeEventListener('click', onPrevious);
            next.removeEventListener('click', onNext);
            track.removeEventListener('scroll', requestUpdate);
            track.removeEventListener('keydown', onKeydown);
            window.removeEventListener('resize', requestUpdate);
            if (frame) window.cancelAnimationFrame(frame);
        };
    });

    return () => cleanups.forEach((cleanup) => cleanup());
}
