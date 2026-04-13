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

if (empty($data['name']) || !isset($data['price'])) {
    echo json_encode(["status" => "error", "message" => "Name and price are required"]);
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO products (owner_id, name, price, image, requires_prescription)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([
    $_SESSION['user_id'],
    trim($data['name']),
    floatval($data['price']),
    $data['image'] ?? null,
    isset($data['requires_prescription']) ? (int)$data['requires_prescription'] : 0
]);

echo json_encode(["status" => "success", "message" => "Product added", "id" => $pdo->lastInsertId()]);
