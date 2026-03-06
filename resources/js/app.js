import './bootstrap';
import './mobile-menu';

if (document.querySelector('.swiper')) {
    import('./swiper-init');
}

if (document.querySelector('[data-aos], .stat-counter, .parallax-section')) {
    import('./animations');
}
