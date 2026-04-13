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

$username = trim($data['username'] ?? "");
$phone    = trim($data['phone']    ?? "");

if (empty($username)) {
    echo json_encode(["status" => "error", "message" => "Username cannot be empty"]);
    exit;
}

$stmt = $pdo->prepare("UPDATE users SET username = ?, phone = ? WHERE id = ?");
$stmt->execute([$username, $phone ?: null, $_SESSION['user_id']]);

echo json_encode(["status" => "success", "message" => "Profile updated"]);
