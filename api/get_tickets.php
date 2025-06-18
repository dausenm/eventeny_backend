<?php
require 'db_config.php';

header('Content-Type: application/json');

if (!isset($_GET['event_id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing event_id parameter"]);
    exit;
}

$event_id = $_GET['event_id'];

try {
    $stmt = $pdo->prepare("SELECT id, event_id, type, price, quantity_available FROM tickets WHERE event_id = ?");
    $stmt->execute([$event_id]);
    $tickets = $stmt->fetchAll();

    echo json_encode($tickets);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Database query failed"]);
}
