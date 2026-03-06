import './bootstrap';
import './mobile-menu';

const runWhenIdle = (callback, timeout = 1200) => {
    if ('requestIdleCallback' in window) {
        window.requestIdleCallback(callback, { timeout });
        return;
    }

    window.setTimeout(callback, Math.min(timeout, 800));
};

if (document.querySelector('.swiper')) {
    runWhenIdle(() => {
        import('./swiper-init');
    }, 900);
}

if (document.querySelector('[data-aos], .stat-counter, .parallax-section')) {
    runWhenIdle(() => {
        import('./animations');
    }, 1300);
}
