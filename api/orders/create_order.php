<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// items = [{ id, name, price, quantity }, ...]
if (empty($data['items']) || !is_array($data['items'])) {
    echo json_encode(["status" => "error", "message" => "No items provided"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$items   = $data['items'];

// Calculate total
$total = 0;
foreach ($items as $item) {
    $total += floatval($item['price']) * intval($item['quantity']);
}

try {
    $pdo->beginTransaction();

    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    // Insert order items
    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($items as $item) {
        $stmt->execute([$order_id, intval($item['id']), intval($item['quantity'])]);
    }

    $pdo->commit();

    echo json_encode([
        "status"   => "success",
        "message"  => "Order placed successfully",
        "order_id" => $order_id,
        "total"    => $total
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(["status" => "error", "message" => "Order failed"]);
}
