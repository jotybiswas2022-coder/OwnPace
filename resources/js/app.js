import Alpine from 'alpinejs';

window.Alpine = Alpine;

// ============================================================
// Scroll reveal — `x-reveal="delayMs"` on any element.
// Adds `.is-revealed` once the element enters the viewport.
// Instantly visible when the user prefers reduced motion.
// ============================================================
const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

Alpine.directive('reveal', (el, { expression }) => {
    if (reduceMotion) {
        el.classList.add('is-revealed');
        return;
    }

    el.classList.add('os-reveal');
    const delay = parseFloat(expression) || 0;
    if (delay) el.style.transitionDelay = `${delay}ms`;

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    el.classList.add('is-revealed');
                    io.unobserve(el);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    io.observe(el);
});

// ============================================================
// Count-up — `x-countup="5000"` animates a number into view.
// Sets the final value instantly under prefers-reduced-motion.
// ============================================================
Alpine.directive('countup', (el, { expression }) => {
    const target = parseInt(expression) || 0;

    const finish = () => { el.textContent = target.toLocaleString(); };

    if (reduceMotion) { finish(); return; }

    const io = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                io.unobserve(el);
                const duration = 1200;
                const start = performance.now();
                const tick = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target).toLocaleString();
                    if (progress < 1) requestAnimationFrame(tick);
                };
                requestAnimationFrame(tick);
            });
        },
        { threshold: 0.3 }
    );

    io.observe(el);
});

// ============================================================
// Toast host — used by frontend/partials/toasts.blade.php.
// Exposes `window.flash(message, type)` for JS-driven notices.
// ============================================================
Alpine.data('toastHost', (seeded = []) => ({
    toasts: [],

    init() {
        seeded.forEach((t, i) => {
            setTimeout(() => this.push(t.type, t.message), 250 + i * 150);
        });

        window.addEventListener('flash', (e) => {
            const detail = e.detail || {};
            this.push(detail.type || 'info', detail.message || '');
        });
    },

    push(type, message) {
        if (!message) return;
        const toast = { id: Date.now() + Math.random(), type, message, visible: false };
        this.toasts.push(toast);
        this.$nextTick(() => { toast.visible = true; });
        setTimeout(() => this.dismiss(toast.id), 4500);
    },

    dismiss(id) {
        const toast = this.toasts.find((t) => t.id === id);
        if (!toast) return;
        toast.visible = false;
        setTimeout(() => {
            this.toasts = this.toasts.filter((t) => t.id !== id);
        }, 250);
    },
}));

window.flash = (message, type = 'info') =>
    window.dispatchEvent(new CustomEvent('flash', { detail: { message, type } }));

Alpine.start();
