<?php
header("Content-Type: application/json");

require __DIR__ . '/../middleware/admin_only.php';
require __DIR__ . '/../../configuration/cors.php';
require __DIR__ . '/../../configuration/database.php';

// Optional filter
$status = $_GET['status'] ?? null;

if ($status) {
    $stmt = $pdo->prepare("
        SELECT pp.*, u.email 
        FROM pharmacy_profiles pp
        JOIN users u ON pp.user_id = u.id
        WHERE pp.status = ?
        ORDER BY pp.created_at DESC
    ");
    $stmt->execute([$status]);
} else {
    $stmt = $pdo->prepare("
        SELECT pp.*, u.email 
        FROM pharmacy_profiles pp
        JOIN users u ON pp.user_id = u.id
        ORDER BY pp.created_at DESC
    ");
    $stmt->execute();
}

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "count" => count($applications),
    "data" => $applications
]);
?>