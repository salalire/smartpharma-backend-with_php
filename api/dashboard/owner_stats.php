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

// Total products
$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE owner_id = ?");
$stmt->execute([$owner_id]);
$total_products = $stmt->fetchColumn();

// Total orders for this owner's products
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT o.id)
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON p.id = oi.product_id
    WHERE p.owner_id = ?
");
$stmt->execute([$owner_id]);
$total_orders = $stmt->fetchColumn();

// Pending orders
$stmt = $pdo->prepare("
    SELECT COUNT(DISTINCT o.id)
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON p.id = oi.product_id
    WHERE p.owner_id = ? AND o.status = 'pending'
");
$stmt->execute([$owner_id]);
$pending_orders = $stmt->fetchColumn();

// Total revenue (completed orders)
$stmt = $pdo->prepare("
    SELECT COALESCE(SUM(o.total_price), 0)
    FROM orders o
    JOIN order_items oi ON oi.order_id = o.id
    JOIN products p ON p.id = oi.product_id
    WHERE p.owner_id = ? AND o.status = 'completed'
");
$stmt->execute([$owner_id]);
$revenue = $stmt->fetchColumn();

// Pharmacy info
$stmt = $pdo->prepare("SELECT pharmacy_name, status FROM pharmacy_profiles WHERE user_id = ? LIMIT 1");
$stmt->execute([$owner_id]);
$pharmacy = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    "status"          => "success",
    "total_products"  => (int)$total_products,
    "total_orders"    => (int)$total_orders,
    "pending_orders"  => (int)$pending_orders,
    "revenue"         => number_format((float)$revenue, 2),
    "pharmacy_name"   => $pharmacy['pharmacy_name'] ?? "My Pharmacy"
]);
