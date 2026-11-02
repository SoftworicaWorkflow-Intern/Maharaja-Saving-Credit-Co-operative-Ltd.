document.querySelectorAll('.fade-in').forEach((element) => {
    element.classList.toggle('show', window.matchMedia('(prefers-reduced-motion: reduce)').matches);
});

const revealElements = document.querySelectorAll('.fade-in:not(.show)');

if (revealElements.length > 0) {
    if (!('IntersectionObserver' in window)) {
        revealElements.forEach((element) => element.classList.add('show'));
    } else {
        const revealObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.15 });

        revealElements.forEach((element) => revealObserver.observe(element));
    }
}

document.querySelectorAll('.service-card-slide-right').forEach((element) => {
    element.classList.toggle('show', window.matchMedia('(prefers-reduced-motion: reduce)').matches);
});

document.querySelectorAll('.leadership-portrait-slide-left, .leadership-portrait-slide-right').forEach((element) => {
    element.classList.toggle('show', window.matchMedia('(prefers-reduced-motion: reduce)').matches);
});

const slideRightElements = document.querySelectorAll('.service-card-slide-right:not(.show)');

if (slideRightElements.length > 0) {
    if (!('IntersectionObserver' in window)) {
        slideRightElements.forEach((element) => element.classList.add('show'));
    } else {
        const slideRightObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.15 });

        slideRightElements.forEach((element) => slideRightObserver.observe(element));
    }
}

const leadershipPortraits = document.querySelectorAll(
    '.leadership-portrait-slide-left:not(.show), .leadership-portrait-slide-right:not(.show)'
);

if (leadershipPortraits.length > 0) {
    if (!('IntersectionObserver' in window)) {
        leadershipPortraits.forEach((portrait) => portrait.classList.add('show'));
    } else {
        const leadershipPortraitObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('show');
                observer.unobserve(entry.target);
            });
        }, { threshold: 0.15 });

        leadershipPortraits.forEach((portrait) => leadershipPortraitObserver.observe(portrait));
    }
}

document.querySelectorAll('[data-hero-carousel]').forEach((carousel) => {
    if (carousel.dataset.heroAutoplayInitialized) return;
    carousel.dataset.heroAutoplayInitialized = 'true';

    const slides = [...carousel.querySelectorAll('[data-hero-slide]')];
    const indicators = [...carousel.querySelectorAll('[data-hero-indicator]')];
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const autoInterval = 4500;
    let activeIndex = 0;
    let timer = null;

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

    const nextSlide = () => {
        showSlide(activeIndex + 1);
    };

    const startAutoPlay = () => {
        if (!reducedMotion.matches && slides.length > 1 && timer === null) {
            timer = window.setInterval(nextSlide, autoInterval);
        }
    };

    const stopAutoPlay = () => {
        if (timer !== null) {
            window.clearInterval(timer);
            timer = null;
        }
    };

    const resetAutoPlay = () => {
        stopAutoPlay();
        startAutoPlay();
    };

    carousel.querySelector('[data-hero-previous]')?.addEventListener('click', () => {
        showSlide(activeIndex - 1);
        resetAutoPlay();
    });

    carousel.querySelector('[data-hero-next]')?.addEventListener('click', () => {
        showSlide(activeIndex + 1);
        resetAutoPlay();
    });

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            showSlide(index);
            resetAutoPlay();
        });
    });

    carousel.addEventListener('mouseenter', stopAutoPlay);
    carousel.addEventListener('mouseleave', startAutoPlay);
    carousel.addEventListener('focusin', stopAutoPlay);
    carousel.addEventListener('focusout', startAutoPlay);

    startAutoPlay();
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

document.querySelectorAll('[data-report-fullscreen]').forEach((button) => {
    const viewerId = button.getAttribute('data-report-fullscreen');
    const viewer = viewerId ? document.getElementById(viewerId) : null;

    if (!(viewer instanceof HTMLElement)) {
        return;
    }

    const status = document.querySelector(`[data-report-fullscreen-status="${viewerId}"]`);
    const updateButtonLabel = () => {
        const isFullscreen = document.fullscreenElement === viewer;

        button.textContent = isFullscreen
            ? button.getAttribute('data-exit-label')
            : button.getAttribute('data-enter-label');
    };

    button.addEventListener('click', async () => {
        if (status instanceof HTMLElement) {
            status.textContent = '';
        }

        try {
            if (document.fullscreenElement === viewer) {
                await document.exitFullscreen();
            } else if (typeof viewer.requestFullscreen === 'function') {
                await viewer.requestFullscreen();
            } else if (status instanceof HTMLElement) {
                status.textContent = button.getAttribute('data-fullscreen-error') || '';
            }
        } catch {
            if (status instanceof HTMLElement) {
                status.textContent = button.getAttribute('data-fullscreen-error') || '';
            }
        }
    });

    document.addEventListener('fullscreenchange', updateButtonLabel);
});

