import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const isMobileViewport = window.matchMedia('(max-width: 767px)').matches;
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Hero Slider
const heroSlider = document.querySelector('.hero-swiper');
if (heroSlider) {
    const heroModules = isMobileViewport
        ? [Pagination, Autoplay]
        : [Navigation, Pagination, Autoplay];

    new Swiper('.hero-swiper', {
        modules: heroModules,
        autoplay: prefersReducedMotion ? false : {
            delay: isMobileViewport ? 6500 : 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: !isMobileViewport,
        },
        loop: !isMobileViewport,
        speed: isMobileViewport ? 450 : 800,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: isMobileViewport ? false : {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
}

// Product Categories Carousel
const categoriesCarousel = document.querySelector('.categories-swiper');
if (categoriesCarousel) {
    new Swiper('.categories-swiper', {
        modules: [Navigation, Pagination],
        slidesPerView: 1,
        spaceBetween: 30,
        loop: false,
        speed: 450,
        navigation: {
            nextEl: '.categories-next',
            prevEl: '.categories-prev',
        },
        pagination: {
            el: '.categories-pagination',
            clickable: true,
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
}

// Testimonials Carousel
const testimonialsCarousel = document.querySelector('.testimonials-swiper');
if (testimonialsCarousel) {
    new Swiper('.testimonials-swiper', {
        modules: [Navigation, Pagination, Autoplay],
        slidesPerView: 1,
        spaceBetween: 30,
        loop: false,
        autoplay: prefersReducedMotion ? false : {
            delay: 7000,
            disableOnInteraction: false,
        },
        speed: 450,
        pagination: {
            el: '.testimonials-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.testimonials-next',
            prevEl: '.testimonials-prev',
        },
        breakpoints: {
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        },
    });
}

// Product Gallery (for product detail page)
const productGallery = document.querySelector('.product-gallery-swiper');
if (productGallery) {
    const galleryThumbs = new Swiper('.product-gallery-thumbs', {
        spaceBetween: 10,
        slidesPerView: 4,
        watchSlidesProgress: true,
    });

    new Swiper('.product-gallery-swiper', {
        modules: [Navigation, Pagination],
        spaceBetween: 10,
        navigation: {
            nextEl: '.gallery-next',
            prevEl: '.gallery-prev',
        },
        thumbs: {
            swiper: galleryThumbs,
        },
    });
}

// Related Products Carousel
const relatedProducts = document.querySelector('.related-products-swiper');
if (relatedProducts) {
    new Swiper('.related-products-swiper', {
        modules: [Navigation],
        slidesPerView: 1,
        spaceBetween: 20,
        speed: 600,
        navigation: {
            nextEl: '.related-next',
            prevEl: '.related-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 4,
            },
        },
    });
}
