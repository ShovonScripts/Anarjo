import './bootstrap';

import Swiper from 'swiper';
import { Autoplay, EffectFade, Pagination, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

// Store active swiper instances to prevent memory leaks during Livewire navigation
const activeSwipers = new Map();

function initSwipers() {
    // 1. Hero Swiper
    const heroSwiperEl = document.querySelector('.hero-swiper');
    if (heroSwiperEl && !heroSwiperEl.swiper) {
        const swiper = new Swiper(heroSwiperEl, {
            modules: [Autoplay, EffectFade, Pagination],
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.hero-pagination',
                clickable: true,
            },
            loop: heroSwiperEl.querySelectorAll('.swiper-slide').length > 1,
            speed: 700,
            watchSlidesProgress: true,
        });
        activeSwipers.set('hero', swiper);
    }

    // 2. Featured Book Rotator (Hero Left)
    const featuredBookSwiperEl = document.querySelector('.featured-book-swiper');
    if (featuredBookSwiperEl && !featuredBookSwiperEl.swiper) {
        const slideCount = featuredBookSwiperEl.querySelectorAll('.swiper-slide').length;
        const swiper = new Swiper(featuredBookSwiperEl, {
            modules: [Autoplay, Pagination, Navigation],
            slidesPerView: 1,
            spaceBetween: 0,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: '.featured-book-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.featured-book-next',
                prevEl: '.featured-book-prev',
            },
            loop: slideCount > 1,
            rewind: slideCount > 1,
            speed: 500,
        });
        activeSwipers.set('featuredBook', swiper);
    }

    // 3. Branding Mobile Swiper
    const brandingMobileSwiperEl = document.querySelector('.branding-mobile-swiper');
    if (brandingMobileSwiperEl && !brandingMobileSwiperEl.swiper) {
        const slideCount = brandingMobileSwiperEl.querySelectorAll('.swiper-slide').length;
        const swiper = new Swiper(brandingMobileSwiperEl, {
            modules: [Autoplay, Pagination],
            slidesPerView: 1,
            spaceBetween: 0,
            autoplay: {
                delay: 5000,
                disableOnInteraction: true,
            },
            pagination: {
                el: '.branding-mobile-pagination',
                clickable: true,
            },
            loop: slideCount > 1,
            speed: 500,
            grabCursor: true,
        });
        activeSwipers.set('brandingMobile', swiper);
    }

    // 4. Multi-item book carousels
    const carouselConfigs = [
        { selector: '.featured-books-swiper', next: '.featured-next', prev: '.featured-prev', key: 'featuredBooks' },
        { selector: '.discounted-books-swiper', next: '.discounted-next', prev: '.discounted-prev', key: 'discountedBooks' },
        { selector: '.popular-books-swiper', next: '.popular-next', prev: '.popular-prev', key: 'popularBooks' },
    ];

    carouselConfigs.forEach(({ selector, next, prev, key }) => {
        const el = document.querySelector(selector);
        if (el && !el.swiper) {
            const slideCount = el.querySelectorAll('.swiper-slide').length;
            const swiper = new Swiper(el, {
                modules: [Navigation, Autoplay],
                slidesPerView: 2.2,
                spaceBetween: 12,
                autoplay: {
                    delay: 5500,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                navigation: {
                    nextEl: next,
                    prevEl: prev,
                },
                loop: slideCount > 6,
                speed: 600,
                watchSlidesProgress: true,
                breakpoints: {
                    480: { slidesPerView: 2, spaceBetween: 14 },
                    640: { slidesPerView: 3, spaceBetween: 18 },
                    768: { slidesPerView: 4, spaceBetween: 20 },
                    1024: { slidesPerView: 5, spaceBetween: 22 },
                    1280: { slidesPerView: 6, spaceBetween: 24 },
                    1536: { slidesPerView: 7, spaceBetween: 24 },
                },
            });
            activeSwipers.set(key, swiper);
        }
    });
}

function initTypewriter() {
    const typedText = document.querySelector('.about-typewriter .typed-text');
    if (!typedText || typedText.dataset.initialized) return;

    const fullText = typedText.getAttribute('data-text') || '';
    const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (reduceMotion) {
        typedText.textContent = fullText;
        return;
    }

    typedText.dataset.initialized = 'true';
    let index = 0;
    const speed = 60;
    const typeTick = () => {
        typedText.textContent = fullText.slice(0, index);
        index += 1;
        if (index <= fullText.length) {
            setTimeout(typeTick, speed);
        }
    };
    typeTick();
}

function initScrollReveals() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.05, rootMargin: '0px 0px 50px 0px' });

    document.querySelectorAll('.reveal:not(.revealed)').forEach(el => observer.observe(el));
}

function initAll() {
    initSwipers();
    initTypewriter();
    initScrollReveals();
}

// Run on standard load
document.addEventListener('DOMContentLoaded', initAll);

// Run on Livewire page transitions (wire:navigate)
document.addEventListener('livewire:navigated', initAll);
