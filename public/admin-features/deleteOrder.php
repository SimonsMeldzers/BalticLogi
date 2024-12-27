<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);

    $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['success_message'] = 'Pasūtījums veiksmīgi dzēsts.!';
        header("Location: manageOrders.php");
        exit;
    } else {
        die("Neizdevās dzēst pasūtījumu.");
    }
}
?>
