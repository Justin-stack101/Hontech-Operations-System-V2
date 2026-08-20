<?php
/**
 * Blog Archive / Knowledge Base Page
 * Hontech Auto Center Inc.
 */

$pageTitle = 'Blog & Car Care Tips — Hontech Auto Center Inc.';
$pageDescription = 'Practical automotive maintenance tips, repair guides, and company news from the casa-trained mechanics at Hontech Auto Center.';

require_once __DIR__ . '/config/db.php';

$pdo = getDBConnection();
$selectedCategory = $_GET['category'] ?? '';
$searchQuery = trim($_GET['q'] ?? '');

$categories = [];
$posts = [];

if ($pdo) {
    try {
        // Fetch categories
        $catStmt = $pdo->query("SELECT * FROM categories ORDER BY name ASC");
        $categories = $catStmt->fetchAll();

        // Build query
        $sql = "
            SELECT p.*, c.name as category_name, c.slug as category_slug, e.full_name as author_name
            FROM posts p
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN employees e ON p.author_id = e.id
            WHERE p.status = 'published'
        ";
        $params = [];

        if (!empty($selectedCategory)) {
            $sql .= " AND c.slug = :cat_slug";
            $params[':cat_slug'] = $selectedCategory;
        }

        if (!empty($searchQuery)) {
            $sql .= " AND (p.title LIKE :search OR p.excerpt LIKE :search OR p.content LIKE :search)";
            $params[':search'] = "%$searchQuery%";
        }

        $sql .= " ORDER BY p.published_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Blog Query Error: " . $e->getMessage());
    }
}

