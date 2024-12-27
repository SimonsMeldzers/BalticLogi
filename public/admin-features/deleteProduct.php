<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['success_message'] = 'Produkts veiksmīgi dzēsts!!';
        header("Location: manageProducts.php");
        exit;
    } else {
        die("Neizdevās dzēst pasūtījumu.");
    }
}
?>
