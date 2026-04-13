<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

$data = json_decode(file_get_contents("php://input"), true);

// Get user_id from session OR from credentials passed directly
$user_id = null;

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} elseif (!empty($data['email']) && !empty($data['password'])) {
    // Auto-authenticate using credentials (for registration flow)
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->execute([trim($data['email'])]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data['password'], $user['password'])) {
        $user_id = $user['id'];
        $_SESSION['user_id'] = $user_id;
    }
}

if (!$user_id) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (
    empty($data['pharmacy_name']) ||
    empty($data['first_name'])    ||
    empty($data['last_name'])     ||
    empty($data['tin_number'])    ||
    empty($data['region'])        ||
    empty($data['city'])
) {
    echo json_encode(["status" => "error", "message" => "All required fields must be filled"]);
    exit;
}

$stmt = $pdo->prepare("SELECT id FROM pharmacy_profiles WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "You already have a pending application"]);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO pharmacy_profiles 
    (user_id, pharmacy_name, first_name, middle_name, last_name, tin_number, region, city, sub_city, woreda)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$success = $stmt->execute([
    $user_id,
    $data['pharmacy_name'],
    $data['first_name'],
    $data['middle_name'] ?? null,
    $data['last_name'],
    $data['tin_number'],
    $data['region'],
    $data['city'],
    $data['sub_city'] ?? null,
    $data['woreda']    ?? null
]);

if ($success) {
    echo json_encode(["status" => "success", "message" => "Application submitted. Waiting for approval."]);
} else {
    echo json_encode(["status" => "error", "message" => "Insert failed"]);
}
