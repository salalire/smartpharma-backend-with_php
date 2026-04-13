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

if (empty($data['id'])) {
    echo json_encode(["status" => "error", "message" => "Product ID required"]);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND owner_id = ?");
$stmt->execute([$data['id'], $_SESSION['user_id']]);

echo json_encode(["status" => "success", "message" => "Product deleted"]);
