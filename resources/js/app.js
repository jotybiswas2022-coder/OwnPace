import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

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

// ============================================================
// Announcement ticker — rotates promo messages in the bar above
// the nav. `x-data="promoTicker([...])` → `x-text="item"`.
// ============================================================
Alpine.data('promoTicker', (items = []) => ({
    index: 0,
    get item() {
        return items[this.index] || '';
    },
    init() {
        if (items.length < 2) return;
        if (reduceMotion) return;
        setInterval(() => {
            this.index = (this.index + 1) % items.length;
        }, 4500);
    },
}));

// ============================================================
// Testimonial carousel — fade-through slides with autoplay.
// `x-data="testimonialCarousel(count)"` → `active`, `next()`, `prev()`.
// ============================================================
Alpine.data('testimonialCarousel', (total = 0) => ({
    active: 0,
    get slideCount() {
        return total;
    },
    next() {
        if (total < 2) return;
        this.active = (this.active + 1) % total;
    },
    prev() {
        if (total < 2) return;
        this.active = (this.active - 1 + total) % total;
    },
    init() {
        if (total < 2 || reduceMotion) return;
        setInterval(() => this.next(), 6000);
    },
}));

// ============================================================
// Deals countdown — ticks down to the end of the current week.
// ============================================================
Alpine.data('countdown', () => {
    const end = new Date();
    end.setDate(end.getDate() + ((7 - end.getDay()) % 7));
    end.setHours(23, 59, 59, 999);

    return {
        days: 0,
        hours: 0,
        minutes: 0,
        seconds: 0,
        init() {
            const tick = () => {
                const diff = Math.max(0, end.getTime() - Date.now());
                this.days = Math.floor(diff / 86400000);
                this.hours = Math.floor(diff / 3600000) % 24;
                this.minutes = Math.floor(diff / 60000) % 60;
                this.seconds = Math.floor(diff / 1000) % 60;
            };
            tick();
            if (reduceMotion) return;
            setInterval(tick, 1000);
        },
    };
});

// ============================================================
// Scroll progress bar + back-to-top button.
// ============================================================
const scrollProgressEl = document.getElementById('scroll-progress');
const backToTopEl = document.getElementById('btn-top');

const updateScrollChrome = () => {
    const max = document.documentElement.scrollHeight - window.innerHeight;
    const pct = max > 0 ? (window.scrollY / max) * 100 : 0;
    if (scrollProgressEl) scrollProgressEl.style.transform = `scaleX(${pct / 100})`;
    if (backToTopEl) backToTopEl.classList.toggle('is-visible', window.scrollY > 600);
};

if (scrollProgressEl || backToTopEl) {
    window.addEventListener('scroll', updateScrollChrome, { passive: true });
    updateScrollChrome();
}

if (backToTopEl) {
    backToTopEl.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
    });
}

// ============================================================
// Hero card parallax tilt — subtle 3D lean toward the cursor.
// Applied to `.tilt-js` inside a `.tilt-scene`.
// ============================================================
const heroTilt = document.querySelector('.tilt-js');
if (heroTilt && !reduceMotion) {
    const scene = heroTilt.closest('.tilt-scene') || heroTilt.parentElement;
    let raf = null;

    scene.addEventListener('mousemove', (e) => {
        if (raf) return;
        raf = requestAnimationFrame(() => {
            raf = null;
            const rect = scene.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;
            heroTilt.style.transform =
                `translateY(-6px) rotateX(${(-y * 6).toFixed(2)}deg) rotateY(${(x * 8).toFixed(2)}deg)`;
        });
    });

    scene.addEventListener('mouseleave', () => {
        heroTilt.style.transform = '';
    });
}

// ============================================================
// Quick add-to-cart — POSTs to cart.add via fetch, flashes a
// toast and refreshes the nav badge without a page reload.
// ============================================================
const csrfToken = () =>
    document.querySelector('meta[name="csrf-token"]')?.content || '';

const setCartBadge = (count) => {
    document.querySelectorAll('.js-cart-badge').forEach((badge) => {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : String(count);
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    });
};

const refreshCartBadge = () => {
    fetch('/cart/count', { headers: { Accept: 'application/json' } })
        .then((r) => r.json())
        .then((data) => setCartBadge(data.count || 0))
        .catch(() => {});
};

window.quickAdd = async (productId, btn) => {
    if (!btn) btn = document.createElement('button');
    const original = btn.innerHTML;
    btn.disabled = true;
    btn.classList.add('is-busy');

    try {
        const res = await fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 }),
        });
        if (!res.ok) throw new Error('add failed');
        const data = await res.json().catch(() => null);
        window.flash(data?.message || 'Added to cart ✓', 'success');
        if (data && typeof data.count === 'number') {
            setCartBadge(data.count);
        } else {
            refreshCartBadge();
        }
    } catch (err) {
        window.flash('Could not add to cart', 'error');
    } finally {
        btn.disabled = false;
        btn.classList.remove('is-busy');
        btn.innerHTML = original;
    }
};

// ============================================================
// Wishlist toggle — flips the heart and flashes feedback. Guests
// never reach this (blade renders a login link instead).
// ============================================================
window.toggleWishlist = async (productId, btn) => {
    if (!btn) return;

    try {
        const res = await fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ product_id: productId }),
        });

        // Guests hitting the auth-guarded route get bounced to /login.
        if (res.redirected && res.url.includes('/login')) {
            window.flash('Please log in to save items', 'warning');
            return;
        }
        if (!res.ok) throw new Error('wishlist failed');

        btn.classList.toggle('is-active');
        const icon = btn.querySelector('i');
        if (icon) {
            icon.className = btn.classList.contains('is-active')
                ? 'bi bi-heart-fill'
                : 'bi bi-heart';
        }
        window.flash(
            btn.classList.contains('is-active') ? 'Saved to wishlist ♥' : 'Removed from wishlist',
            'info'
        );
    } catch (err) {
        window.flash('Could not update wishlist — try again', 'error');
    }
};

Alpine.start();
