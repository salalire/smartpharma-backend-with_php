<?php
header("Content-Type: application/json");

require __DIR__ . '/../../configuration/database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

$user_id = $data['user_id'];

// Update only status
$stmt = $pdo->prepare("
    UPDATE pharmacy_profiles 
    SET status = 'rejected' 
    WHERE user_id = ?
");

$stmt->execute([$user_id]);

echo json_encode([
    "status" => "success",
    "message" => "Application rejected"
]);
?>