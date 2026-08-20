<?php
/**
 * Blog Preview Section Component (Homepage)
 * Hontech Auto Center Inc.
 */
require_once __DIR__ . '/../config/db.php';

$pdo = getDBConnection();
$posts = [];

if ($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT p.*, c.name as category_name, c.slug as category_slug 
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            WHERE p.status = 'published'
            ORDER BY p.published_at DESC
            LIMIT 3
        ");
        $posts = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Blog preview error: " . $e->getMessage());
    }
}

// Fallback demo posts if database is not yet populated
if (empty($posts)) {
    $posts = [
        [
            'id' => 1,
            'title' => '5 Telltale Signs Your Car Brakes Need Immediate Inspection',
            'slug' => '5-telltale-signs-car-brakes-need-immediate-inspection',
            'excerpt' => 'Don\'t wait for a dangerous squeal or spongy pedal. Learn the key warning signs of brake rotor wear and pad deterioration.',
            'category_name' => 'Maintenance Tips',
            'featured_image' => 'images/service-image.png',
            'published_at' => date('Y-m-d', strtotime('-5 days'))
        ],
        [
            'id' => 2,
            'title' => 'Hontech Auto Center Expands Service Bays to 1,200 SQM in Metro Manila',
            'slug' => 'hontech-auto-center-expands-service-bays-1200-sqm',
            'excerpt' => 'To serve our growing community of vehicle owners, Hontech has upgraded its service footprint with heavy hydraulic lifters and baking booths.',
            'category_name' => 'Company News',
            'featured_image' => 'images/hero-bg.png',
            'published_at' => date('Y-m-d', strtotime('-12 days'))
        ],
        [
            'id' => 3,
            'title' => 'Why Regular PMS Saves Tens of Thousands in Future Repairs',
            'slug' => 'why-regular-pms-saves-tens-of-thousands-future-repairs',
            'excerpt' => 'Routine oil changes, filter renewals, and fluid checks are small investments that prevent catastrophic engine and transmission failures.',
            'category_name' => 'Automotive Guides',
            'featured_image' => 'images/values-bg.png',
            'published_at' => date('Y-m-d', strtotime('-18 days'))
        ]
    ];
}
?>
<!-- ═══════════════════════════════════════
     LATEST NEWS & BLOG ARTICLES
     ═══════════════════════════════════════ -->
<section class="section blog-preview" id="blog" style="background: var(--color-bg);">
    <div class="container">
        <div class="section-header text-center reveal">
            <div class="section-badge">
                <span class="badge-dot"></span>
                Insights & Car Care Knowledge
            </div>
            <h2 class="section-title">
                Latest News & <span class="highlight">Maintenance Tips</span>
            </h2>
            <p class="section-subtitle">
                Expert maintenance advice, automotive guides, and company milestones from our casa-trained technical team.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px; margin-top: 40px;">
            <?php foreach ($posts as $post): ?>
                <article class="blog-card why-card reveal" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                    <div style="height: 200px; overflow: hidden; position: relative;">
                        <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s ease;">
                        <span style="position: absolute; top: 14px; left: 14px; background: var(--color-primary); color: #ffffff; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                            <?= htmlspecialchars($post['category_name']) ?>
                        </span>
                    </div>
                    <div style="padding: 24px; display: flex; flex-direction: column; flex: 1;">
                        <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                            <i data-lucide="calendar" style="width:14px;height:14px"></i>
                            <?= date('M d, Y', strtotime($post['published_at'])) ?>
                        </div>
                        <h3 style="font-size: 1.15rem; font-weight: 700; line-height: 1.4; margin-bottom: 10px; color: var(--color-dark);">
                            <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" style="color: inherit; transition: color 0.2s;">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h3>
                        <p style="font-size: 0.9rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 18px; flex: 1;">
                            <?= htmlspecialchars($post['excerpt']) ?>
                        </p>
                        <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" style="font-size: 0.9rem; font-weight: 700; color: var(--color-primary); display: inline-flex; align-items: center; gap: 6px;">
                            Read Full Article <i data-lucide="arrow-right" style="width:16px;height:16px"></i>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center; margin-top: 40px;" class="reveal">
            <a href="blog.php" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; border-radius: var(--radius-full);">
                <i data-lucide="book-open" style="width:16px;height:16px"></i>
                Browse All Blog Articles
            </a>
        </div>
    </div>
</section>