document.querySelectorAll('[data-report-category-selector]').forEach((selector) => {
    const form = selector.closest('form');
    const annualFields = form?.querySelector('[data-annual-report-fields]');
    const addChapterButton = form?.querySelector('[data-add-annual-chapter]');
    const chapterList = form?.querySelector('[data-annual-chapter-list]');
    const chapterTemplate = form?.querySelector('[data-annual-chapter-template]');
    const categoryFields = [...(form?.querySelectorAll('[data-report-category-fields]') ?? [])];

    if (!(annualFields instanceof HTMLFieldSetElement)) {
        return;
    }

    const updateAnnualFields = () => {
        const isAnnualReport = selector.value === selector.dataset.annualCategoryId;

        annualFields.disabled = !isAnnualReport;
        annualFields.classList.toggle('hidden', !isAnnualReport);

        const selectedCategorySlug = selector.selectedOptions[0]?.dataset.categorySlug;
        categoryFields.forEach((fieldset) => {
            const isSelectedCategory = fieldset.dataset.categorySlug === selectedCategorySlug;

            fieldset.disabled = !isSelectedCategory;
            fieldset.classList.toggle('hidden', !isSelectedCategory);
        });
    };

    selector.addEventListener('change', updateAnnualFields);
    updateAnnualFields();

    chapterList?.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const removeButton = event.target.closest('[data-remove-annual-chapter]');
        if (removeButton) {
            removeButton.closest('[data-annual-chapter-row]')?.remove();
        }
    });

    addChapterButton?.addEventListener('click', () => {
        if (!(chapterList instanceof HTMLElement) || !(chapterTemplate instanceof HTMLTemplateElement)) {
            return;
        }

        const currentCount = chapterList.querySelectorAll('[data-annual-chapter-row]').length;
        if (currentCount >= 20) {
            return;
        }

        const index = chapterList.dataset.nextChapterIndex || String(currentCount);
        chapterList.dataset.nextChapterIndex = String(Number(index) + 1);
        const row = chapterTemplate.content.firstElementChild?.cloneNode(true);
        if (!(row instanceof HTMLElement)) {
            return;
        }

        const chapterKey = row.querySelector('[data-chapter-key]');
        if (chapterKey instanceof HTMLInputElement) {
            chapterKey.name = `annual_chapters[${index}][chapter_key]`;
            chapterKey.value = `custom-${Date.now()}-${index}`;
        }

        row.querySelectorAll('[data-chapter-field]').forEach((field) => {
            if (!(field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement)) {
                return;
            }

            const fieldName = field.dataset.chapterField;
            if (fieldName) {
                field.name = `annual_chapters[${index}][${fieldName}]`;
            }

            if (fieldName === 'display_order') {
                field.value = String(currentCount + 1);
            }
        });

        chapterList.append(row);
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

const initCounterAnimations = () => {
    const counterElements = document.querySelectorAll('[data-counter]');
    if (!counterElements.length) return;

    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const runCounter = (el) => {
        if (el.dataset.counterStarted === 'true') return;
        el.dataset.counterStarted = 'true';

        const target = parseInt(el.getAttribute('data-target') || '0', 10);
        const prefix = el.getAttribute('data-prefix') || '';
        const suffix = el.getAttribute('data-suffix') || '';
        const format = el.getAttribute('data-format') || 'int';

        const formatNumber = (val) => {
            if (format === 'comma') {
                return val.toLocaleString();
            }
            return String(val);
        };

        if (reducedMotion) {
            el.textContent = `${prefix}${formatNumber(target)}${suffix}`;
            return;
        }

        const duration = 2000;
        const startTime = performance.now();

        const tick = (now) => {
            const elapsed = now - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.round(easeOut * target);

            el.textContent = `${prefix}${formatNumber(current)}${suffix}`;

            if (progress < 1) {
                requestAnimationFrame(tick);
            } else {
                el.textContent = `${prefix}${formatNumber(target)}${suffix}`;
            }
        };

        requestAnimationFrame(tick);
    };

    if (!('IntersectionObserver' in window)) {
        counterElements.forEach(runCounter);
        return;
    }

    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                runCounter(entry.target);
                obs.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    counterElements.forEach((el) => observer.observe(el));
};

initCounterAnimations();
