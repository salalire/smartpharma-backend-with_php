<?php
header("Content-Type: application/json");

require __DIR__ . '/../middleware/admin_only.php';
require __DIR__ . '/../../configuration/database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['application_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Application ID required"
    ]);
    exit;
}

$application_id = $data['application_id'];

try {
    $pdo->beginTransaction();

    // 1. Check application
    $stmt = $pdo->prepare("
        SELECT user_id, status 
        FROM pharmacy_profiles 
        WHERE id = ?
    ");
    $stmt->execute([$application_id]);

    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        throw new Exception("Application not found");
    }

    if ($app['status'] !== 'pending') {
        throw new Exception("Application already processed");
    }

    $user_id = $app['user_id'];

    // 2. Approve application
    $stmt = $pdo->prepare("
        UPDATE pharmacy_profiles 
        SET status = 'approved' 
        WHERE id = ?
    ");
    $stmt->execute([$application_id]);

    // 3. Update user role
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
        "message" => $e->getMessage()
    ]);
}
?>