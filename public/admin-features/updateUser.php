<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    if (empty($name) || empty($email) || empty($role)) {
        die("All fields are required.");
    }

    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $name, $email, $role, $id);

    if ($stmt->execute()) {
        // Paziņojums kad veiksmigi rediģēja
        session_start();
        $_SESSION['success_message'] = 'Lietotājs veiksmīgi rediģēts!';
        header("Location: manageUsers.php");
        exit;
    } else {
        die("Neizdevās rediģēt lietotāju.");
    }
}
?>