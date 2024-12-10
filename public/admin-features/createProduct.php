<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $category_id = intval($_POST['category_id']);
    $description = trim($_POST['description']);
    $image_path = trim($_POST['image_path']);
    $material = trim($_POST['material']);
    $dimensions = trim($_POST['dimensions']);
    $energy_rating = trim($_POST['energy_rating']);

    $stmt = $conn->prepare("INSERT INTO products (name, price, category_id, description, image_path, material, dimensions, energy_rating) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdisssss", $name, $price, $category_id, $description, $image_path, $material, $dimensions, $energy_rating);

    if ($stmt->execute()) {
        session_start();
        $_SESSION['success_message'] = 'Product created successfully!';
        header("Location: manageProducts.php");
        exit;
    } else {
        die("Failed to create product.");
    }
}
?>
