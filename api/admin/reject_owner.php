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

$stmt = $pdo->prepare("SELECT status FROM pharmacy_profiles WHERE id = ?");
$stmt->execute([$data['application_id']]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$app) { echo json_encode(["status" => "error", "message" => "Not found"]); exit; }
if ($app['status'] !== 'pending') { echo json_encode(["status" => "error", "message" => "Already processed"]); exit; }

$pdo->prepare("UPDATE pharmacy_profiles SET status = 'rejected' WHERE id = ?")
    ->execute([$data['application_id']]);

echo json_encode(["status" => "success", "message" => "Application rejected"]);
