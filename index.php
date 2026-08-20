<?php
/**
 * Master Landing Page Entry Point
 * Hontech Auto Center Inc. (PHP + MySQL on XAMPP)
 */

$pageTitle = 'Hontech Auto Center Inc. — Your Trusted Auto Center Partner';
$pageDescription = 'HONTECH AUTO CENTER, INC. (HACI) provides high-standard, CASA-like automotive care with transparent and competitive pricing in Metro Manila.';

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
require_once __DIR__ . '/includes/hero.php';
require_once __DIR__ . '/includes/about.php';
require_once __DIR__ . '/includes/vision_mission.php';
require_once __DIR__ . '/includes/services.php';
require_once __DIR__ . '/includes/estimator.php';
require_once __DIR__ . '/includes/blog_preview.php';
require_once __DIR__ . '/includes/milestones.php';
require_once __DIR__ . '/includes/team.php';
require_once __DIR__ . '/includes/faq.php';
require_once __DIR__ . '/includes/contact.php';
require_once __DIR__ . '/includes/footer.php';
