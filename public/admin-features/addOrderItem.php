<?php
include '../../src/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        echo json_encode(['success' => false, 'message' => 'Nepareizs daudzums.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(['success' => false, 'message' => 'Pasūtījums nav atrasts.']);
        exit;
    }

    $product = $result->fetch_assoc();
    $price = $product['price'];

    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Produkts pievienots pasūtījumam.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Neizdevās pievienot produktu pie pasūtījuma.']);
    }
}
?>
