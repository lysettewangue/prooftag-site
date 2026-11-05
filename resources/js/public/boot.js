import { bootCentresMaps } from './centres-map';
import { enhancePublicHeader } from './header';
import { enhanceScrollReveal } from './scroll-reveal';

/**
 * Public-site vanilla foundation. Safe to call once per page load.
 */
export function bootPublicSite(root = document) {
    const cleanups = [enhancePublicHeader(root), enhanceScrollReveal(root)];

    bootCentresMaps(root);

    document.documentElement.dataset.publicJs = 'ready';

    return () => {
        cleanups.forEach((cleanup) => {
            if (typeof cleanup === 'function') {
                cleanup();
            }
        });
        delete document.documentElement.dataset.publicJs;
    };
}
