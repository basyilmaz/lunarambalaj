import AOS from 'aos';
import 'aos/dist/aos.css';

const isMobileViewport = window.matchMedia('(max-width: 767px)').matches;
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Initialize AOS only for non-mobile and motion-enabled contexts.
if (document.querySelector('[data-aos]') && !prefersReducedMotion) {
    AOS.init({
        disable: () => isMobileViewport || prefersReducedMotion,
        duration: 600,
        easing: 'ease-out',
        once: true,
        offset: 60,
    });
}

// Statistics Counter Animation
const statsCounters = document.querySelectorAll('.stat-counter');
if (statsCounters.length > 0 && !prefersReducedMotion) {
    const observerOptions = {
        threshold: 0.35,
        rootMargin: '0px'
    };

    const animateCounter = (element) => {
        const target = parseInt(element.getAttribute('data-target'));
        const duration = 1200;
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current).toLocaleString('tr-TR');
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString('tr-TR');
            }
        };

        updateCounter();
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('counted')) {
                entry.target.classList.add('counted');
                animateCounter(entry.target);
            }
        });
    }, observerOptions);

    statsCounters.forEach(counter => counterObserver.observe(counter));
}

// Smooth Scroll for Anchor Links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (href === '#') return;

        e.preventDefault();
        const target = document.querySelector(href);

        if (target) {
            const headerOffset = 80;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Sticky Header with Shadow on Scroll
const header = document.querySelector('header.sticky');
if (header) {
    let headerTicking = false;

    const updateHeaderShadow = () => {
        if (window.scrollY > 50) {
            header.classList.add('shadow-lg');
        } else {
            header.classList.remove('shadow-lg');
        }
        headerTicking = false;
    };

    window.addEventListener('scroll', () => {
        if (headerTicking) return;
        headerTicking = true;
        window.requestAnimationFrame(updateHeaderShadow);
    }, { passive: true });
}
