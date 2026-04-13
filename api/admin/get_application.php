<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$status = $_GET['status'] ?? null;

if ($status) {
    $stmt = $pdo->prepare("
        SELECT pp.*, u.email, u.username
        FROM pharmacy_profiles pp
        JOIN users u ON pp.user_id = u.id
        WHERE pp.status = ?
        ORDER BY pp.created_at DESC
    ");
    $stmt->execute([$status]);
} else {
    $stmt = $pdo->prepare("
        SELECT pp.*, u.email, u.username
        FROM pharmacy_profiles pp
        JOIN users u ON pp.user_id = u.id
        ORDER BY pp.created_at DESC
    ");
    $stmt->execute();
}

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode(["status" => "success", "count" => count($applications), "data" => $applications]);
