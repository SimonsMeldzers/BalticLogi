<?php
session_start();
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_id = intval($_POST['message_id']);

    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->bind_param("i", $message_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Ziņa veiksmīgi dzēsta.";
    } else {
        $_SESSION['error_message'] = "Neizdevās dzēst ziņojumu.";
    }

    header("Location: /BalticLogi/public/admin-features/manageMessages.php");
    exit;
}
