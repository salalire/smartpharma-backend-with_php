<?php

require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");

session_start(); 

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

require __DIR__ . '/../../configuration/database.php';

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT id, username, email, phone, role 
    FROM users WHERE id = ?
");

$stmt->execute([$user_id]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($user);