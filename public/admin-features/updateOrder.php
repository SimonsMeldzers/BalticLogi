<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $total_price = floatval($_POST['total_price']);
    $status = trim($_POST['status']);

    $allowedStatuses = ['pending', 'cancelled', 'completed'];
    if (!in_array(strtolower($status), $allowedStatuses)) {
        die("Invalid status.");
    }

    $stmt = $conn->prepare("UPDATE orders SET total_price = ?, status = ? WHERE id = ?");
    $stmt->bind_param("dsi", $total_price, $status, $id);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['success_message'] = 'Pasūtījums veiksmīgi atjaunots!';
        header("Location: manageOrders.php");
        exit;
    } else {
        die("Neizdevās atjaunot pasūtījumu.");
    }
}
?>
