<?php
/**
 * Single Article Reading Page
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/config/db.php';

$slug = trim($_GET['slug'] ?? '');
$pdo = getDBConnection();
$post = null;
$recentPosts = [];

if ($pdo && !empty($slug)) {
    try {
        // Increment view count
        $pdo->prepare("UPDATE posts SET views_count = views_count + 1 WHERE slug = :slug")->execute([':slug' => $slug]);

        // Fetch post
        $stmt = $pdo->prepare("
            SELECT p.*, c.name as category_name, c.slug as category_slug, e.full_name as author_name, e.avatar_url
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN employees e ON p.author_id = e.id
            WHERE p.slug = :slug AND p.status = 'published'
            LIMIT 1
        ");
        $stmt->execute([':slug' => $slug]);
        $post = $stmt->fetch();

        // Fetch recent posts for sidebar
        $recentStmt = $pdo->prepare("
            SELECT title, slug, featured_image, published_at 
            FROM posts 
            WHERE slug != :slug AND status = 'published' 
            ORDER BY published_at DESC 
            LIMIT 4
        ");
        $recentStmt->execute([':slug' => $slug]);
        $recentPosts = $recentStmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Post view error: " . $e->getMessage());
    }
}

// Fallback demo post if DB is offline or testing
if (!$post) {
    $post = [
        'title' => '5 Telltale Signs Your Car Brakes Need Immediate Inspection',
        'slug' => '5-telltale-signs-car-brakes-need-immediate-inspection',
        'category_name' => 'Maintenance Tips',
        'category_slug' => 'maintenance-tips',
        'author_name' => 'Engr. Justin Honrado',
        'published_at' => date('Y-m-d', strtotime('-5 days')),
        'views_count' => 143,
        'featured_image' => 'images/service-image.png',
        'excerpt' => 'Don\'t wait for a dangerous squeal or spongy pedal. Learn the key warning signs of brake rotor wear and pad deterioration.',
        'content' => '<p>Your vehicle\'s brake system is the single most important safety mechanism protecting you and your passengers on Metro Manila roads. Over time, friction wear degrades brake pads and heat causes rotor warping.</p><h3>1. High-Pitched Squealing or Screeching</h3><p>Most modern brake pads are fitted with an audible acoustic wear indicator—a small metallic tab that makes a sharp squeal when the friction material drops below 3mm.</p><h3>2. Spongy or Soft Brake Pedal</h3><p>If your foot presses down further than usual or feels spongy, moisture or air bubbles may have entered the hydraulic brake lines, or there may be a master cylinder fluid leak.</p><h3>3. Vibration Under Braking</h3><p>A pulsating brake pedal or steering wheel shake during high-speed deceleration usually signals uneven rotor wear or warped brake discs caused by thermal shock.</p><h3>4. Vehicle Pulling to One Side</h3><p>A stuck caliper slide pin or uneven pad wear will pull your car toward one side during braking, requiring immediate shop balancing.</p><h3>Schedule a Complete Brake System Check at Hontech</h3><p>At Hontech Auto Center, our CASA-trained technicians measure rotor thickness with digital calipers, inspect caliper piston boots, and test brake fluid moisture levels to guarantee 100% road safety.</p>'
    ];
}

$pageTitle = $post['title'] . ' — Hontech Auto Center';
$pageDescription = $post['excerpt'];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<div style="background: var(--color-bg); padding: 110px 0 80px;">
    <div class="container" style="max-width: 1100px;">
        
        <!-- Breadcrumbs -->
        <div style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
            <a href="index.php" style="color: var(--color-text-secondary);">Home</a>
            <span>/</span>
            <a href="blog.php" style="color: var(--color-text-secondary);">Blog</a>
            <span>/</span>
            <a href="blog.php?category=<?= urlencode($post['category_slug'] ?? '') ?>" style="color: var(--color-primary); font-weight: 600;">
                <?= htmlspecialchars($post['category_name']) ?>
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;">
            
            <!-- Main Article Column -->
            <article style="background: #ffffff; padding: 40px; border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); border: 1px solid var(--color-border);">
                
                <span style="display: inline-block; background: var(--color-primary-50); color: var(--color-primary); font-size: 0.8rem; font-weight: 700; padding: 4px 12px; border-radius: 4px; text-transform: uppercase; margin-bottom: 14px;">
                    <?= htmlspecialchars($post['category_name']) ?>
                </span>

                <h1 style="font-size: 2.2rem; font-weight: 900; line-height: 1.3; color: var(--color-dark); margin-bottom: 16px;">
                    <?= htmlspecialchars($post['title']) ?>
                </h1>

                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 20px; font-size: 0.85rem; color: var(--color-text-muted); padding-bottom: 24px; border-bottom: 1px solid var(--color-border); margin-bottom: 28px;">
                    <span><i data-lucide="user" style="width:14px;height:14px;display:inline-block;vertical-align:middle;"></i> By <strong><?= htmlspecialchars($post['author_name'] ?? 'Hontech Technical Team') ?></strong></span>
                    <span><i data-lucide="calendar" style="width:14px;height:14px;display:inline-block;vertical-align:middle;"></i> <?= date('F d, Y', strtotime($post['published_at'])) ?></span>
                    <span><i data-lucide="eye" style="width:14px;height:14px;display:inline-block;vertical-align:middle;"></i> <?= number_format($post['views_count'] ?? 0) ?> views</span>
                </div>

                <!-- Featured Image -->
                <div style="border-radius: var(--radius-lg); overflow: hidden; margin-bottom: 32px; max-height: 420px;">
                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                </div>

                <!-- Article Body Content -->
                <div class="article-content" style="font-size: 1.05rem; line-height: 1.8; color: #374151;">
                    <?= $post['content'] ?>
                </div>

                <!-- Author Bio Box -->
                <div style="margin-top: 50px; padding: 24px; background: var(--color-bg-alt); border-radius: var(--radius-lg); display: flex; gap: 16px; align-items: center;">
                    <div style="width: 54px; height: 54px; border-radius: 50%; background: var(--color-primary); color: #ffffff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.2rem; flex-shrink: 0;">
                        <?= strtoupper(substr($post['author_name'] ?? 'H', 0, 1)) ?>
                    </div>
                    <div>
                        <h4 style="font-size: 1rem; font-weight: 700; color: var(--color-dark); margin-bottom: 2px;">Written by <?= htmlspecialchars($post['author_name'] ?? 'Hontech Technical Team') ?></h4>
                        <p style="font-size: 0.85rem; color: var(--color-text-secondary); line-height: 1.4;">
                            Certified automotive specialist at Hontech Auto Center Inc. Dedicated to transparent diagnostics and exceptional car care.
                        </p>
                    </div>
                </div>

                <!-- Call to Action Banner -->
                <div style="margin-top: 36px; background: var(--color-dark); color: #ffffff; padding: 28px; border-radius: var(--radius-lg); text-align: center;">
                    <h3 style="font-size: 1.3rem; font-weight: 800; margin-bottom: 8px;">Need Expert Inspection for Your Vehicle?</h3>
                    <p style="font-size: 0.9rem; color: #9ca3af; margin-bottom: 18px;">
                        Use our instant cost estimator or book a multi-point inspection with our master technicians today.
                    </p>
                    <a href="index.php#estimator" class="btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: var(--radius-md);">
                        <i data-lucide="calculator" style="width:16px;height:16px"></i>
                        Open Service Estimator
                    </a>
                </div>

            </article>

            <!-- Sidebar -->
            <aside style="display: flex; flex-direction: column; gap: 30px;">
                
                <!-- Quick Contact Card -->
                <div style="background: #ffffff; padding: 28px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm);">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--color-dark); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="wrench" style="width:18px;height:18px;color:var(--color-primary)"></i>
                        Hontech Auto Center
                    </h4>
                    <p style="font-size: 0.85rem; color: var(--color-text-secondary); line-height: 1.5; margin-bottom: 16px;">
                        Metro Manila's trusted casa-quality automotive service facility.
                    </p>
                    <div style="font-size: 0.85rem; color: var(--color-text-muted); display: flex; flex-direction: column; gap: 8px;">
                        <div>📍 Metro Manila Facility</div>
                        <div>📞 +63 917 123 4567</div>
                        <div>⏰ Mon - Sat: 8:00 AM - 5:30 PM</div>
                    </div>
                </div>

                <!-- Recent Posts -->
                <div style="background: #ffffff; padding: 28px; border-radius: var(--radius-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm);">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--color-dark); margin-bottom: 16px;">
                        Recent Articles
                    </h4>
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <?php if (!empty($recentPosts)): ?>
                            <?php foreach ($recentPosts as $r): ?>
                                <div style="display: flex; gap: 12px; align-items: center;">
                                    <img src="<?= htmlspecialchars($r['featured_image']) ?>" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; flex-shrink: 0;">
                                    <div>
                                        <a href="blog-post.php?slug=<?= urlencode($r['slug']) ?>" style="font-size: 0.88rem; font-weight: 600; color: var(--color-dark); line-height: 1.3; display: block; margin-bottom: 4px;">
                                            <?= htmlspecialchars($r['title']) ?>
                                        </a>
                                        <span style="font-size: 0.75rem; color: var(--color-text-muted);"><?= date('M d, Y', strtotime($r['published_at'])) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="font-size: 0.85rem; color: var(--color-text-muted);">More articles coming soon.</p>
                        <?php endif; ?>
                    </div>
                </div>

            </aside>

        </div>

    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
