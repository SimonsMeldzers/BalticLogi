<?php
include '../src/connect_db.php';

$sql = 'SELECT * FROM products';
$result = $conn->query($sql);
$products = $result->fetch_array();
print_r($products[1]);
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../src/output.css" rel="stylesheet">
    <title>BalticLogi</title>
</head>
<body>
    <h1 class="bg-yellow-500">hi</h1>
</body>
</html>