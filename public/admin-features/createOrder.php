<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = intval($_POST['user_id']);
    $total_price = floatval($_POST['total_price']);
    $status = trim($_POST['status']);

    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $total_price, $status);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['success_message'] = 'Pasūtījums veiksmīgi izveidots!';
        header("Location: manageOrders.php");
        exit;
    } else {
        die("Neizdevās izveidot pasūtījumu.");
    }
}
?>
