<?php
/**
 * Role-Based Dashboard (RBAC)
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=unauthorized");
    exit;
}

$user_name = $_SESSION['user_name'] ?? 'Staff Member';
$user_role = $_SESSION['user_role'] ?? 'technician';
$role_display = $_SESSION['role_display'] ?? 'Staff';
$user_dept = $_SESSION['user_dept'] ?? 'General Operations';

$pdo = getDBConnection();

$stats = [
    'inquiries_count' => 0,
    'bookings_count' => 0,
    'posts_count' => 0,
    'job_orders_count' => 0
];

$recentInquiries = [];
$recentBookings = [];
$recentJobs = [];

if ($pdo) {
    try {
        $stats['inquiries_count'] = $pdo->query("SELECT COUNT(*) FROM inquiries WHERE status = 'pending'")->fetchColumn() ?: 0;
        $stats['bookings_count'] = $pdo->query("SELECT COUNT(*) FROM bookings WHERE status = 'pending'")->fetchColumn() ?: 0;
        $stats['posts_count'] = $pdo->query("SELECT COUNT(*) FROM posts WHERE status = 'published'")->fetchColumn() ?: 0;
        $stats['job_orders_count'] = $pdo->query("SELECT COUNT(*) FROM job_orders WHERE status != 'completed'")->fetchColumn() ?: 0;

        $recentInquiries = $pdo->query("SELECT * FROM inquiries ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $recentBookings = $pdo->query("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 5")->fetchAll();
        $recentJobs = $pdo->query("SELECT * FROM job_orders ORDER BY created_at DESC LIMIT 5")->fetchAll();
    } catch (PDOException $e) {
        error_log("Dashboard stats query error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operations Dashboard — Hontech RBAC</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        body { background: #0f172a; color: #f8fafc; display: flex; min-height: 100vh; }
        a { text-decoration: none; color: inherit; }
        
        .sidebar { width: 260px; background: #1e293b; border-right: 1px solid #334155; padding: 24px 20px; display: flex; flex-direction: column; }
        .sidebar-brand { display: flex; align-items: center; gap: 10px; font-weight: 800; font-size: 1.2rem; margin-bottom: 30px; }
        .nav-item { display: flex; align-items: center; gap: 12px; padding: 12px 14px; border-radius: 8px; color: #94a3b8; font-weight: 600; font-size: 0.9rem; margin-bottom: 4px; transition: all 0.2s; }
        .nav-item:hover, .nav-item.active { background: #334155; color: #ffffff; }
        .nav-item.active { background: #dc2626; color: #ffffff; }

        .main-content { flex: 1; padding: 32px 40px; overflow-y: auto; }
        .topbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .badge-admin { background: #dc2626; color: #fff; }
        .badge-manager { background: #3b82f6; color: #fff; }
        .badge-tech { background: #10b981; color: #fff; }
        .badge-mkt { background: #8b5cf6; color: #fff; }

        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 36px; }
        .stat-card { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 20px; }
        .stat-card-title { font-size: 0.85rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; }
        .stat-card-val { font-size: 2rem; font-weight: 800; color: #ffffff; margin-top: 8px; }

        .panel { background: #1e293b; border: 1px solid #334155; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 14px; font-size: 0.9rem; }
        .table th { text-align: left; padding: 12px; color: #94a3b8; border-bottom: 1px solid #334155; }
        .table td { padding: 12px; border-bottom: 1px solid #334155; color: #cbd5e1; }
    </style>
</head>
<body>

<!-- Sidebar Navigation -->
<div class="sidebar">
    <div class="sidebar-brand">
        <div style="background: #dc2626; padding: 6px; border-radius: 8px; display: flex;"><i data-lucide="wrench" style="width:18px;height:18px;color:#fff"></i></div>
        <span>HonTech <span style="color:#ef4444;font-size:0.8rem">RBAC</span></span>
    </div>

    <div style="flex: 1;">
        <a href="dashboard.php" class="nav-item active"><i data-lucide="layout-dashboard" style="width:18px;height:18px"></i> Dashboard</a>
        
        <?php if (in_array($user_role, ['super_admin', 'manager'])): ?>
            <a href="bookings.php" class="nav-item"><i data-lucide="calendar" style="width:18px;height:18px"></i> Service Bookings</a>
            <a href="inquiries.php" class="nav-item"><i data-lucide="message-square" style="width:18px;height:18px"></i> Customer Inquiries</a>
        <?php endif; ?>

        <?php if (in_array($user_role, ['super_admin', 'marketing', 'manager'])): ?>
            <a href="posts.php" class="nav-item"><i data-lucide="file-text" style="width:18px;height:18px"></i> Blog CMS</a>
            <a href="post-editor.php" class="nav-item"><i data-lucide="plus-circle" style="width:18px;height:18px"></i> Write Article</a>
        <?php endif; ?>

        <?php if ($user_role === 'super_admin'): ?>
            <a href="employees.php" class="nav-item"><i data-lucide="users" style="width:18px;height:18px"></i> Staff & Roles</a>
        <?php endif; ?>

        <a href="../index.php" target="_blank" class="nav-item" style="margin-top: 20px; border-top: 1px solid #334155; padding-top: 16px;"><i data-lucide="external-link" style="width:18px;height:18px"></i> View Public Site</a>
    </div>

    <div style="border-top: 1px solid #334155; padding-top: 16px;">
        <div style="font-size: 0.85rem; font-weight: 700; color: #ffffff;"><?= htmlspecialchars($user_name) ?></div>
        <div style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 12px;"><?= htmlspecialchars($role_display) ?></div>
        <a href="logout.php" style="display: flex; align-items: center; gap: 8px; color: #ef4444; font-size: 0.85rem; font-weight: 600;">
            <i data-lucide="log-out" style="width:16px;height:16px"></i> Sign Out
        </a>
    </div>
</div>

<!-- Main Area -->
<div class="main-content">
    <div class="topbar">
        <div>
            <h1 style="font-size: 1.8rem; font-weight: 800;">Operations Control Center</h1>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 2px;">
                Department: <strong style="color: #f8fafc;"><?= htmlspecialchars($user_dept) ?></strong>
            </p>
        </div>
        <div>
            <span class="badge <?= $user_role === 'super_admin' ? 'badge-admin' : ($user_role === 'manager' ? 'badge-manager' : ($user_role === 'technician' ? 'badge-tech' : 'badge-mkt')) ?>">
                <?= htmlspecialchars($role_display) ?>
            </span>
        </div>
    </div>

    <!-- Metrics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-title">Pending Bookings</div>
            <div class="stat-card-val" style="color: #ef4444;"><?= $stats['bookings_count'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Unresolved Inquiries</div>
            <div class="stat-card-val" style="color: #3b82f6;"><?= $stats['inquiries_count'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Published Blog Articles</div>
            <div class="stat-card-val" style="color: #10b981;"><?= $stats['posts_count'] ?></div>
        </div>
        <div class="stat-card">
            <div class="stat-card-title">Active Job Orders</div>
            <div class="stat-card-val" style="color: #f59e0b;"><?= $stats['job_orders_count'] ?></div>
        </div>
    </div>

    <!-- Role-Specific Views -->
    <?php if ($user_role === 'technician'): ?>
        <!-- Technician Service Bay View -->
        <div class="panel">
            <h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 4px;">🔧 My Assigned Job Orders & Checklist</h3>
            <p style="color: #94a3b8; font-size: 0.85rem;">Active vehicle repair and inspection orders in shop bays:</p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Job Order #</th>
                        <th>Vehicle</th>
                        <th>Work Description</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentJobs)): ?>
                        <?php foreach ($recentJobs as $job): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($job['job_order_no']) ?></strong></td>
                                <td><?= htmlspecialchars($job['vehicle_info']) ?></td>
                                <td><?= htmlspecialchars($job['work_description']) ?></td>
                                <td><span class="badge badge-tech"><?= htmlspecialchars($job['status']) ?></span></td>
                                <td><a href="#" style="color: #3b82f6; font-weight: 600;">Update Status</a></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">No pending job orders assigned to you today.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <?php if (in_array($user_role, ['super_admin', 'manager'])): ?>
        <!-- Manager / Admin View: Recent Bookings -->
        <div class="panel">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <h3 style="font-size: 1.2rem; font-weight: 700;">📅 Recent Service Appointments</h3>
                <a href="bookings.php" style="color: #ef4444; font-size: 0.85rem; font-weight: 600;">View All Bookings →</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Ref #</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Vehicle</th>
                        <th>Est. Total</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($recentBookings)): ?>
                        <?php foreach ($recentBookings as $b): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($b['booking_reference']) ?></strong></td>
                                <td><?= htmlspecialchars($b['customer_name']) ?></td>
                                <td><?= htmlspecialchars($b['customer_phone']) ?></td>
                                <td><?= htmlspecialchars($b['vehicle_model']) ?></td>
                                <td>₱<?= number_format($b['estimated_cost'], 2) ?></td>
                                <td><?= date('M d, Y', strtotime($b['preferred_date'])) ?></td>
                                <td><span class="badge badge-manager"><?= htmlspecialchars($b['status']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 20px;">No bookings recorded yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>

<script>lucide.createIcons();</script>
</body>
</html>
