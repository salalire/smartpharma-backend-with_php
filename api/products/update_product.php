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

if (empty($data['id']) || empty($data['name']) || !isset($data['price'])) {
    echo json_encode(["status" => "error", "message" => "ID, name and price are required"]);
    exit;
}

// Only allow owner to update their own product
$stmt = $pdo->prepare("
    UPDATE products SET name = ?, price = ?, image = ?, requires_prescription = ?
    WHERE id = ? AND owner_id = ?
");
$stmt->execute([
    trim($data['name']),
    floatval($data['price']),
    $data['image'] ?? null,
    isset($data['requires_prescription']) ? (int)$data['requires_prescription'] : 0,
    $data['id'],
    $_SESSION['user_id']
]);

echo json_encode(["status" => "success", "message" => "Product updated"]);
