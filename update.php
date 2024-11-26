<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare("UPDATE inventory SET item_name = ?, quantity = ? WHERE id = ?");
    $stmt->execute([$item_name, $quantity, $id]);
    header("Location: index.php");
    exit;
}
