<?php
header("Content-Type: application/json");

require __DIR__ . '/../../configuration/database.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (
    empty($data['user_id']) ||
    empty($data['pharmacy_name']) ||
    empty($data['first_name']) ||
    empty($data['last_name']) ||
    empty($data['tin_number']) ||
    empty($data['region']) ||
    empty($data['city'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "All required fields must be filled"
    ]);
    exit;
}

$user_id = $data['user_id'];

//  Check if user already applied
$stmt = $pdo->prepare("SELECT id FROM pharmacy_profiles 
WHERE user_id = ? AND status = 'pending'");
$stmt->execute([$user_id]);

if ($stmt->rowCount() > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "You have already applied wait until your current application is processed"
    ]);
    exit;
}

// Insert application
$stmt = $pdo->prepare("
    INSERT INTO pharmacy_profiles 
    (user_id, pharmacy_name, first_name, middle_name, last_name, tin_number, region, city, sub_city, woreda)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $user_id,
    $data['pharmacy_name'],
    $data['first_name'],
    $data['middle_name'] ?? null,
    $data['last_name'],
    $data['tin_number'],
    $data['region'],
    $data['city'],
    $data['sub_city'] ?? null,
    $data['woreda'] ?? null
]);

echo json_encode([
    "status" => "success",
    "message" => "Application submitted. Waiting for approval."
]);
?>