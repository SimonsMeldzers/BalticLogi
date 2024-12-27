<?php
session_start();
include '../src/connect_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    if (!$user_id || empty($name) || empty($email) || empty($message)) {
        $_SESSION['error_message'] = 'All fields are required.';
        header('Location: /public/contacts.php');
        exit;
    }

    // Insert the message into the database
    $stmt = $conn->prepare("INSERT INTO contact_messages (user_id, name, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $email, $message);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Your message has been sent successfully!';
    } else {
        $_SESSION['error_message'] = 'Failed to send your message. Please try again.';
    }

    header('Location: /BalticLogi/public/contacts.php');
    exit;
}
