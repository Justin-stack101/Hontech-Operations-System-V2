<?php
/**
 * Employee & Staff Login Screen
 * Hontech Auto Center Inc. (RBAC)
 */

require_once __DIR__ . '/../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = $_GET['error'] ?? '';
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal Login — Hontech Auto Center</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body {
            background-color: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            padding: 20px;
        }
        .login-card {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 40px;
            max-width: 440px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .demo-pill {
            background: #334155;
            border: 1px solid #475569;
            color: #cbd5e1;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
        }
        .demo-pill:hover {
            background: #dc2626;
            color: #ffffff;
            border-color: #dc2626;
        }
    </style>
</head>
<body>

<div class="login-card">
    
    <div style="text-align: center; margin-bottom: 28px;">
        <div style="width: 50px; height: 50px; background: #dc2626; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px;">
            <i data-lucide="shield-check" style="width:26px;height:26px;color:#ffffff"></i>
        </div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: #ffffff;">Employee Portal</h2>
        <p style="color: #94a3b8; font-size: 0.85rem; margin-top: 4px;">Hontech Auto Center Operations & RBAC</p>
    </div>

    <?php if ($error === 'invalid_credentials'): ?>
        <div style="background: rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444; color: #fca5a5; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px;">
            Invalid email address or password.
        </div>
    <?php elseif ($error === 'empty_fields'): ?>
        <div style="background: rgba(239, 68, 68, 0.15); border-left: 4px solid #ef4444; color: #fca5a5; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px;">
            Please enter your employee email and password.
        </div>
    <?php elseif ($msg === 'logged_out'): ?>
        <div style="background: rgba(16, 185, 129, 0.15); border-left: 4px solid #10b981; color: #6ee7b7; padding: 12px 16px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px;">
            You have been logged out securely.
        </div>
    <?php endif; ?>

    <form action="../api/auth.php" method="POST" style="display: flex; flex-direction: column; gap: 18px;">
        <input type="hidden" name="action" value="login">

        <div>
            <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 6px;">Staff Email Address</label>
            <input type="email" id="emailInput" name="email" required placeholder="admin@hontech.com" style="width: 100%; padding: 12px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #ffffff; font-size: 0.95rem;">
        </div>

        <div>
            <label style="font-size: 0.85rem; font-weight: 600; color: #cbd5e1; display: block; margin-bottom: 6px;">Password</label>
            <input type="password" id="passwordInput" name="password" required placeholder="••••••••" style="width: 100%; padding: 12px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #ffffff; font-size: 0.95rem;">
        </div>

        <button type="submit" class="btn-primary" style="padding: 14px; font-weight: 700; border-radius: 8px; margin-top: 4px; display: flex; align-items: center; justify-content: center; gap: 8px; width: 100%;">
            <i data-lucide="log-in" style="width:18px;height:18px"></i>
            Sign In to Portal
        </button>
    </form>

    <!-- Quick Role Autofill Helpers for Testing -->
    <div style="margin-top: 30px; border-top: 1px solid #334155; padding-top: 20px;">
        <div style="font-size: 0.75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px; text-align: center;">
            🧪 Quick Demo Logins (Click to Autofill)
        </div>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
            <button type="button" class="demo-pill" onclick="autofill('admin@hontech.com', 'password123')">
                <strong>Super Admin</strong><br><span style="font-size:0.7rem;color:#94a3b8">admin@hontech.com</span>
            </button>
            <button type="button" class="demo-pill" onclick="autofill('manager@hontech.com', 'password123')">
                <strong>Service Manager</strong><br><span style="font-size:0.7rem;color:#94a3b8">manager@hontech.com</span>
            </button>
            <button type="button" class="demo-pill" onclick="autofill('technician@hontech.com', 'password123')">
                <strong>Technician / Mech</strong><br><span style="font-size:0.7rem;color:#94a3b8">technician@hontech.com</span>
            </button>
            <button type="button" class="demo-pill" onclick="autofill('marketing@hontech.com', 'password123')">
                <strong>Marketing Editor</strong><br><span style="font-size:0.7rem;color:#94a3b8">marketing@hontech.com</span>
            </button>
        </div>
    </div>

    <div style="text-align: center; margin-top: 24px;">
        <a href="../index.php" style="color: #94a3b8; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 6px;">
            <i data-lucide="arrow-left" style="width:14px;height:14px"></i> Return to Homepage
        </a>
    </div>

</div>

<script>
    lucide.createIcons();
    function autofill(email, pass) {
        document.getElementById('emailInput').value = email;
        document.getElementById('passwordInput').value = pass;
    }
</script>
</body>
</html>
