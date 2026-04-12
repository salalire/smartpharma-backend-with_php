<?php

header("Content-Type: application/json");

require __DIR__ . '/../../configuration/cors.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];


require __DIR__ . '/../../configuration/database.php';

try {

    
    $stmt = $pdo->prepare("
        SELECT status 
        FROM pharmacy_profiles 
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 1
    ");

    $stmt->execute([$user_id]);

    $application = $stmt->fetch(PDO::FETCH_ASSOC);

   
    if (!$application) {
        echo json_encode([
            "status" => "none"
        ]);
        exit;
    }

    
    echo json_encode([
        "status" => $application['status']
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Server error"
    ]);
}