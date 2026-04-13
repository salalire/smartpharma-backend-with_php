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

$owner_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT id, name, price, image, requires_prescription, created_at
    FROM products
    WHERE owner_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$owner_id]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "data" => $products]);
