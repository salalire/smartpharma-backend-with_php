<?php
require __DIR__ . '/../configuration/database.php';
$stmt = $pdo->query("SELECT id, name, image FROM products LIMIT 10");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['id'] . ' | ' . $row['name'] . ' | ' . $row['image'] . "\n";
}
