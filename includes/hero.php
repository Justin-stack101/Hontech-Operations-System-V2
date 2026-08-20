<?php
/**
 * Hero Section Component
 * Hontech Auto Center Inc.
 */
?>
<!-- ═══════════════════════════════════════
     HERO
     ═══════════════════════════════════════ -->
<section class="hero" id="hero">
    <div class="hero-bg" data-parallax="0.3">
        <img src="images/hero-bg.png" alt="Hontech Auto Center Workshop" loading="eager">
    </div>
    <div class="hero-dots"></div>

    <!-- Floating Geometric Shapes -->
    <div class="hero-floats">
        <div class="hero-float hero-float--1"></div>
        <div class="hero-float hero-float--2"></div>
        <div class="hero-float hero-float--3"></div>
        <div class="hero-float hero-float--4"></div>
        <div class="hero-float hero-float--5"></div>
        <div class="hero-float hero-float--6"></div>
        <!-- Glow Rings -->
        <div class="glow-ring glow-ring--1"></div>
        <div class="glow-ring glow-ring--2"></div>
        <div class="glow-ring glow-ring--3"></div>
    </div>

    <!-- Particle Canvas -->
    <canvas class="particle-canvas" id="particleCanvas"></canvas>

    <div class="hero-content">
        <div class="hero-text">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Company Profile & Automotive Care
            </div>

            <h1>
                Your Trusted<br>
                <span class="highlight">Auto Center</span><br>
                Partner
            </h1>

            <p class="hero-description">
                HONTECH AUTO CENTER, INC. is one of the newest and fastest-growing automotive companies in Metro
                Manila — delivering high-standard CASA-like quality services that are competitive yet affordable.
            </p>

            <div class="hero-actions">
                <a href="#estimator" class="btn-primary">
                    <i data-lucide="calculator" style="width:16px;height:16px"></i>
                    Instant Cost Estimator
                </a>
                <a href="#services" class="btn-secondary">
                    <i data-lucide="wrench" style="width:16px;height:16px"></i>
                    Explore Services
                </a>
            </div>

            <div class="hero-stats">
                <div>
                    <div class="hero-stat-number"><span class="counter-number" data-target="5" data-suffix="+">0</span><span>+</span></div>
                    <div class="hero-stat-label">Years of Excellence</div>
                </div>
                <div>
                    <div class="hero-stat-number"><span class="counter-number" data-target="16" data-suffix="+">0</span><span>+</span></div>
                    <div class="hero-stat-label">Expert Personnel</div>
                </div>
                <div>
                    <div class="hero-stat-number"><span class="counter-number" data-target="1200" data-suffix="sqm">0</span><span>sqm</span></div>
                    <div class="hero-stat-label">Service Area</div>
                </div>
            </div>
        </div>

        <div class="hero-image-card reveal-right">
            <img src="images/service-image.png" alt="Professional Auto Service">
            <div class="hero-image-overlay">
                <div class="hero-overlay-icon">
                    <i data-lucide="shield-check" style="width:20px;height:20px;color:white"></i>
                </div>
                <div class="hero-overlay-text">
                    <h4>CASA-Like Quality Standards</h4>
                    <p>Certified experts trained in top Philippine automotive dealerships</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Marquee Ticker Strip -->
<div class="marquee-strip">
    <div class="marquee-track">
        <span class="marquee-item"><span class="marquee-dot"></span>CASA-Like Quality</span>
        <span class="marquee-item"><span class="marquee-dot"></span>Certified Experts</span>
        <span class="marquee-item"><span class="marquee-dot"></span>Affordable Pricing</span>
        <span class="marquee-item"><span class="marquee-dot"></span>Trusted Since 2020</span>
        <span class="marquee-item"><span class="marquee-dot"></span>Metro Manila Facility</span>
        <span class="marquee-item"><span class="marquee-dot"></span>Quality Over Profit</span>
        <span class="marquee-item"><span class="marquee-dot"></span>OEM Diagnostics</span>
        <span class="marquee-item"><span class="marquee-dot"></span>Baking Paint Booth</span>
    </div>
</div>
