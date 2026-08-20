<?php
/**
 * Navigation Bar Component
 * Hontech Auto Center Inc.
 */
$isHome = basename($_SERVER['PHP_SELF']) === 'index.php';
$navPrefix = $isHome ? '' : 'index.php';
?>
<!-- ═══════════════════════════════════════
     NAVIGATION
     ═══════════════════════════════════════ -->
<nav class="navbar" id="navbar">
    <div class="navbar-inner">
        <a href="<?= $navPrefix ?>#hero" class="navbar-logo">
            <div class="navbar-logo-icon">
                <i data-lucide="wrench" style="width:18px;height:18px;color:white"></i>
            </div>
            <div class="navbar-logo-text">Hon<span>Tech</span></div>
        </a>

        <div class="navbar-links" id="navLinks">
            <a href="<?= $navPrefix ?>#about">About</a>
            <a href="<?= $navPrefix ?>#vision-mission">Vision & Mission</a>
            <a href="<?= $navPrefix ?>#why-hontech">Why Hontech</a>
            <a href="<?= $navPrefix ?>#services">Services</a>
            <a href="<?= $navPrefix ?>#estimator">Estimator</a>
            <a href="blog.php">Blog & Tips</a>
            <a href="<?= $navPrefix ?>#milestones">Milestones</a>
            <a href="<?= $navPrefix ?>#team">Our Team</a>
            <a href="<?= $navPrefix ?>#contact">Contact</a>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="admin/login.php" class="btn-secondary" style="padding: 8px 16px; font-size: 0.85rem; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;">
                <i data-lucide="lock" style="width:14px;height:14px"></i>
                <span>Staff Portal</span>
            </a>
            <a href="<?= $navPrefix ?>#contact" class="navbar-cta">Get In Touch</a>
        </div>

        <button class="navbar-toggle" id="navToggle" aria-label="Toggle navigation">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>
</nav>
