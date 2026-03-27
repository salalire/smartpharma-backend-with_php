<?php
header("Content-Type: application/json");

require __DIR__ . '/../../configuration/database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit;
}

$user_id = $data['user_id'];

try {
    $pdo->beginTransaction();

    // 1. Update application status
    $stmt = $pdo->prepare("
        UPDATE pharmacy_profiles 
        SET status = 'approved' 
        WHERE user_id = ?
    ");
    $stmt->execute([$user_id]);

    // 2. Update user role
    $stmt = $pdo->prepare("
        UPDATE users 
        SET role = 'owner' 
        WHERE id = ?
    ");
    $stmt->execute([$user_id]);

    $pdo->commit();

    echo json_encode([
        "status" => "success",
        "message" => "Owner approved successfully"
    ]);

} catch (Exception $e) {
    $pdo->rollBack();

    echo json_encode([
        "status" => "error",
        "message" => "Approval failed"
    ]);
}
?>