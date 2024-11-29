<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    try {
        $stmt = $pdo->prepare("UPDATE inventory SET item_name = :item_name, quantity = :quantity WHERE id = :id");
        $stmt->execute([':item_name' => $item_name, ':quantity' => $quantity, ':id' => $id]);

        echo json_encode(['status' => 'success', 'message' => 'Item updated successfully']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update item: ' . $e->getMessage()]);
    }
}
