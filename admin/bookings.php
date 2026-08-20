<?php
/**
 * Service Bookings & Appointment Manager
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=unauthorized");
    exit;
}

$pdo = getDBConnection();
$bookings = [];

if (isset($_GET['status']) && isset($_GET['id']) && $pdo) {
    $st = $_GET['status'];
    $id = intval($_GET['id']);
    if (in_array($st, ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])) {
        $pdo->prepare("UPDATE bookings SET status = :st WHERE id = :id")->execute([':st' => $st, ':id' => $id]);
    }
    header("Location: bookings.php?msg=updated");
    exit;
}

if ($pdo) {
    $bookings = $pdo->query("SELECT * FROM bookings ORDER BY preferred_date ASC, created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Service Appointments — Hontech RBAC</title>
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
        .badge-confirmed { background: #3b82f6; color: #fff; }
        .badge-in_progress { background: #f59e0b; color: #fff; }
        .badge-completed { background: #10b981; color: #fff; }
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
        <a href="bookings.php" class="nav-item active"><i data-lucide="calendar" style="width:18px;height:18px"></i> Service Bookings</a>
        <a href="inquiries.php" class="nav-item"><i data-lucide="message-square" style="width:18px;height:18px"></i> Customer Inquiries</a>
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
            <h1 style="font-size: 1.8rem; font-weight: 800;">Service Bookings & Schedule</h1>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 2px;">Appointments scheduled via Cost Estimator and Website</p>
        </div>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>Ref Code</th>
                    <th>Customer Details</th>
                    <th>Vehicle</th>
                    <th>Selected Services</th>
                    <th>Est. Quote</th>
                    <th>Schedule Date</th>
                    <th>Status</th>
                    <th>Dispatch / Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $b): ?>
                        <tr>
                            <td><strong style="color:#ef4444"><?= htmlspecialchars($b['booking_reference']) ?></strong></td>
                            <td>
                                <div><strong><?= htmlspecialchars($b['customer_name']) ?></strong></div>
                                <div style="font-size:0.8rem;color:#94a3b8">📞 <?= htmlspecialchars($b['customer_phone']) ?></div>
                                <div style="font-size:0.8rem;color:#94a3b8">✉️ <?= htmlspecialchars($b['customer_email']) ?></div>
                            </td>
                            <td>
                                <div><strong><?= htmlspecialchars($b['vehicle_model']) ?></strong></div>
                                <div style="font-size:0.8rem;color:#94a3b8"><?= htmlspecialchars($b['plate_number'] ?: 'No Plate Entered') ?></div>
                            </td>
                            <td style="max-width: 240px; font-size: 0.85rem; line-height: 1.4;">
                                <?= htmlspecialchars($b['selected_services']) ?>
                            </td>
                            <td><strong style="color:#10b981">₱<?= number_format($b['estimated_cost'], 2) ?></strong></td>
                            <td>
                                <div><strong><?= date('M d, Y', strtotime($b['preferred_date'])) ?></strong></div>
                                <div style="font-size:0.8rem;color:#94a3b8"><?= htmlspecialchars($b['preferred_time']) ?></div>
                            </td>
                            <td>
                                <span class="badge badge-<?= $b['status'] ?>">
                                    <?= htmlspecialchars($b['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px; font-size: 0.8rem;">
                                    <?php if ($b['status'] !== 'confirmed'): ?>
                                        <a href="bookings.php?status=confirmed&id=<?= $b['id'] ?>" style="color: #3b82f6; font-weight:600;">Confirm</a>
                                    <?php endif; ?>
                                    <?php if ($b['status'] !== 'in_progress'): ?>
                                        <a href="bookings.php?status=in_progress&id=<?= $b['id'] ?>" style="color: #f59e0b; font-weight:600;">Dispatch Bay</a>
                                    <?php endif; ?>
                                    <?php if ($b['status'] !== 'completed'): ?>
                                        <a href="bookings.php?status=completed&id=<?= $b['id'] ?>" style="color: #10b981; font-weight:600;">Mark Done</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" style="text-align: center; color: #94a3b8; padding: 24px;">No appointments scheduled yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
