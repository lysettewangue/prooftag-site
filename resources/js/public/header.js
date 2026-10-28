const WIDE_MEDIA = '(min-width: 64.0625rem)';

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function updateScrolledState(header) {
    header.toggleAttribute('data-scrolled', window.scrollY > 8);
}

function closeMobileNav(details) {
    if (details.open) {
        details.open = false;
    }
}

/**
 * Progressive enhancement for the sticky public header.
 * Native <details> remains usable with JavaScript disabled.
 */
export function enhancePublicHeader(root = document) {
    const header = root.querySelector('[data-public-header]');

    if (! header) {
        return () => {};
    }

    const mobileNav = header.querySelector('[data-public-mobile-nav]');
    const wideQuery = window.matchMedia(WIDE_MEDIA);
    let ticking = false;

    const onScroll = () => {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(() => {
            updateScrolledState(header);
            ticking = false;
        });
    };

    const onPointerDown = (event) => {
        if (! mobileNav?.open) {
            return;
        }

        if (! mobileNav.contains(event.target)) {
            closeMobileNav(mobileNav);
        }
    };

    const onKeyDown = (event) => {
        if (event.key === 'Escape') {
            closeMobileNav(mobileNav);
        }
    };

    const onWideChange = (event) => {
        if (event.matches) {
            closeMobileNav(mobileNav);
        }
    };

    updateScrolledState(header);
    window.addEventListener('scroll', onScroll, { passive: true });
    document.addEventListener('pointerdown', onPointerDown);
    document.addEventListener('keydown', onKeyDown);

    if (typeof wideQuery.addEventListener === 'function') {
        wideQuery.addEventListener('change', onWideChange);
    } else {
        wideQuery.addListener(onWideChange);
    }

    if (! prefersReducedMotion()) {
        header.setAttribute('data-motion', 'ready');
    }

    return () => {
        window.removeEventListener('scroll', onScroll);
        document.removeEventListener('pointerdown', onPointerDown);
        document.removeEventListener('keydown', onKeyDown);

        if (typeof wideQuery.removeEventListener === 'function') {
            wideQuery.removeEventListener('change', onWideChange);
        } else {
            wideQuery.removeListener(onWideChange);
        }
    };
}
