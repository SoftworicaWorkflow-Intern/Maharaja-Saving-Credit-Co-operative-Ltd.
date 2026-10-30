document.querySelectorAll('[data-hero-carousel]').forEach((carousel) => {
    const slides = [...carousel.querySelectorAll('[data-hero-slide]')];
    const indicators = [...carousel.querySelectorAll('[data-hero-indicator]')];
    let activeIndex = 0;

    const showSlide = (index) => {
        activeIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === activeIndex;

            slide.classList.toggle('opacity-0', !isActive);
            slide.hidden = !isActive;
            slide.setAttribute('aria-hidden', String(!isActive));
        });

        indicators.forEach((indicator, indicatorIndex) => {
            const isActive = indicatorIndex === activeIndex;

            indicator.setAttribute('aria-current', String(isActive));
            indicator.classList.toggle('w-7', isActive);
            indicator.classList.toggle('bg-[#FFD91A]', isActive);
            indicator.classList.toggle('w-2.5', !isActive);
            indicator.classList.toggle('bg-white/70', !isActive);
        });
    };

    carousel.querySelector('[data-hero-previous]')?.addEventListener('click', () => {
        showSlide(activeIndex - 1);
    });

    carousel.querySelector('[data-hero-next]')?.addEventListener('click', () => {
        showSlide(activeIndex + 1);
    });

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => showSlide(index));
    });
});

document.querySelectorAll('[data-rotating-headline]').forEach((headline) => {
    const slides = [...headline.querySelectorAll('[data-headline-slide]')];
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const interval = 3500;
    const transitionDuration = 350;
    let activeIndex = 0;
    let isHovered = false;
    let isTransitioning = false;
    let intervalId = null;
    let transitionTimeout = null;

    if (slides.length < 2) {
        return;
    }

    const syncActiveSlide = () => {
        slides.forEach((slide, index) => {
            const isActive = index === activeIndex;

            slide.setAttribute('aria-hidden', String(!isActive));
            slide.classList.toggle('translate-y-0', isActive);
            slide.classList.toggle('opacity-100', isActive);
            slide.classList.toggle('translate-y-3', !isActive);
            slide.classList.toggle('opacity-0', !isActive);
        });
    };

    const startRotation = () => {
        if (!reducedMotion.matches && intervalId === null) {
            intervalId = window.setInterval(showNextSlide, interval);
        }
    };

    const stopRotation = () => {
        if (intervalId !== null) {
            window.clearInterval(intervalId);
            intervalId = null;
        }
    };

    const showNextSlide = () => {
        if (isHovered || isTransitioning) {
            return;
        }

        const currentSlide = slides[activeIndex];
        const nextIndex = (activeIndex + 1) % slides.length;
        const nextSlide = slides[nextIndex];
        isTransitioning = true;

        currentSlide.classList.remove('translate-y-0', 'opacity-100');
        currentSlide.classList.add('translate-y-3', 'opacity-0');

        transitionTimeout = window.setTimeout(() => {
            activeIndex = nextIndex;
            currentSlide.setAttribute('aria-hidden', 'true');
            nextSlide.setAttribute('aria-hidden', 'false');
            nextSlide.classList.remove('translate-y-3', 'opacity-0');
            nextSlide.classList.add('translate-y-0', 'opacity-100');
            isTransitioning = false;
            transitionTimeout = null;
        }, transitionDuration);
    };

    headline.addEventListener('mouseenter', () => {
        isHovered = true;
        stopRotation();
    });
    headline.addEventListener('mouseleave', () => {
        isHovered = false;
        startRotation();
    });
    reducedMotion.addEventListener('change', (event) => {
        if (event.matches) {
            stopRotation();

            if (transitionTimeout !== null) {
                window.clearTimeout(transitionTimeout);
                transitionTimeout = null;
                isTransitioning = false;
            }

            syncActiveSlide();
        } else {
            startRotation();
        }
    });

    startRotation();
});

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    const inputId = button.getAttribute('aria-controls');
    const input = inputId ? document.getElementById(inputId) : null;

    if (!(input instanceof HTMLInputElement)) {
        return;
    }

    button.addEventListener('click', () => {
        const willBeVisible = input.type === 'password';

        input.type = willBeVisible ? 'text' : 'password';
        button.setAttribute('aria-pressed', String(willBeVisible));
        button.setAttribute('aria-label', willBeVisible ? 'Hide password' : 'Show password');
        button.querySelector('[data-eye-visible]')?.classList.toggle('hidden', !willBeVisible);
        button.querySelector('[data-eye-hidden]')?.classList.toggle('hidden', willBeVisible);
    });
});

document.querySelectorAll('[data-mobile-nav-toggle]').forEach((button) => {
    const navigationId = button.getAttribute('aria-controls');
    const navigation = navigationId ? document.getElementById(navigationId) : null;

    if (!(navigation instanceof HTMLElement)) {
        return;
    }

    const setNavigationOpen = (isOpen) => {
        button.setAttribute('aria-expanded', String(isOpen));
        button.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
        navigation.hidden = !isOpen;
        navigation.classList.toggle('hidden', !isOpen);
        button.querySelector('[data-menu-open]')?.classList.toggle('hidden', isOpen);
        button.querySelector('[data-menu-close]')?.classList.toggle('hidden', !isOpen);
    };

    button.addEventListener('click', () => {
        setNavigationOpen(button.getAttribute('aria-expanded') !== 'true');
    });

    document.querySelectorAll('[data-print-page]').forEach((button) => {
        button.addEventListener('click', () => window.print());
    });

    navigation.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setNavigationOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && button.getAttribute('aria-expanded') === 'true') {
            setNavigationOpen(false);
            button.focus();
        }
    });
});
