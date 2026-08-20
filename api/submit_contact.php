<?php
/**
 * API: Submit Contact Inquiry
 * Hontech Auto Center Inc.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$service_type = trim($_POST['service_type'] ?? 'General Inquiry');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide your name, email, and message.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide a valid email address.']);
    exit;
}

$pdo = getDBConnection();
if (!$pdo) {
    // If DB is offline, return graceful simulated success for front-end demo
    echo json_encode([
        'success' => true,
        'message' => 'Message received! (Database in demo offline mode - connect MySQL in XAMPP to persist).'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO inquiries (name, email, phone, service_type, message, status) 
        VALUES (:name, :email, :phone, :service_type, :message, 'pending')
    ");
    $stmt->execute([
        ':name' => $name,
        ':email' => $email,
        ':phone' => $phone,
        ':service_type' => $service_type,
        ':message' => $message
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Thank you! Your message has been sent. Our service supervisors will contact you shortly.'
    ]);
} catch (PDOException $e) {
    error_log("Inquiry Insert Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A server error occurred while saving your inquiry.']);
}
