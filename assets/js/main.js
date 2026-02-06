/**
 * DADE Learn - Main JavaScript
 * Core functionality and interactivity
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize all modules
    initMobileMenu();
    initUserDropdown();
    initStickyHeader();
    initBackToTop();
    initAnimations();
    initLazyLoading();
});

/**
 * Mobile Menu Toggle
 */
function initMobileMenu() {
    const toggle = document.getElementById('mobileToggle');
    const menu = document.getElementById('mobileMenu');
    const overlay = document.getElementById('menuOverlay');
    const close = document.getElementById('mobileClose');

    if (!toggle || !menu) return;

    const openMenu = () => {
        menu.classList.add('active');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };

    const closeMenu = () => {
        menu.classList.remove('active');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };

    toggle.addEventListener('click', openMenu);
    close?.addEventListener('click', closeMenu);
    overlay?.addEventListener('click', closeMenu);

    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && menu.classList.contains('active')) {
            closeMenu();
        }
    });
}

/**
 * User Dropdown Toggle
 */
function initUserDropdown() {
    const dropdown = document.querySelector('.user-dropdown');
    const toggle = document.getElementById('userDropdown');

    if (!dropdown || !toggle) return;

    toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdown.classList.toggle('active');
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
            dropdown.classList.remove('active');
        }
    });
}

/**
 * Sticky Header with Shadow
 */
function initStickyHeader() {
    const header = document.getElementById('header');
    if (!header) return;

    let lastScroll = 0;

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;

        if (currentScroll > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }

        lastScroll = currentScroll;
    });
}

/**
 * Back to Top Button
 */
function initBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;

    window.addEventListener('scroll', () => {
        if (window.pageYOffset > 500) {
            btn.classList.add('visible');
        } else {
            btn.classList.remove('visible');
        }
    });

    btn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Scroll Animations (Intersection Observer)
 */
function initAnimations() {
    const animatedElements = document.querySelectorAll('[data-animate]');

    if (!animatedElements.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const animation = el.dataset.animate || 'fadeInUp';
                const delay = el.dataset.delay || 0;

                setTimeout(() => {
                    el.classList.add('animate-' + animation);
                    el.style.opacity = '1';
                }, delay);

                observer.unobserve(el);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });

    animatedElements.forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });
}

/**
 * Lazy Loading for Images
 */
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');

    if (!lazyImages.length) return;

    const imageObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                img.classList.add('loaded');
                imageObserver.unobserve(img);
            }
        });
    });

    lazyImages.forEach(img => imageObserver.observe(img));
}

/**
 * Animated Counter
 */
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);

    const updateCounter = () => {
        start += increment;
        if (start < target) {
            element.textContent = Math.floor(start).toLocaleString();
            requestAnimationFrame(updateCounter);
        } else {
            element.textContent = target.toLocaleString();
        }
    };

    updateCounter();
}

/**
 * Initialize Counters on Scroll
 */
function initCounters() {
    const counters = document.querySelectorAll('[data-counter]');

    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.dataset.counter);
                animateCounter(counter, target);
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
}

/**
 * Form Validation
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('[required]');
    let isValid = true;

    inputs.forEach(input => {
        removeError(input);

        if (!input.value.trim()) {
            showError(input, 'This field is required');
            isValid = false;
        } else if (input.type === 'email' && !isValidEmail(input.value)) {
            showError(input, 'Please enter a valid email address');
            isValid = false;
        }
    });

    return isValid;
}

function showError(input, message) {
    input.classList.add('error');
    const errorDiv = document.createElement('span');
    errorDiv.className = 'form-error';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
}

function removeError(input) {
    input.classList.remove('error');
    const error = input.parentNode.querySelector('.form-error');
    if (error) error.remove();
}

function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

/**
 * AJAX Request Helper
 */
async function fetchJSON(url, options = {}) {
    const defaults = {
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    const response = await fetch(url, { ...defaults, ...options });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

    return response.json();
}

/**
 * Toast Notifications
 */
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer') || createToastContainer();

    const toast = document.createElement('div');
    toast.className = `toast alert-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} alert-icon"></i>
        <div class="alert-content">${message}</div>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse forwards';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container';
    document.body.appendChild(container);
    return container;
}

/**
 * Debounce Function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Utility: Format Number
 */
function formatNumber(num) {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'K';
    }
    return num.toString();
}
