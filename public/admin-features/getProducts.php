<?php
include '../../src/connect_db.php';

header('Content-Type: application/json');

$query = "SELECT id, name, price FROM products";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $products = [];
    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => (float)$row['price']
        ];
    }
    echo json_encode(['success' => true, 'products' => $products]);
} else {
    echo json_encode(['success' => false, 'products' => []]);
}
?>
