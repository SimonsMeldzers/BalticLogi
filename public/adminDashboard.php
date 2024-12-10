<?php
include '../src/connect_db.php';
include '../src/isAdmin.php';

$user_count = $conn->query("SELECT COUNT(*) AS count FROM users")->fetch_assoc()['count'];
$product_count = $conn->query("SELECT COUNT(*) AS count FROM products")->fetch_assoc()['count'];
$order_count = $conn->query("SELECT COUNT(*) AS count FROM orders")->fetch_assoc()['count']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baltic Logi | Admin</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-primary bg-opacity-5">
    <?php include '../public/components/navbar.php'; ?>
    <div class="max-w-screen-xl mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center">Admina panelis</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
            <!-- Lietotaji -->
            <div class="p-6 bg-white rounded shadow">
                <h2 class="text-xl font-bold">Lietotāji</h2>
                <p>Kopā: <?php echo $user_count; ?></p>
                <a href="admin-features/manageUsers.php" class="text-primary hover:underline">Rediģēt lietotājus</a>
            </div>
            <!-- Produkti -->
            <div class="p-6 bg-white rounded shadow">
                <h2 class="text-xl font-bold">Produkti</h2>
                <p>Kopā: <?php echo $product_count; ?></p>
                <a href="admin-features/manageProducts.php" class="text-primary hover:underline">Rediģēt Produktus</a>
            </div>
            <!-- Pasutijumi -->
            <div class="p-6 bg-white rounded shadow">
                <h2 class="text-xl font-bold">Pasūtījumi</h2>
                <p>Kopā: <?php echo $order_count; ?></p>
                <a href="admin-features/manageOrders.php" class="text-primary hover:underline">Rediģēt Pasūtījumus</a>
            </div>
        </div>
    </div>
</body>
</html>
