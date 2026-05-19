<?php
error_reporting(0);
session_start();
require __DIR__ . '/../../configuration/cors.php';
header("Content-Type: application/json");
require __DIR__ . '/../../configuration/database.php';

$data = json_decode(file_get_contents("php://input"), true);

$step = $data['step'] ?? '';

// ── STEP 1: Verify email exists ──────────────────────────────
if ($step === 'verify') {
    $email = trim($data['email'] ?? '');
    if (!$email) {
        echo json_encode(["status" => "error", "message" => "Email is required"]);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "No account found with that email"]);
        exit;
    }

    // Store in session so step 2 knows who to update
    $_SESSION['reset_user_id'] = $user['id'];
    $_SESSION['reset_email']   = $email;

    echo json_encode(["status" => "success", "message" => "Email verified", "username" => $user['username']]);
    exit;
}

// ── STEP 2: Set new password ─────────────────────────────────
if ($step === 'reset') {
    if (!isset($_SESSION['reset_user_id'])) {
        echo json_encode(["status" => "error", "message" => "Session expired. Start again."]);
        exit;
    }

    $password = $data['password'] ?? '';
    if (strlen($password) < 6) {
        echo json_encode(["status" => "error", "message" => "Password must be at least 6 characters"]);
        exit;
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt   = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$hashed, $_SESSION['reset_user_id']]);

    // Clear reset session
    unset($_SESSION['reset_user_id'], $_SESSION['reset_email']);

    echo json_encode(["status" => "success", "message" => "Password updated successfully"]);
    exit;
}

echo json_encode(["status" => "error", "message" => "Invalid request"]);
