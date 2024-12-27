<?php
session_start();
include '../src/connect_db.php';

$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $ids = implode(',', $product_ids);

    $query = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($query);

    while ($row = $result->fetch_assoc()) {
        $product_id = $row['id'];
        $quantity = $_SESSION['cart'][$product_id]['quantity'];
        $price = $row['price'];
        $total_price += $quantity * $price;

        $cart_items[] = [
            'id' => $product_id,
            'name' => $row['name'],
            'price' => $price,
            'quantity' => $quantity,
            'total' => $quantity * $price,
        ];
    }
}

$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baltic Logi | Grozs</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body>
    <?php include '../public/components/navbar.php'; ?>

    <div class="max-w-screen-xl mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Jūsu grozs</h1>

        <?php if (!empty($successMessage)): ?>
            <div class="fixed top-[4.5rem] left-1/2 transform -translate-x-1/2 flex 
                        items-center w-full max-w-xs p-4 space-x-4 text-gray-500 
                        bg-white divide-x divide-gray-200 rounded-lg shadow-2xl 
                        dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800" 
                id="toast-success"
                role="alert"
            >
                <svg class="w-5 h-5 text-green-700 rotate-45" 
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                    fill="none" viewBox="0 0 18 20"
                >
                    <path stroke="currentColor" stroke-linecap="round" 
                        stroke-linejoin="round" stroke-width="2" 
                        d="m9 17 8 2L9 1 1 19l8-2Zm0 0V9"
                    />
                </svg>
                <div class="pl-4 text-sm font-normal">
                    <?php echo htmlspecialchars($successMessage); ?>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    const toast = document.getElementById('toast-success');
                    if (toast) toast.remove();
                }, 7000);
            </script>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <p>Jūsu grozs ir tukš.</p>
        <?php else: ?>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Produkts</th>
                            <th scope="col" class="px-6 py-3">Cena</th>
                            <th scope="col" class="px-6 py-3">Daudzums</th>
                            <th scope="col" class="px-6 py-3">Kopā</th>
                            <th scope="col" class="px-6 py-3">Darbības</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </td>
                                <td class="px-6 py-4">
                                    €<?php echo number_format($item['price'], 2); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="../src/updateCart.php" class="flex items-center space-x-2">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                               min="1" class="w-16 p-2 border rounded">
                                        <button class="py-2 px-3 bg-danger text-white font-semibold rounded-md shadow-sm 
                                                       hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]"
                                                type="submit"
                                        >      
                                            <img width="24px" height="24px" src="https://img.icons8.com/?size=100&id=59872&format=png&color=FFFFFF" alt="">
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    €<?php echo number_format($item['total'], 2); ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="POST" action="../src/removeFromCart.php" onsubmit="return confirm('Vai tiešām vēlaties dzēst produktu?')">
                                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="text-danger hover:underline">Dzēst</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between items-center mt-6">
                <h2 class="text-xl font-bold text-danger">Kopā: €<?php echo number_format($total_price, 2); ?></h2>
                <form method="POST" action="../src/makeOrder.php">
                    <button class="py-3 px-5 bg-danger text-white font-semibold rounded-md shadow-sm 
                                   hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]"
                            type="submit"
                    >
                    Veikt pasūtījumu
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
