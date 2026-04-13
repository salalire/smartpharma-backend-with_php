<?php
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

if ($_SESSION['role'] !== 'admin') {
    echo json_encode(["status" => "error", "message" => "Access denied"]);
    exit;
}
