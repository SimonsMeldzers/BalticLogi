<?php
include '../src/connect_db.php';

$sql = 'SELECT * FROM products';
$result = $conn->query($sql);
$products = $result->fetch_array();
// print_r($products[1]);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/output.css" rel="stylesheet">
    <link href="../src/css/style.css" rel="stylesheet">
    <title>BalticLogi</title>
</head>
<body>
    <?php include '../public/components/navbar.php'; ?>
    <?php include '../public/components/banner.php'; ?>
    <?php include '../public/components/featuredProducts.php'; ?>

    <?php
        $conn->close();
    ?>
</body>
</html>