<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

require __DIR__ . '/../../configuration/database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['order_id']) || empty($data['status'])) {
    echo json_encode(["status" => "error", "message" => "Order ID and status required"]);
    exit;
}

$allowed = ['pending', 'processing', 'completed', 'cancelled'];
if (!in_array($data['status'], $allowed)) {
    echo json_encode(["status" => "error", "message" => "Invalid status"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
$stmt->execute([$data['status'], $data['order_id']]);

echo json_encode(["status" => "success", "message" => "Order status updated"]);
