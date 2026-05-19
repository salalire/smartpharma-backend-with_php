<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if (!isset($_FILES['image'])) {
    echo json_encode(["status" => "error", "message" => "No file uploaded"]);
    exit;
}

$file     = $_FILES['image'];
$allowed  = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
$maxSize  = 2 * 1024 * 1024; // 2MB

if (!in_array($file['type'], $allowed)) {
    echo json_encode(["status" => "error", "message" => "Only JPG, PNG, WEBP, GIF allowed"]);
    exit;
}

if ($file['size'] > $maxSize) {
    echo json_encode(["status" => "error", "message" => "File too large. Max 2MB"]);
    exit;
}

// Save to uploads folder
$uploadDir = __DIR__ . '/../../../../smartpharma-frontend/smartpharma/media/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('product_') . '.' . $ext;
$dest     = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $dest)) {
    // Return URL accessible from frontend
    $url = 'http://127.0.0.1/sp/smartpharma-frontend/smartpharma/media/uploads/' . $filename;
    echo json_encode(["status" => "success", "url" => $url]);
} else {
    echo json_encode(["status" => "error", "message" => "Upload failed"]);
}
