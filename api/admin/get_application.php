<?php
header("Content-Type: application/json");

require __DIR__ . '/../../configuration/database.php';

// Get all applications with user info
$stmt = $pdo->prepare("
    SELECT pp.*, u.email 
    FROM pharmacy_profiles pp
    JOIN users u ON pp.user_id = u.id
    ORDER BY pp.created_at DESC
");

$stmt->execute();
$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $applications
]);
?>