<?php
/**
 * Customer Inquiries Manager
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=unauthorized");
    exit;
}

$user_role = $_SESSION['user_role'] ?? '';
if (!in_array($user_role, ['super_admin', 'manager'])) {
    die("Access denied. Supervisor or Administrator role required.");
}

$pdo = getDBConnection();
$inquiries = [];

// Status toggle action
if (isset($_GET['toggle_status']) && isset($_GET['id']) && $pdo) {
    $new_status = $_GET['toggle_status'];
    $inquiry_id = intval($_GET['id']);
    if (in_array($new_status, ['pending', 'contacted', 'resolved'])) {
        $pdo->prepare("UPDATE inquiries SET status = :st WHERE id = :id")->execute([':st' => $new_status, ':id' => $inquiry_id]);
    }
    header("Location: inquiries.php?msg=status_updated");
    exit;
}

if ($pdo) {
    $inquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Inquiries — Hontech RBAC</title>
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
        .table td { padding: 14px 12px; border-bottom: 1px solid #334155; color: #cbd5e1; vertical-align: top; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-pending { background: #ef4444; color: #fff; }
        .badge-contacted { background: #f59e0b; color: #fff; }
        .badge-resolved { background: #10b981; color: #fff; }
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
        <a href="inquiries.php" class="nav-item active"><i data-lucide="message-square" style="width:18px;height:18px"></i> Customer Inquiries</a>
        <a href="posts.php" class="nav-item"><i data-lucide="file-text" style="width:18px;height:18px"></i> Blog CMS</a>
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
            <h1 style="font-size: 1.8rem; font-weight: 800;">Customer Inquiries</h1>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 2px;">Messages sent through the website contact form</p>
        </div>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Contact Info</th>
                    <th>Service Type</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($inquiries)): ?>
                    <?php foreach ($inquiries as $inq): ?>
                        <tr>
                            <td><?= date('M d, Y', strtotime($inq['created_at'])) ?></td>
                            <td><strong><?= htmlspecialchars($inq['name']) ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($inq['email']) ?></div>
                                <div style="font-size:0.8rem;color:#94a3b8"><?= htmlspecialchars($inq['phone']) ?></div>
                            </td>
                            <td><span style="color:#ef4444;font-weight:600"><?= htmlspecialchars($inq['service_type']) ?></span></td>
                            <td style="max-width: 300px; line-height: 1.4;"><?= nl2br(htmlspecialchars($inq['message'])) ?></td>
                            <td>
                                <span class="badge badge-<?= $inq['status'] ?>">
                                    <?= htmlspecialchars($inq['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; font-size: 0.8rem;">
                                    <?php if ($inq['status'] !== 'contacted'): ?>
                                        <a href="inquiries.php?toggle_status=contacted&id=<?= $inq['id'] ?>" style="color: #f59e0b; font-weight:600;">Contacted</a>
                                    <?php endif; ?>
                                    <?php if ($inq['status'] !== 'resolved'): ?>
                                        <a href="inquiries.php?toggle_status=resolved&id=<?= $inq['id'] ?>" style="color: #10b981; font-weight:600;">Resolve</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 24px;">No inquiries submitted yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
