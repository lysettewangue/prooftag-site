/**
 * Scroll-triggered reveals for public sections (About hub and similar).
 */
export function enhanceScrollReveal(root = document) {
    const nodes = [...root.querySelectorAll('[data-public-reveal]')];

    if (nodes.length === 0) {
        return () => {};
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        nodes.forEach((node) => node.classList.add('is-revealed'));

        return () => {};
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (! entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-revealed');
                observer.unobserve(entry.target);
            });
        },
        {
            rootMargin: '0px 0px -8% 0px',
            threshold: 0.12,
        },
    );

    nodes.forEach((node) => observer.observe(node));

    return () => observer.disconnect();
}
