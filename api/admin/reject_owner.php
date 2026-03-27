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
    // Check application
    $stmt = $pdo->prepare("
        SELECT status 
        FROM pharmacy_profiles 
        WHERE id = ?
    ");
    $stmt->execute([$application_id]);

    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) {
        echo json_encode([
            "status" => "error",
            "message" => "Application not found"
        ]);
        exit;
    }

    if ($app['status'] !== 'pending') {
        echo json_encode([
            "status" => "error",
            "message" => "Application already processed"
        ]);
        exit;
    }

    // Reject application
    $stmt = $pdo->prepare("
        UPDATE pharmacy_profiles 
        SET status = 'rejected' 
        WHERE id = ?
    ");
    $stmt->execute([$application_id]);

    echo json_encode([
        "status" => "success",
        "message" => "Application rejected successfully"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}
?>