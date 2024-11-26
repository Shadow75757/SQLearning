<?php
require 'db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$itemName = $data['item_name'] ?? null;
$quantity = $data['quantity'] ?? null;

if ($itemName && $quantity) {
    try {
        $stmt = $pdo->prepare("INSERT INTO inventory (item_name, quantity, inserted_by) VALUES (:item_name, :quantity, :inserted_by)");
        $stmt->execute([
            ':item_name' => $itemName,
            ':quantity' => $quantity,
            ':inserted_by' => $_SESSION['user']['username'] ?? 'Unknown',
        ]);
        echo json_encode(['status' => 'success', 'message' => 'Item added successfully.']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add item: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
