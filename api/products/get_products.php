<?php
// Public endpoint — no auth required
error_reporting(0);
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

$search = $_GET['search'] ?? '';

if ($search) {
    $stmt = $pdo->prepare("
        SELECT p.id, p.name, p.price, p.image, p.requires_prescription,
               u.username AS pharmacy
        FROM products p
        JOIN users u ON u.id = p.owner_id
        WHERE p.name LIKE ?
        ORDER BY p.created_at DESC
    ");
    $stmt->execute(['%' . $search . '%']);
} else {
    $stmt = $pdo->query("
        SELECT p.id, p.name, p.price, p.image, p.requires_prescription,
               u.username AS pharmacy
        FROM products p
        JOIN users u ON u.id = p.owner_id
        ORDER BY p.created_at DESC
    ");
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(["status" => "success", "data" => $products]);
