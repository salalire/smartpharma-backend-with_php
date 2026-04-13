<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "none"]);
    exit;
}

require __DIR__ . '/../../configuration/database.php';

$stmt = $pdo->prepare("
    SELECT status FROM pharmacy_profiles 
    WHERE user_id = ?
    ORDER BY created_at DESC
    LIMIT 1
");
$stmt->execute([$_SESSION['user_id']]);
$app = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode(["status" => $app ? $app['status'] : "none"]);
