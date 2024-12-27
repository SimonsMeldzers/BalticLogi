<?php
session_start();
include '../src/connect_db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['success_message'] = 'Grozs ir tukš nav iespējams veikt pasūtījumu!';
    header('Location: cart.php');
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    $_SESSION['success_message'] = 'Nepieciešams būt pierakstītam lai veikt pasūtījumu!';
    header('Location: ../public/cart.php');
    exit;
}

$total_price = 0;
$order_items = [];
foreach ($_SESSION['cart'] as $product_id => $details) {
    $stmt = $conn->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $price = $row['price'];
        $quantity = $details['quantity'];
        $total_price += $price * $quantity;

        $order_items[] = [
            'product_id' => $product_id,
            'quantity' => $quantity,
            'price' => $price
        ];
    }
}

// pievieno datus orders tabulā
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, created_at, updated_at) VALUES (?, ?, 'pending', NOW(), NOW())");
$stmt->bind_param("id", $user_id, $total_price);
$stmt->execute();
$order_id = $stmt->insert_id;

// pievieno datus order_items tabulā
foreach ($order_items as $item) {
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

unset($_SESSION['cart']);
$_SESSION['success_message'] = 'Pasūtījums veiksmīgi pievienots!';
header('Location: /BalticLogi/public/cart.php');
exit;
?>
