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

// Get orders that contain this owner's products
$stmt = $pdo->prepare("
    SELECT DISTINCT
        o.id,
        o.status,
        o.total_price,
        o.created_at,
        u.username AS customer_name,
        u.email    AS customer_email
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p     ON p.id = oi.product_id
    JOIN users u        ON u.id = o.user_id
    WHERE p.owner_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(["status" => "success", "data" => $orders]);
