export function enhanceContactPage(root = document) {
    const form = root.querySelector('[data-contact-form]');
    const subject = form?.querySelector('[name="subject"]');
    const triggers = Array.from(root.querySelectorAll('[data-contact-subject]'));

    if (!(form instanceof HTMLFormElement) || !(subject instanceof HTMLInputElement) || triggers.length === 0) {
        return () => {};
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const cleanups = triggers.map((trigger) => {
        const handleClick = () => {
            const value = trigger.getAttribute('data-contact-subject')?.trim();

            if (!value) {
                return;
            }

            subject.value = value;
            subject.dispatchEvent(new Event('input', { bubbles: true }));
            subject.dispatchEvent(new Event('change', { bubbles: true }));
            form.scrollIntoView({
                behavior: prefersReducedMotion.matches ? 'auto' : 'smooth',
                block: 'center',
            });
            window.setTimeout(() => subject.focus({ preventScroll: true }), prefersReducedMotion.matches ? 0 : 450);
        };

        trigger.addEventListener('click', handleClick);

        return () => trigger.removeEventListener('click', handleClick);
    });

    return () => cleanups.forEach((cleanup) => cleanup());
}
