<?php
include '../../src/connect_db.php';

if (isset($_GET['order_id'])) {
    $order_id = intval($_GET['order_id']);
    $query = "
        SELECT 
            oi.id AS order_item_id,
            oi.quantity,
            p.name AS product_name,
            p.price AS product_price
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $order_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $items = [];

        while ($row = $result->fetch_assoc()) {
            $items[] = [
                'order_item_id' => $row['order_item_id'],
                'quantity' => $row['quantity'],
                'product_name' => $row['product_name'],
                'product_price' => (float) $row['product_price']
            ];            
        }

        echo json_encode(['success' => true, 'items' => $items]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Neizdevās izgūt produktus.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Slikts pieprasījums.']);
}
?>
