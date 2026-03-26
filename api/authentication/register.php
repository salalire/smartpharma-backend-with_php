<?php
header("Content-Type: application/json");

require __DIR__ . '/../../configuration/database.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    empty($data['username']) ||
    empty($data['email']) ||
    empty($data['password'])
) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$username = $data['username'];
$email = $data['email'];
$password = $data['password'];
$phone = $data['phone'] ?? null;

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email"]);
    exit;
}

// Check if email exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    echo json_encode(["status" => "error", "message" => "Email already exists"]);
    exit;
}

// Hash password 🔐
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert user
$stmt = $pdo->prepare("
    INSERT INTO users (username, email, password, phone)
    VALUES (?, ?, ?, ?)
");

$stmt->execute([$username, $email, $hashedPassword, $phone]);

echo json_encode([
    "status" => "success",
    "message" => "User registered successfully"
]);
?>
