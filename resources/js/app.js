import './bootstrap';

import Swiper from 'swiper';
import { Autoplay, EffectFade, Pagination, Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-fade';
import 'swiper/css/pagination';
import 'swiper/css/navigation';

document.addEventListener('DOMContentLoaded', () => {
    const heroSwiperEl = document.querySelector('.hero-swiper');
    if (heroSwiperEl) {
        new Swiper(heroSwiperEl, {
            modules: [Autoplay, EffectFade, Pagination],
            effect: 'fade',
            fadeEffect: { crossFade: true },
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.hero-pagination',
                clickable: true,
            },
            loop: true,
            speed: 800,
        });
    }

    const featuredSwiperEl = document.querySelector('.featured-books-swiper');
    if (featuredSwiperEl) {
        new Swiper(featuredSwiperEl, {
            modules: [Navigation, Autoplay],
            slidesPerView: 1,
            spaceBetween: 24,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.featured-next',
                prevEl: '.featured-prev',
            },
            loop: true,
            speed: 800,
            breakpoints: {
                640: { slidesPerView: 2 },
                768: { slidesPerView: 3 },
                1024: { slidesPerView: 4 },
                1280: { slidesPerView: 5 },
            },
        });
    }
});
