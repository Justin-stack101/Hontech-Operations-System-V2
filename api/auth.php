<?php
/**
 * Authentication Handler
 * Hontech Auto Center Inc. (RBAC)
 */

require_once __DIR__ . '/../config/db.php';

$action = $_GET['action'] ?? ($_POST['action'] ?? '');

if ($action === 'logout') {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header("Location: ../admin/login.php?msg=logged_out");
    exit;
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        header("Location: ../admin/login.php?error=empty_fields");
        exit;
    }

    $pdo = getDBConnection();

    // Fallback demo logins if database is offline
    if (!$pdo) {
        $demoUsers = [
            'admin@hontech.com' => ['id' => 1, 'name' => 'Engr. Justin Honrado', 'role' => 'super_admin', 'role_name' => 'Super Administrator'],
            'manager@hontech.com' => ['id' => 2, 'name' => 'Marco Santos', 'role' => 'manager', 'role_name' => 'Service Manager'],
            'technician@hontech.com' => ['id' => 3, 'name' => 'Danilo Reyes', 'role' => 'technician', 'role_name' => 'Lead Technician'],
            'marketing@hontech.com' => ['id' => 4, 'name' => 'Elena Cruz', 'role' => 'marketing', 'role_name' => 'Marketing Editor']
        ];

        if (isset($demoUsers[$email]) && $password === 'password123') {
            $_SESSION['user_id'] = $demoUsers[$email]['id'];
            $_SESSION['user_name'] = $demoUsers[$email]['name'];
            $_SESSION['user_email'] = $email;
            $_SESSION['user_role'] = $demoUsers[$email]['role'];
            $_SESSION['role_display'] = $demoUsers[$email]['role_name'];
            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            header("Location: ../admin/login.php?error=invalid_credentials");
            exit;
        }
    }

    try {
        $stmt = $pdo->prepare("
            SELECT e.*, r.role_name, r.display_name as role_display, d.name as dept_name 
            FROM employees e
            JOIN roles r ON e.role_id = r.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE e.email = :email AND e.status = 'active'
            LIMIT 1
        ");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role_name'];
            $_SESSION['role_display'] = $user['role_display'];
            $_SESSION['user_dept'] = $user['dept_name'];
            $_SESSION['employee_code'] = $user['employee_code'];

            // Log activity
            $logStmt = $pdo->prepare("INSERT INTO audit_logs (employee_id, action, description, ip_address) VALUES (:id, 'LOGIN', 'Successful employee login', :ip)");
            $logStmt->execute([':id' => $user['id'], ':ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1']);

            // Update last login
            $pdo->prepare("UPDATE employees SET last_login = NOW() WHERE id = :id")->execute([':id' => $user['id']]);

            header("Location: ../admin/dashboard.php");
            exit;
        } else {
            header("Location: ../admin/login.php?error=invalid_credentials");
            exit;
        }
    } catch (PDOException $e) {
        error_log("Auth Error: " . $e->getMessage());
        header("Location: ../admin/login.php?error=server_error");
        exit;
    }
}