// Fallback demo posts if database is not active yet
if (empty($posts) && empty($selectedCategory) && empty($searchQuery)) {
    $categories = [
        ['id' => 1, 'name' => 'Maintenance Tips', 'slug' => 'maintenance-tips'],
        ['id' => 2, 'name' => 'Company News', 'slug' => 'company-news'],
        ['id' => 3, 'name' => 'Promotions & Packages', 'slug' => 'promotions-packages'],
        ['id' => 4, 'name' => 'Automotive Guides', 'slug' => 'automotive-guides']
    ];

    $posts = [
        [
            'id' => 1,
            'title' => '5 Telltale Signs Your Car Brakes Need Immediate Inspection',
            'slug' => '5-telltale-signs-car-brakes-need-immediate-inspection',
            'excerpt' => 'Don\'t wait for a dangerous squeal or spongy pedal. Learn the key warning signs of brake rotor wear and pad deterioration.',
            'category_name' => 'Maintenance Tips',
            'category_slug' => 'maintenance-tips',
            'featured_image' => 'images/service-image.png',
            'author_name' => 'Engr. Justin Honrado',
            'published_at' => date('Y-m-d', strtotime('-5 days'))
        ],
        [
            'id' => 2,
            'title' => 'Hontech Auto Center Expands Service Bays to 1,200 SQM in Metro Manila',
            'slug' => 'hontech-auto-center-expands-service-bays-1200-sqm',
            'excerpt' => 'To serve our growing community of vehicle owners, Hontech has upgraded its service footprint with heavy hydraulic lifters and baking booths.',
            'category_name' => 'Company News',
            'category_slug' => 'company-news',
            'featured_image' => 'images/hero-bg.png',
            'author_name' => 'Elena Cruz',
            'published_at' => date('Y-m-d', strtotime('-12 days'))
        ],
        [
            'id' => 3,
            'title' => 'Why Regular PMS Saves Tens of Thousands in Future Repairs',
            'slug' => 'why-regular-pms-saves-tens-of-thousands-future-repairs',
            'excerpt' => 'Routine oil changes, filter renewals, and fluid checks are small investments that prevent catastrophic engine and transmission failures.',
            'category_name' => 'Automotive Guides',
            'category_slug' => 'automotive-guides',
            'featured_image' => 'images/values-bg.png',
            'author_name' => 'Danilo Reyes',
            'published_at' => date('Y-m-d', strtotime('-18 days'))
        ]
    ];
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- Blog Banner -->
<section style="background: var(--color-dark); color: #ffffff; padding: 120px 0 60px; text-align: center; position: relative;">
    <div class="container" style="max-width: 800px;">
        <div class="section-badge" style="background: rgba(220, 38, 38, 0.2); color: #ef4444; border-color: rgba(220, 38, 38, 0.4); margin-bottom: 16px;">
            <span class="badge-dot" style="background: #ef4444;"></span>
            Car Care Knowledge & News
        </div>
        <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 16px;">
            Hontech <span style="color: var(--color-primary);">Blog & Insights</span>
        </h1>
        <p style="color: #9ca3af; font-size: 1.05rem; line-height: 1.6;">
            Practical advice from our casa-trained mechanics to help you maintain your car’s peak performance, safety, and longevity.
        </p>

        <!-- Search Bar -->
        <form action="blog.php" method="GET" style="margin-top: 30px; display: flex; max-width: 550px; margin-left: auto; margin-right: auto; gap: 10px;">
            <?php if (!empty($selectedCategory)): ?>
                <input type="hidden" name="category" value="<?= htmlspecialchars($selectedCategory) ?>">
            <?php endif; ?>
            <input type="text" name="q" value="<?= htmlspecialchars($searchQuery) ?>" placeholder="Search maintenance tips, engine care, brake advice..." style="flex: 1; padding: 14px 20px; border-radius: var(--radius-full); border: 1px solid var(--color-dark-tertiary); background: #1f2937; color: #ffffff; font-size: 0.95rem;">
            <button type="submit" class="btn-primary" style="border-radius: var(--radius-full); padding: 14px 24px;">
                <i data-lucide="search" style="width:18px;height:18px"></i>
            </button>
        </form>
    </div>
</section>

<!-- Blog Catalog Grid -->
<section class="section" style="background: var(--color-bg); padding: 50px 0 80px;">
    <div class="container">

        <!-- Category Filter Pills -->
        <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center; margin-bottom: 40px;">
            <a href="blog.php<?= !empty($searchQuery) ? '?q=' . urlencode($searchQuery) : '' ?>" class="btn-secondary" style="padding: 8px 18px; font-size: 0.85rem; border-radius: var(--radius-full); <?= empty($selectedCategory) ? 'background: var(--color-primary); color: #ffffff; border-color: var(--color-primary);' : '' ?>">
                All Categories
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="blog.php?category=<?= urlencode($cat['slug']) ?><?= !empty($searchQuery) ? '&q=' . urlencode($searchQuery) : '' ?>" class="btn-secondary" style="padding: 8px 18px; font-size: 0.85rem; border-radius: var(--radius-full); <?= $selectedCategory === $cat['slug'] ? 'background: var(--color-primary); color: #ffffff; border-color: var(--color-primary);' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($posts)): ?>
            <div style="text-align: center; padding: 60px 20px; background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border);">
                <i data-lucide="file-question" style="width:48px;height:48px;color:var(--color-primary);margin: 0 auto 16px;"></i>
                <h3 style="font-size: 1.3rem; font-weight: 700; color: var(--color-dark);">No Articles Found</h3>
                <p style="color: var(--color-text-secondary); margin-top: 8px;">Try clearing your search or selecting another category.</p>
                <a href="blog.php" class="btn-primary" style="display: inline-block; margin-top: 20px;">View All Articles</a>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 30px;">
                <?php foreach ($posts as $post): ?>
                    <article class="blog-card why-card reveal" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; background: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--color-border); box-shadow: var(--shadow-sm);">
                        <div style="height: 220px; overflow: hidden; position: relative;">
                            <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <span style="position: absolute; top: 14px; left: 14px; background: var(--color-primary); color: #ffffff; font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 4px; text-transform: uppercase;">
                                <?= htmlspecialchars($post['category_name']) ?>
                            </span>
                        </div>
                        <div style="padding: 24px; display: flex; flex-direction: column; flex: 1;">
                            <div style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;">
                                <span><i data-lucide="calendar" style="width:13px;height:13px;display:inline-block;vertical-align:middle;"></i> <?= date('M d, Y', strtotime($post['published_at'])) ?></span>
                                <span><i data-lucide="user" style="width:13px;height:13px;display:inline-block;vertical-align:middle;"></i> <?= htmlspecialchars($post['author_name'] ?? 'Hontech Team') ?></span>
                            </div>
                            <h3 style="font-size: 1.2rem; font-weight: 700; line-height: 1.4; margin-bottom: 10px; color: var(--color-dark);">
                                <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" style="color: inherit;">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h3>
                            <p style="font-size: 0.9rem; color: var(--color-text-secondary); line-height: 1.6; margin-bottom: 20px; flex: 1;">
                                <?= htmlspecialchars($post['excerpt']) ?>
                            </p>
                            <a href="blog-post.php?slug=<?= urlencode($post['slug']) ?>" style="font-size: 0.9rem; font-weight: 700; color: var(--color-primary); display: inline-flex; align-items: center; gap: 6px;">
                                Read Complete Article <i data-lucide="arrow-right" style="width:16px;height:16px"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
