import './bootstrap';
import './mobile-menu';

const runWhenIdle = (callback, timeout = 1200) => {
    if ('requestIdleCallback' in window) {
        window.requestIdleCallback(callback, { timeout });
        return;
    }

    window.setTimeout(callback, Math.min(timeout, 800));
};

const runAfterPageLoad = (callback, delay = 0) => {
    if (document.readyState === 'complete') {
        window.setTimeout(callback, delay);
        return;
    }

    window.addEventListener('load', () => window.setTimeout(callback, delay), { once: true });
};

const isMobileViewport = window.matchMedia('(max-width: 767px)').matches;
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (document.querySelector('.swiper')) {
    if (isMobileViewport) {
        runAfterPageLoad(() => {
            runWhenIdle(() => {
                import('./swiper-init');
            }, 1800);
        }, 1200);
    } else {
        runWhenIdle(() => {
            import('./swiper-init');
        }, 900);
    }
}

if (!prefersReducedMotion && document.querySelector('[data-aos], .stat-counter, .parallax-section')) {
    const animationTimeout = isMobileViewport ? 2200 : 1300;
    const animationDelay = isMobileViewport ? 900 : 0;

    runAfterPageLoad(() => {
        runWhenIdle(() => {
            import('./animations');
        }, animationTimeout);
    }, animationDelay);
}
