<?php
/**
 * API: Submit Service Booking / Estimate Lead
 * Hontech Auto Center Inc.
 */

header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$customer_name = trim($_POST['customer_name'] ?? '');
$customer_email = trim($_POST['customer_email'] ?? '');
$customer_phone = trim($_POST['customer_phone'] ?? '');
$vehicle_make = trim($_POST['vehicle_make'] ?? '');
$vehicle_model = trim($_POST['vehicle_model'] ?? '');
$plate_number = trim($_POST['plate_number'] ?? '');
$selected_services = trim($_POST['selected_services'] ?? '');
$estimated_cost = floatval($_POST['estimated_cost'] ?? 0);
$preferred_date = trim($_POST['preferred_date'] ?? date('Y-m-d', strtotime('+2 days')));
$preferred_time = trim($_POST['preferred_time'] ?? 'Morning (8AM-12PM)');
$additional_notes = trim($_POST['additional_notes'] ?? '');

if (empty($customer_name) || empty($customer_phone) || empty($vehicle_model)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Please provide your name, contact phone, and vehicle model.']);
    exit;
}

$ref_code = 'HON-BK-' . date('Y') . '-' . strtoupper(substr(uniqid(), -4));

$pdo = getDBConnection();
if (!$pdo) {
    echo json_encode([
        'success' => true,
        'reference_code' => $ref_code,
        'message' => 'Appointment scheduled! (Demo mode: Connect MySQL in XAMPP to record data).'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO bookings (
            booking_reference, customer_name, customer_email, customer_phone,
            vehicle_make, vehicle_model, plate_number, selected_services,
            estimated_cost, preferred_date, preferred_time, additional_notes, status
        ) VALUES (
            :booking_reference, :customer_name, :customer_email, :customer_phone,
            :vehicle_make, :vehicle_model, :plate_number, :selected_services,
            :estimated_cost, :preferred_date, :preferred_time, :additional_notes, 'pending'
        )
    ");

    $stmt->execute([
        ':booking_reference' => $ref_code,
        ':customer_name'     => $customer_name,
        ':customer_email'    => $customer_email,
        ':customer_phone'    => $customer_phone,
        ':vehicle_make'      => $vehicle_make,
        ':vehicle_model'     => $vehicle_model,
        ':plate_number'      => $plate_number,
        ':selected_services' => $selected_services,
        ':estimated_cost'    => $estimated_cost,
        ':preferred_date'    => $preferred_date,
        ':preferred_time'    => $preferred_time,
        ':additional_notes'  => $additional_notes
    ]);

    echo json_encode([
        'success' => true,
        'reference_code' => $ref_code,
        'message' => 'Your service appointment has been booked! We look forward to servicing your car.'
    ]);
} catch (PDOException $e) {
    error_log("Booking Insert Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'A server error occurred while booking appointment.']);
}
