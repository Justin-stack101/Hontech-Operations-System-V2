<?php
/**
 * Blog CMS Manager
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=unauthorized");
    exit;
}

$pdo = getDBConnection();
$posts = [];

if ($pdo) {
    $posts = $pdo->query("
        SELECT p.*, c.name as category_name, e.full_name as author_name 
        FROM posts p
        JOIN categories c ON p.category_id = c.id
        LEFT JOIN employees e ON p.author_id = e.id
        ORDER BY p.created_at DESC
    ")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog Posts Manager — Hontech RBAC</title>
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
        .panel { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 0.9rem; }
        .table th { text-align: left; padding: 12px; color: #94a3b8; border-bottom: 1px solid #334155; }
        .table td { padding: 14px 12px; border-bottom: 1px solid #334155; color: #cbd5e1; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-published { background: #10b981; color: #fff; }
        .badge-draft { background: #64748b; color: #fff; }
        .btn-new { background: #dc2626; color: #fff; padding: 10px 18px; border-radius: 8px; font-weight: 700; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; }
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
        <a href="posts.php" class="nav-item active"><i data-lucide="file-text" style="width:18px;height:18px"></i> Blog CMS</a>
        <a href="post-editor.php" class="nav-item"><i data-lucide="plus-circle" style="width:18px;height:18px"></i> Write Article</a>
    </div>
    <div style="border-top: 1px solid #334155; padding-top: 16px;">
        <a href="logout.php" style="display: flex; align-items: center; gap: 8px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">
            <i data-lucide="log-out" style="width:16px;height:16px"></i> Sign Out
        </a>
    </div>
</div>

<div class="main-content">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px;">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800;">Blog & Knowledge Base Articles</h1>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 2px;">Manage published car care guides and company updates</p>
        </div>
        <a href="post-editor.php" class="btn-new">
            <i data-lucide="plus" style="width:18px;height:18px"></i> Create New Post
        </a>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>Article Title</th>
                    <th>Category</th>
                    <th>Author</th>
                    <th>Views</th>
                    <th>Status</th>
                    <th>Date Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $p): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($p['title']) ?></strong>
                            </td>
                            <td><span style="color:#ef4444;font-weight:600"><?= htmlspecialchars($p['category_name']) ?></span></td>
                            <td><?= htmlspecialchars($p['author_name'] ?? 'Hontech Team') ?></td>
                            <td><?= number_format($p['views_count']) ?></td>
                            <td>
                                <span class="badge badge-<?= $p['status'] ?>">
                                    <?= htmlspecialchars($p['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M d, Y', strtotime($p['published_at'])) ?></td>
                            <td>
                                <div style="display: flex; gap: 10px; font-size: 0.85rem;">
                                    <a href="../blog-post.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" style="color: #3b82f6; font-weight:600;">View</a>
                                    <a href="post-editor.php?id=<?= $p['id'] ?>" style="color: #f59e0b; font-weight:600;">Edit</a>
                                    <a href="../api/blog_actions.php?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Are you sure you want to delete this article?')" style="color: #ef4444; font-weight:600;">Delete</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 24px;">No blog articles found. Click "Create New Post" to write your first article!</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
