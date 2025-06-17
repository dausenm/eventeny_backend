<?php
require 'db_config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

file_put_contents("log.txt", file_get_contents("php://input"));

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode(["msg" => "Hello from POST"]);
} else {
    echo json_encode(["msg" => "Wrong method"]);
}

// Read the input JSON
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data['name'], $data['email'], $data['cart'])) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input"]);
    exit;
}

$name = $data['name'];
$email = $data['email'];
$cart = $data['cart'];

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Insert into purchases table
    $stmt = $pdo->prepare("INSERT INTO purchases (name, email, created_at) VALUES (?, ?, NOW())");
    $stmt->execute([$name, $email]);
    $purchase_id = $pdo->lastInsertId();

    // Insert each cart item into purchase_items
    $stmtItem = $pdo->prepare("INSERT INTO purchase_items (purchase_id, event_id, ticket_id, quantity, price) VALUES (?, ?, ?, ?, ?)");
    $stmtUpdateQty = $pdo->prepare("UPDATE tickets SET quantity_available = quantity_available - ? WHERE id = ? AND quantity_available >= ?");

    foreach ($cart as $item) {
        $event_id = $item['event_id'];
        $ticket_id = $item['ticket_id'];
        $quantity = $item['quantity'];
        $price = $item['price'];

        // Insert line item
        $stmtItem->execute([$purchase_id, $event_id, $ticket_id, $quantity, $price]);

        // Decrease available ticket quantity
        $stmtUpdateQty->execute([$quantity, $ticket_id, $quantity]);

        // If no rows updated, that means not enough tickets
        if ($stmtUpdateQty->rowCount() === 0) {
            throw new Exception("Not enough tickets available for ticket ID $ticket_id");
        }
    }

    // Commit transaction
    $pdo->commit();
    echo json_encode(["success" => true, "purchase_id" => $purchase_id]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(["error" => "Purchase failed", "details" => $e->getMessage()]);
    exit;
}
?>
