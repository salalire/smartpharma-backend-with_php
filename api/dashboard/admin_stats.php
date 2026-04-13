<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

// Verify session exists and role is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$total_users    = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();
$total_owners   = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'owner'")->fetchColumn();
$pending_apps   = $pdo->query("SELECT COUNT(*) FROM pharmacy_profiles WHERE status = 'pending'")->fetchColumn();
$approved_apps  = $pdo->query("SELECT COUNT(*) FROM pharmacy_profiles WHERE status = 'approved'")->fetchColumn();
$rejected_apps  = $pdo->query("SELECT COUNT(*) FROM pharmacy_profiles WHERE status = 'rejected'")->fetchColumn();
$total_products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$total_orders   = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

echo json_encode([
    "status"         => "success",
    "total_users"    => (int)$total_users,
    "total_owners"   => (int)$total_owners,
    "pending_apps"   => (int)$pending_apps,
    "approved_apps"  => (int)$approved_apps,
    "rejected_apps"  => (int)$rejected_apps,
    "total_products" => (int)$total_products,
    "total_orders"   => (int)$total_orders
]);
