@extends('frontend.app')
@section('title', 'Feature Demo — OwnPace Redesign Showcase')

@section('content')

<style>
/* ===== DEMO PAGE STYLES ===== */
.demo-header {
    text-align: center;
    padding: 60px 0 40px;
    position: relative;
}
.demo-header h1 {
    font-family: 'Syne', sans-serif;
    font-size: clamp(32px, 5vw, 52px);
    font-weight: 900;
    color: #f4f4f5;
    margin-bottom: 12px;
    line-height: 1.15;
}
.demo-header h1 span {
    background: linear-gradient(135deg, #fbbf24, #eab308, #ca8a04);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}
.demo-header p {
    color: #a1a1aa;
    font-size: 16px;
    max-width: 560px;
    margin: 0 auto 20px;
}
.demo-header .demo-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(234,179,8,0.1);
    border: 1px solid rgba(234,179,8,0.12);
    color: #fbbf24;
    padding: 5px 16px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}
.demo-version {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    padding: 8px 20px;
    border-radius: 10px;
    color: #71717a;
    font-size: 13px;
    font-weight: 500;
}
.demo-version i { color: #22c55e; font-size: 14px; }

/* ===== SECTION COMMON ===== */
.demo-section {
    padding: 50px 0;
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.demo-section:last-child { border-bottom: none; }
.demo-section-alt { background: rgba(13,13,17,0.5); }
.demo-section-header {
    margin-bottom: 30px;
}
.demo-section-tag {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(234,179,8,0.08);
    border: 1px solid rgba(234,179,8,0.12);
    color: #fbbf24;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    margin-bottom: 10px;
}
.demo-section-header h2 {
    font-family: 'Syne', sans-serif;
    font-size: clamp(22px, 3vw, 30px);
    font-weight: 800;
    color: #f4f4f5;
    margin-bottom: 6px;
}
.demo-section-header p {
    color: #a1a1aa;
    font-size: 14px;
    max-width: 500px;
}

/* ===== FEATURE CARDS ===== */
.demo-grid {
    display: grid;
    gap: 16px;
}
.demo-grid-2 { grid-template-columns: repeat(2, 1fr); }
.demo-grid-3 { grid-template-columns: repeat(3, 1fr); }
.demo-grid-4 { grid-template-columns: repeat(4, 1fr); }

.demo-card {
    background: #16161d;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 14px;
    padding: 24px 20px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}
.demo-card:hover {
    border-color: rgba(234,179,8,0.12);
    transform: translateY(-3px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.2);
}
.demo-card-accent {
    border-color: rgba(234,179,8,0.08);
}
.demo-card-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: rgba(234,179,8,0.08);
    display: flex; align-items: center; justify-content: center;
    color: #eab308; font-size: 20px;
    margin-bottom: 14px;
    transition: all 0.3s;
}
.demo-card:hover .demo-card-icon {
    background: #eab308;
    color: #0A0A0B;
}
.demo-card h3 {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
    color: #f4f4f5;
    margin-bottom: 6px;
}
.demo-card p {
    color: #a1a1aa;
    font-size: 13px;
    line-height: 1.6;
    margin: 0;
}
.demo-card .demo-tag {
    display: inline-block;
    background: rgba(234,179,8,0.08);
    color: #fbbf24;
    padding: 2px 10px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 700;
    margin-top: 10px;
}

/* ===== LIVE DEMO CELLS ===== */
.demo-live {
    background: #0d0d11;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    overflow: hidden;
}
.demo-live-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 18px;
    background: rgba(255,255,255,0.02);
    border-bottom: 1px solid rgba(255,255,255,0.04);
}
.demo-live-header span {
    display: flex; align-items: center; gap: 8px;
    color: #71717a;
    font-size: 12px;
    font-weight: 600;
}
.demo-live-header i { color: #22c55e; font-size: 8px; }
.demo-live-body {
    padding: 24px;
    min-height: 100px;
}
.demo-live-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #52525b;
    margin-bottom: 8px;
}

/* ===== BOTTOM NAV PREVIEW ===== */
.demo-bn-preview {
    max-width: 360px;
    margin: 0 auto;
    background: rgba(18,18,20,0.95);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 8px 6px;
    display: flex;
    align-items: center;
    justify-content: space-around;
    box-shadow: 0 8px 40px rgba(0,0,0,0.4);
}
.demo-bn-item {
    display: flex; flex-direction: column;
    align-items: center; gap: 2px;
    padding: 6px 10px;
    color: #71717a;
    font-size: 10px; font-weight: 600;
    transition: all 0.25s;
    position: relative;
    cursor: pointer;
    min-width: 48px;
}
.demo-bn-item i { font-size: 18px; transition: all 0.3s; }
.demo-bn-item:hover { color: #a1a1aa; }
.demo-bn-item.active {
    color: #eab308 !important;
}
.demo-bn-item.active i { transform: translateY(-2px); }
.demo-bn-item.active::after {
    content: '';
    position: absolute; top: -1px; left: 50%;
    transform: translateX(-50%);
    width: 20px; height: 3px;
    background: #eab308;
    border-radius: 0 0 3px 3px;
}
.demo-bn-badge {
    position: absolute; top: -2px; right: 2px;
    background: #eab308; color: #0A0A0B;
    font-size: 8px; font-weight: 800;
    min-width: 16px; height: 16px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
}

/* ===== TRUST BAR DEMO ===== */
.demo-trust-scroll {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    scrollbar-width: none;
    padding: 4px 0;
}
.demo-trust-scroll::-webkit-scrollbar { display: none; }
.demo-trust-pill {
    flex-shrink: 0;
    display: flex; align-items: center; gap: 8px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.04);
    border-radius: 10px;
    padding: 8px 14px;
    color: #a1a1aa;
    font-size: 12px;
    font-weight: 500;
    white-space: nowrap;
}
.demo-trust-pill i { color: #eab308; font-size: 14px; }

/* ===== MARQUEE DEMO ===== */
.demo-marquee {
    overflow: hidden;
    mask-image: linear-gradient(to right, transparent, black 5%, black 95%, transparent);
}
.demo-marquee-track {
    display: flex; align-items: center;
    animation: demoMarquee 15s linear infinite;
    width: max-content;
}
.demo-marquee-item {
    display: inline-flex; align-items: center; gap: 6px;
    color: #a1a1aa; font-size: 13px; font-weight: 500;
    white-space: nowrap;
}
.demo-marquee-item i { color: #eab308; font-size: 14px; }
.demo-marquee-dot {
    color: #eab308; margin: 0 20px;
    font-size: 7px; opacity: 0.3;
}
@keyframes demoMarquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ===== STATS DEMO ===== */
.demo-stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}
.demo-stat-card {
    text-align: center;
    padding: 20px 12px;
    background: rgba(0,0,0,0.15);
    border: 1px solid rgba(255,255,255,0.04);
    border-radius: 10px;
    transition: all 0.25s;
}
.demo-stat-card:hover {
    border-color: rgba(234,179,8,0.1);
    background: rgba(0,0,0,0.2);
}
.demo-stat-card i {
    font-size: 22px;
    color: rgba(234,179,8,0.15);
    display: block;
    margin-bottom: 6px;
}
.demo-stat-card:hover i { color: #eab308; }
.demo-stat-num {
    font-family: 'Syne', sans-serif;
    font-size: 22px; font-weight: 800;
    background: linear-gradient(135deg, #fbbf24, #eab308);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
}
.demo-stat-card span {
    display: block;
    font-size: 11px;
    color: rgba(255,255,255,0.4);
    font-weight: 500;
    margin-top: 2px;
}

/* ===== SCROLL INDICATOR DEMO ===== */
.demo-scroll-indicator {
    display: flex; flex-direction: column;
    align-items: center; gap: 8px;
    padding: 20px 0;
}
.demo-scroll-indicator .scroll-text {
    font-size: 10px; font-weight: 600;
    text-transform: uppercase; letter-spacing: 2px;
    color: #71717a;
}
.demo-scroll-indicator .scroll-line {
    width: 1px; height: 32px;
    background: linear-gradient(to bottom, #eab308, transparent);
    animation: demoScrollBounce 2s ease-in-out infinite;
}
@keyframes demoScrollBounce {
    0%, 100% { transform: scaleY(1); opacity: 1; }
    50% { transform: scaleY(0.6); opacity: 0.3; }
}

/* ===== FLOATING SHAPES DEMO ===== */
.demo-floats {
    position: relative;
    height: 120px;
    display: flex; align-items: center; justify-content: center;
}
.demo-float-shape {
    width: 44px; height: 44px;
    border-radius: 12px;
    background: rgba(234,179,8,0.06);
    border: 1px solid rgba(234,179,8,0.1);
    color: rgba(234,179,8,0.3);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    position: absolute;
    animation: demoFloat 6s ease-in-out infinite;
}
.demo-float-shape:nth-child(1) { top: 10px; left: 20%; animation-delay: 0s; }
.demo-float-shape:nth-child(2) { bottom: 10px; left: 50%; animation-delay: 2s; }
.demo-float-shape:nth-child(3) { top: 20px; right: 20%; animation-delay: 4s; }
@keyframes demoFloat {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    33% { transform: translateY(-12px) rotate(3deg); }
    66% { transform: translateY(6px) rotate(-2deg); }
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .demo-grid-2,
    .demo-grid-3,
    .demo-grid-4 { grid-template-columns: 1fr; }
    .demo-stats-grid { grid-template-columns: repeat(2, 1fr); }
    .demo-section { padding: 35px 0; }
    .demo-live-body { padding: 16px; }
}

/* ===== HERO FEATURE PREVIEW ===== */
.demo-hero-features {
    display: flex; flex-direction: column; gap: 16px;
}
.demo-hf-row {
    display: flex; align-items: center; gap: 16px;
    background: rgba(255,255,255,0.02);
    border: 1px solid rgba(255,255,255,0.04);
    border-radius: 12px;
    padding: 14px 18px;
    transition: all 0.25s;
}
.demo-hf-row:hover {
    background: rgba(234,179,8,0.03);
    border-color: rgba(234,179,8,0.08);
}
.demo-hf-icon {
    width: 36px; height: 36px;
    border-radius: 10px;
    background: rgba(234,179,8,0.08);
    display: flex; align-items: center; justify-content: center;
    color: #eab308; font-size: 15px;
    flex-shrink: 0;
}
.demo-hf-info { flex: 1; }
.demo-hf-info h4 {
    font-size: 14px; font-weight: 700;
    color: #f4f4f5; margin-bottom: 2px;
}
.demo-hf-info p {
    font-size: 12px; color: #71717a; margin: 0;
}
.demo-hf-badge {
    font-size: 10px; font-weight: 700;
    padding: 3px 10px;
    border-radius: 6px;
    background: rgba(34,197,94,0.1);
    color: #4ade80;
    white-space: nowrap;
}
</style>

<!-- ===== DEMO HEADER ===== -->
<section class="demo-header">
    <div class="container">
        <div class="demo-badge">
            <i class="bi bi-stars"></i> Feature Showcase
        </div>
        <h1>OwnPace <span>Redesign</span> Demo</h1>
        <p>An interactive showcase of all the frontend improvements — mobile hero, desktop enhancements, bottom navigation, and more.</p>
        <div class="demo-version">
            <i class="bi bi-check-circle-fill"></i>
            <span>v2.0 — Built with Laravel + Bootstrap 5.3</span>
        </div>
    </div>
</section>

<!-- ===== 1. MOBILE HERO REDESIGN ===== -->
<section class="demo-section demo-section-alt">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-phone-fill"></i> Mobile First</span>
            <h2>📱 Mobile Hero Redesign</h2>
            <p>Complete reimagining of the mobile hero section with glassmorphism card, full-width slider, and staggered animations.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="demo-live">
                    <div class="demo-live-header">
                        <span><i class="bi bi-circle-fill"></i> Live Preview (Mobile)</span>
                        <span style="font-size:11px;">≤576px viewport</span>
                    </div>
                    <div class="demo-live-body text-center">
                        <div style="max-width:320px;margin:0 auto;background:linear-gradient(180deg,#0d0d11 0%,#16161d 100%);border-radius:16px;overflow:hidden;border:1px solid rgba(255,255,255,0.06);">
                            <!-- Simulated mobile hero -->
                            <div style="height:160px;background:linear-gradient(135deg,#1a1a2e,#0d0d11);display:flex;align-items:center;justify-content:center;position:relative;">
                                <i class="bi bi-images" style="font-size:32px;color:rgba(255,255,255,0.1);"></i>
                                <div style="position:absolute;bottom:10px;left:50%;transform:translateX(-50%);display:flex;gap:5px;">
                                    <span style="width:18px;height:5px;border-radius:3px;background:#eab308;"></span>
                                    <span style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.2);"></span>
                                    <span style="width:5px;height:5px;border-radius:50%;background:rgba(255,255,255,0.2);"></span>
                                </div>
                                <!-- Gradient overlay -->
                                <div style="position:absolute;inset:0;background:linear-gradient(180deg,transparent 0%,rgba(13,13,17,0.9) 50%,rgba(13,13,17,1) 80%);"></div>
                            </div>
                            <!-- Card content -->
                            <div style="background:linear-gradient(180deg,rgba(22,22,29,0.98),#16161d);padding:16px 14px 14px;margin-top:-20px;position:relative;z-index:2;border-radius:16px 16px 0 0;box-shadow:0 -10px 30px rgba(0,0,0,0.5);">
                                <div style="display:inline-flex;align-items:center;gap:4px;background:rgba(234,179,8,0.1);border:1px solid rgba(234,179,8,0.15);color:#fbbf24;padding:4px 10px;border-radius:20px;font-size:9px;font-weight:600;margin-bottom:10px;">
                                    <i class="bi bi-shield-fill-check" style="font-size:9px;"></i> 100% Secure
                                </div>
                                <div style="font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:#f4f4f5;line-height:1.15;margin-bottom:6px;">
                                    Shop What You Love,<br>
                                    <span style="background:linear-gradient(135deg,#fbbf24,#eab308,#ca8a04);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Pay in Easy Pieces</span>
                                </div>
                                <div style="display:flex;gap:8px;margin-top:12px;">
                                    <span style="flex:1;text-align:center;padding:10px;background:linear-gradient(135deg,#eab308,#ca8a04);color:#0A0A0B;border-radius:10px;font-size:11px;font-weight:700;">Start Shopping</span>
                                    <span style="flex:1;text-align:center;padding:10px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:#f4f4f5;border-radius:10px;font-size:11px;font-weight:700;">Create Account</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="demo-hero-features">
                    <div class="demo-hf-row">
                        <div class="demo-hf-icon"><i class="bi bi-layers"></i></div>
                        <div class="demo-hf-info">
                            <h4>Glassmorphism Card</h4>
                            <p>Content overlaps the slider with gradient backdrop</p>
                        </div>
                        <span class="demo-hf-badge">Live</span>
                    </div>
                    <div class="demo-hf-row">
                        <div class="demo-hf-icon"><i class="bi bi-arrows-expand"></i></div>
                        <div class="demo-hf-info">
                            <h4>Full-Width Slider</h4>
                            <p>Slider becomes a full-bleed hero banner on mobile</p>
                        </div>
                        <span class="demo-hf-badge">Live</span>
                    </div>
                    <div class="demo-hf-row">
                        <div class="demo-hf-icon"><i class="bi bi-emoji-neutral"></i></div>
                        <div class="demo-hf-info">
                            <h4>Staggered Entrance</h4>
                            <p>Each element fades in with a 0.08s delay cascade</p>
                        </div>
                        <span class="demo-hf-badge">Live</span>
                    </div>
                    <div class="demo-hf-row">
                        <div class="demo-hf-icon"><i class="bi bi-dot"></i></div>
                        <div class="demo-hf-info">
                            <h4>Custom Dot Indicators</h4>
                            <p>Animated progress dots replace default controls</p>
                        </div>
                        <span class="demo-hf-badge">Live</span>
                    </div>
                    <div class="demo-hf-row">
                        <div class="demo-hf-icon"><i class="bi bi-phone"></i></div>
                        <div class="demo-hf-info">
                            <h4>Touch-Optimized Buttons</h4>
                            <p>Side-by-side equal-width buttons for thumb reach</p>
                        </div>
                        <span class="demo-hf-badge">Live</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== 2. DESKTOP HERO ENHANCEMENTS ===== -->
<section class="demo-section">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-display"></i> Desktop</span>
            <h2>🖥️ Desktop Hero Enhancements</h2>
            <p>Floating decorative elements, custom carousel controls, slide counter, scroll indicator, and micro-interactions.</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="demo-card text-center">
                    <div class="demo-card-icon" style="margin:0 auto 14px;"><i class="bi bi-shield-fill-check"></i></div>
                    <h3>Floating Shapes</h3>
                    <p>Three decorative icons (shield, coin, lightning) float slowly with a 6s animation cycle.</p>
                    <span class="demo-tag">CSS Animation</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="demo-card text-center">
                    <div class="demo-card-icon" style="margin:0 auto 14px;"><i class="bi bi-chevron-left"></i></div>
                    <h3>Custom Carousel Controls</h3>
                    <p>Glassmorphism circular buttons appear on hover with smooth golden glow transition.</p>
                    <span class="demo-tag">Hover Reveal</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="demo-card text-center">
                    <div class="demo-card-icon" style="margin:0 auto 14px;"><i class="bi bi-123"></i></div>
                    <h3>Slide Counter</h3>
                    <p>\"01 ═ 03\" style counter with animated progress bar synced to 5s carousel interval.</p>
                    <span class="demo-tag">Live Counter</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="demo-card text-center">
                    <div class="demo-card-icon" style="margin:0 auto 14px;"><i class="bi bi-check-circle-fill"></i></div>
                    <h3>Feature Pill</h3>
                    <p>Golden \"No credit check • Instant approval\" pill pulses below the slider.</p>
                    <span class="demo-tag">Pulse Animation</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="demo-card text-center">
                    <div class="demo-card-icon" style="margin:0 auto 14px;"><i class="bi bi-mouse"></i></div>
                    <h3>Scroll Indicator</h3>
                    <p>Animated \"SCROLL\" text with gradient line and bounce effect at the hero bottom.</p>
                    <span class="demo-tag">Bounce Animation</span>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="demo-card text-center">
                    <div class="demo-card-icon" style="margin:0 auto 14px;"><i class="bi bi-sun"></i></div>
                    <h3>Shimmer Buttons</h3>
                    <p>Gold buttons have a shimmer overlay that sweeps across on hover with enhanced glow.</p>
                    <span class="demo-tag">Hover Effect</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== 3. MOBILE BOTTOM NAV ===== -->
<section class="demo-section demo-section-alt">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-layout-three-columns"></i> Navigation</span>
            <h2>📱 Mobile Bottom Navigation</h2>
            <p>Fixed bottom nav with 5 items, active indicator, cart badge, and smart scroll hide/show.</p>
        </div>

        <div class="row g-4 align-items-center">
            <div class="col-lg-5">
                <div class="demo-live">
                    <div class="demo-live-header">
                        <span><i class="bi bi-circle-fill"></i> Live Preview</span>
                        <span style="font-size:11px;">320px mockup</span>
                    </div>
                    <div class="demo-live-body">
                        <div class="demo-bn-preview">
                            <div class="demo-bn-item active">
                                <i class="bi bi-house-fill"></i>
                                <span>Home</span>
                            </div>
                            <div class="demo-bn-item">
                                <i class="bi bi-grid-fill"></i>
                                <span>Shop</span>
                            </div>
                            <div class="demo-bn-item">
                                <div style="position:relative;">
                                    <i class="bi bi-cart-fill"></i>
                                    <span class="demo-bn-badge">3</span>
                                </div>
                                <span>Cart</span>
                            </div>
                            <div class="demo-bn-item">
                                <i class="bi bi-heart-fill"></i>
                                <span>Wishlist</span>
                            </div>
                            <div class="demo-bn-item">
                                <div style="width:22px;height:22px;border-radius:6px;background:linear-gradient(135deg,#eab308,#ca8a04);display:flex;align-items:center;justify-content:center;color:#0A0A0B;font-size:10px;font-weight:800;">J</div>
                                <span>Profile</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="demo-card">
                            <div class="demo-card-icon"><i class="bi bi-dot"></i></div>
                            <h3>Active Indicator</h3>
                            <p>Golden dot at the top with the active icon lifting slightly upward.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="demo-card">
                            <div class="demo-card-icon"><i class="bi bi-bag-fill"></i></div>
                            <h3>Cart Badge</h3>
                            <p>Dynamic count badge on cart icon, supports 99+ overflow.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="demo-card">
                            <div class="demo-card-icon"><i class="bi bi-eye-slash"></i></div>
                            <h3>Scroll Hide/Show</h3>
                            <p>Direction-based visibility — hides on scroll down, reappears on scroll up.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="demo-card">
                            <div class="demo-card-icon"><i class="bi bi-shield"></i></div>
                            <h3>Safe Area Support</h3>
                            <p>Uses <code>env(safe-area-inset-bottom)</code> for notched devices.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== 4. TRUST BAR MOBILE ===== -->
<section class="demo-section">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-shield-check"></i> Trust</span>
            <h2>🛍️ Mobile Trust Bar</h2>
            <p>Horizontally scrollable pill cards replace the old stacked column layout for better touch interaction.</p>
        </div>

        <div class="demo-live">
            <div class="demo-live-header">
                <span><i class="bi bi-circle-fill"></i> Scrollable Preview</span>
                <span style="font-size:11px;">← Swipe →</span>
            </div>
            <div class="demo-live-body">
                <div class="demo-trust-scroll">
                    <div class="demo-trust-pill"><i class="bi bi-truck"></i> Free delivery over ₦50,000</div>
                    <div class="demo-trust-pill"><i class="bi bi-arrow-repeat"></i> 30-day easy exchange</div>
                    <div class="demo-trust-pill"><i class="bi bi-shield-check"></i> 256-bit SSL secure</div>
                    <div class="demo-trust-pill"><i class="bi bi-headset"></i> 24/7 customer support</div>
                    <div class="demo-trust-pill"><i class="bi bi-coin"></i> 0% interest plans</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== 5. MARQUEE ===== -->
<section class="demo-section demo-section-alt">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-arrow-left-right"></i> Marquee</span>
            <h2>🔄 Animated Marquee</h2>
            <p>Continuous scrolling trust badges with faster mobile animation speed (18s vs 30s desktop).</p>
        </div>

        <div class="demo-live">
            <div class="demo-live-header">
                <span><i class="bi bi-circle-fill"></i> Auto-scrolling</span>
            </div>
            <div class="demo-live-body" style="padding:16px 0;">
                <div class="demo-marquee">
                    <div class="demo-marquee-track" style="animation-duration:12s;">
                        <span class="demo-marquee-item"><i class="bi bi-shield-fill-check"></i> 100% Secure</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-coin"></i> 0% Interest</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-truck"></i> Free Delivery</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-arrow-repeat"></i> Easy Exchange</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-headset"></i> 24/7 Support</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-clock"></i> Instant Approval</span>
                        <span class="demo-marquee-dot">✦</span>
                        <!-- Duplicate for seamless loop -->
                        <span class="demo-marquee-item"><i class="bi bi-shield-fill-check"></i> 100% Secure</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-coin"></i> 0% Interest</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-truck"></i> Free Delivery</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-arrow-repeat"></i> Easy Exchange</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-headset"></i> 24/7 Support</span>
                        <span class="demo-marquee-dot">✦</span>
                        <span class="demo-marquee-item"><i class="bi bi-clock"></i> Instant Approval</span>
                        <span class="demo-marquee-dot">✦</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== 6. STATS ===== -->
<section class="demo-section">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-bar-chart-fill"></i> Analytics</span>
            <h2>📊 Compact Stats Grid</h2>
            <p>Responsive 4-column grid that collapses to 2 columns on mobile with compact card design.</p>
        </div>

        <div class="demo-stats-grid">
            <div class="demo-stat-card">
                <i class="bi bi-box-seam-fill"></i>
                <div class="demo-stat-num">5,000+</div>
                <span>Products Available</span>
            </div>
            <div class="demo-stat-card">
                <i class="bi bi-emoji-smile-fill"></i>
                <div class="demo-stat-num">15,000+</div>
                <span>Happy Customers</span>
            </div>
            <div class="demo-stat-card">
                <i class="bi bi-coin"></i>
                <div class="demo-stat-num">36</div>
                <span>Payment Plans</span>
            </div>
            <div class="demo-stat-card">
                <i class="bi bi-building"></i>
                <div class="demo-stat-num">100+</div>
                <span>Trusted Brands</span>
            </div>
        </div>
    </div>
</section>

<!-- ===== 7. ANIMATIONS SHOWCASE ===== -->
<section class="demo-section demo-section-alt">
    <div class="container">
        <div class="demo-section-header">
            <span class="demo-section-tag"><i class="bi bi-magic"></i> Animations</span>
            <h2>🎬 Micro-Interactions</h2>
            <p>All the subtle animations powering the redesign experience.</p>
        </div>

        <div class="row g-3">
            <div class="col-md-3 col-6">
                <div class="demo-card text-center">
                    <div class="demo-float-shape" style="position:relative;margin:0 auto 14px;animation-delay:0s;">
                        <i class="bi bi-shield-fill-check"></i>
                    </div>
                    <h3>Floating</h3>
                    <p style="font-size:12px;">6s ease-in-out</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="demo-card text-center">
                    <div class="demo-scroll-indicator" style="padding:0 0 16px;">
                        <div class="scroll-line" style="margin:0 auto;"></div>
                    </div>
                    <h3>Bounce</h3>
                    <p style="font-size:12px;">2s ease-in-out</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="demo-card text-center">
                    <div style="width:44px;height:44px;border-radius:12px;margin:0 auto 14px;background:linear-gradient(135deg,#eab308,#ca8a04);display:flex;align-items:center;justify-content:center;color:#0A0A0B;font-size:18px;animation:demoFloat 3s ease-in-out infinite;">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h3>Pulse</h3>
                    <p style="font-size:12px;">3s ease-in-out</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="demo-card text-center">
                    <div style="width:44px;height:44px;border-radius:12px;margin:0 auto 14px;background:rgba(234,179,8,0.08);display:flex;align-items:center;justify-content:center;color:#eab308;font-size:18px;animation:demoPillProgress 5s linear infinite;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h3>Progress Bar</h3>
                    <p style="font-size:12px;">5s linear</p>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes demoPillProgress {
    0% { transform: scaleX(1); }
    50% { transform: scaleX(0.6); }
    100% { transform: scaleX(1); }
}
</style>

<!-- ===== FOOTER ===== -->
<section style="padding:40px 0;text-align:center;border-top:1px solid rgba(255,255,255,0.04);">
    <div class="container">
        <p style="color:#71717a;font-size:13px;max-width:500px;margin:0 auto;line-height:1.7;">
            These features are <strong style="color:#f4f4f5;">live on the OwnPace homepage</strong>. 
            View them in action by visiting the main site and resizing your browser or using DevTools mobile mode.
        </p>
        <div style="margin-top:20px;display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ url('/') }}" class="btn-primary-gold" style="padding:12px 24px;font-size:13px;">
                <i class="bi bi-house-fill"></i> View Live Site
            </a>
            <a href="{{ url('/shop') }}" class="btn-outline-gold" style="padding:12px 24px;font-size:13px;">
                <i class="bi bi-grid-fill"></i> Browse Products
            </a>
        </div>
    </div>
</section>

@endsection
