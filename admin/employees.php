<?php
/**
 * Staff & Role Management (Super Admin only)
 * Hontech Auto Center Inc.
 */

require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?error=unauthorized");
    exit;
}

if (($_SESSION['user_role'] ?? '') !== 'super_admin') {
    die("Access denied. Super Administrator role required.");
}

$pdo = getDBConnection();
$employees = [];

if ($pdo) {
    $employees = $pdo->query("
        SELECT e.*, r.display_name as role_title, d.name as dept_name 
        FROM employees e
        JOIN roles r ON e.role_id = r.id
        LEFT JOIN departments d ON e.department_id = d.id
        ORDER BY e.role_id ASC, e.full_name ASC
    ")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff & Roles Management — Hontech RBAC</title>
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
        .badge-active { background: #10b981; color: #fff; }
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
        <a href="employees.php" class="nav-item active"><i data-lucide="users" style="width:18px;height:18px"></i> Staff & Roles</a>
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
            <h1 style="font-size: 1.8rem; font-weight: 800;">Staff & Employee Roster</h1>
            <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 2px;">Role-Based Access Control (RBAC) User Registry</p>
        </div>
    </div>

    <div class="panel">
        <table class="table">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Staff Name</th>
                    <th>Email</th>
                    <th>Assigned Role</th>
                    <th>Department</th>
                    <th>Account Status</th>
                    <th>Last Login</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($employees)): ?>
                    <?php foreach ($employees as $emp): ?>
                        <tr>
                            <td><code style="color:#ef4444"><?= htmlspecialchars($emp['employee_code']) ?></code></td>
                            <td><strong><?= htmlspecialchars($emp['full_name']) ?></strong></td>
                            <td><?= htmlspecialchars($emp['email']) ?></td>
                            <td><strong style="color:#3b82f6"><?= htmlspecialchars($emp['role_title']) ?></strong></td>
                            <td><?= htmlspecialchars($emp['dept_name'] ?? 'General Operations') ?></td>
                            <td><span class="badge badge-active"><?= htmlspecialchars($emp['status']) ?></span></td>
                            <td><?= $emp['last_login'] ? date('M d, Y H:i', strtotime($emp['last_login'])) : 'Never logged in' ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align: center; color: #94a3b8; padding: 24px;">No employees found. Import database/hontech_db.sql to seed default accounts.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>
