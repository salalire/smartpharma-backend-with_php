<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['application_id'])) {
    echo json_encode(["status" => "error", "message" => "Application ID required"]);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT user_id, status FROM pharmacy_profiles WHERE id = ?");
    $stmt->execute([$data['application_id']]);
    $app = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$app) throw new Exception("Application not found");
    if ($app['status'] !== 'pending') throw new Exception("Application already processed");

    $pdo->prepare("UPDATE pharmacy_profiles SET status = 'approved' WHERE id = ?")
        ->execute([$data['application_id']]);
    $pdo->prepare("UPDATE users SET role = 'owner' WHERE id = ?")
        ->execute([$app['user_id']]);

    $pdo->commit();
    echo json_encode(["status" => "success", "message" => "Pharmacy approved"]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
