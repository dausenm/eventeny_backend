<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'db_config.php';

try {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
    $events = $stmt->fetchAll();
    echo json_encode($events);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Failed to fetch events"]);
    exit;
}
