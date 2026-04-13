<?php
// Session cookie settings — must be set BEFORE session_start()
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_path', '/');
ini_set('session.cookie_domain', '');

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin && preg_match('/^http:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/', $origin)) {
    header("Access-Control-Allow-Origin: " . $origin);
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
