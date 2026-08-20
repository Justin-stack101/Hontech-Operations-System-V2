<?php
/**
 * Global Header Include
 * Hontech Auto Center Inc.
 */
if (!isset($pageTitle)) {
    $pageTitle = 'Hontech Auto Center Inc. — Your Trusted Auto Center Partner';
}
if (!isset($pageDescription)) {
    $pageDescription = 'HONTECH AUTO CENTER, INC. (HACI) is one of the newest and fastest-growing automotive companies based in Metro Manila, providing high-standard, affordable car care services.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
    
    <!-- Open Graph Meta -->
    <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($pageDescription) ?>">
    <meta property="og:type" content="website">
    
    <!-- CSS Stylesheets -->
    <link rel="stylesheet" href="style.css">
    <?php if (isset($extraCSS)): ?>
        <link rel="stylesheet" href="<?= $extraCSS ?>">
    <?php endif; ?>
    <link rel="icon" type="image/png" href="images/favicon.png">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <!-- Scroll Progress Bar -->
    <div class="scroll-progress" id="scrollProgress"></div>
