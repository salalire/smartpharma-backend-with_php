<?php
header("Content-Type: application/json");

require __DIR__ . '/../../configuration/database.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (
    empty($data['email']) ||
    empty($data['password'])
) {
    echo json_encode([
        "status" => "error",
        "message" => "Email and password are required"
    ]);
    exit;
}

$email = $data['email'];
$password = $data['password'];

// Check if user exists
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}
// Verify password

if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password"
    ]);
    exit;
}
// Login successful
session_start();

// Save user info in session
$_SESSION['user_id'] = $user['id'];
$_SESSION['role'] = $user['role'];

echo json_encode([
    "status" => "success",
    "message" => "Login successful"
]);
?>
