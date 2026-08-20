<?php
/**
 * Blog Article Editor (Create / Edit)
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=unauthorized");
    exit;
}

$pdo = getDBConnection();
$post_id = intval($_GET['id'] ?? 0);
$post = [
    'id' => 0,
    'title' => '',
    'category_id' => 1,
    'excerpt' => '',
    'content' => '',
    'featured_image' => 'images/service-image.png',
    'status' => 'published'
];

$categories = [];
if ($pdo) {
    $categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
    if ($post_id > 0) {
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->execute([':id' => $post_id]);
        $existing = $stmt->fetch();
        if ($existing) {
            $post = $existing;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $post_id > 0 ? 'Edit Article' : 'Write New Article' ?> — Hontech RBAC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #0f172a; color: #f8fafc; display: flex; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }
        .sidebar { width: 260px; background: #1e293b; border-right: 1px solid #334155; padding: 24px 20px; display: flex; flex-direction: column; }
        .sidebar-brand { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1.2rem; margin-bottom: 30px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 8px; color: #94a3b8; font-weight: 600; font-size: 0.9rem; margin-bottom: 4px; }
        .nav-item:hover, .nav-item.active { background: #334155; color: #ffffff; }
        .nav-item.active { background: #dc2626; color: #ffffff; }
        .main-content { flex: 1; padding: 32px 40px; overflow-y: auto; }
        .panel { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 32px; max-width: 900px; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: #cbd5e1; margin-bottom: 6px; }
        input[type="text"], select, textarea { width: 100%; padding: 12px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #ffffff; font-size: 0.95rem; font-family: inherit; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand">
        <div style="background: #dc2626; padding: 6px; border-radius: 8px; display: flex;"><i data-lucide="wrench" style="width:18px;height:18px;color:#fff"></i></div>
        <span>HonTech <span style="color:#ef4444;font-size:0.8rem">RBAC</span></span>
    </div>
    <div style="flex: 1;">
        <a href="dashboard.php" class="nav-item"><i data-lucide="layout-dashboard" style="width:18px;height:18px"></i> Dashboard</a>
        <a href="bookings.php" class="nav-item"><i data-lucide="calendar" style="width:18px;height:18px"></i> Service Bookings</a>
        <a href="inquiries.php" class="nav-item"><i data-lucide="message-square" style="width:18px;height:18px"></i> Customer Inquiries</a>
        <a href="posts.php" class="nav-item"><i data-lucide="file-text" style="width:18px;height:18px"></i> Blog CMS</a>
        <a href="post-editor.php" class="nav-item active"><i data-lucide="plus-circle" style="width:18px;height:18px"></i> Write Article</a>
    </div>
    <div style="border-top: 1px solid #334155; padding-top: 16px;">
        <a href="logout.php" style="display: flex; align-items: center; gap: 8px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">
            <i data-lucide="log-out" style="width:16px;height:16px"></i> Sign Out
        </a>
    </div>
</div>

<div class="main-content">
    <div style="margin-bottom: 24px;">
        <a href="posts.php" style="color: #94a3b8; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px; margin-bottom: 8px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px"></i> Back to All Articles
        </a>
        <h1 style="font-size: 1.8rem; font-weight: 800;"><?= $post_id > 0 ? 'Edit Article' : 'Write New Blog Article' ?></h1>
    </div>

    <div class="panel">
        <form action="../api/blog_actions.php" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            <input type="hidden" name="action" value="save">
            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
            <input type="hidden" name="current_image" value="<?= htmlspecialchars($post['featured_image']) ?>">

            <div>
                <label>Article Title *</label>
                <input type="text" name="title" required value="<?= htmlspecialchars($post['title']) ?>" placeholder="e.g. 5 Maintenance Checks Before a Long Road Trip">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <label>Category *</label>
                    <select name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $post['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label>Publication Status</label>
                    <select name="status">
                        <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published (Live)</option>
                        <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft (Hidden)</option>
                    </select>
                </div>
            </div>

            <div>
                <label>Excerpt / Short Summary (For Cards & SEO) *</label>
                <textarea name="excerpt" rows="2" required placeholder="A brief 1-2 sentence preview of the article..."><?= htmlspecialchars($post['excerpt']) ?></textarea>
            </div>

            <div>
                <label>Featured Image</label>
                <input type="file" name="featured_image" accept="image/*" style="padding: 10px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; width: 100%; color: #94a3b8;">
                <?php if (!empty($post['featured_image'])): ?>
                    <div style="margin-top: 8px; font-size: 0.8rem; color: #94a3b8;">
                        Current image: <code><?= htmlspecialchars($post['featured_image']) ?></code>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <label>Article Content (HTML formatting supported) *</label>
                <textarea name="content" rows="12" required placeholder="<p>Write your detailed article body here...</p>"><?= htmlspecialchars($post['content']) ?></textarea>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" class="btn-primary" style="padding: 14px 28px; font-weight: 700; border-radius: 8px; display: inline-flex; align-items: center; gap: 8px;">
                    <i data-lucide="save" style="width:18px;height:18px"></i> Save & Publish
                </button>
                <a href="posts.php" style="padding: 14px 24px; background: #334155; border-radius: 8px; font-weight: 600; color: #cbd5e1;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
