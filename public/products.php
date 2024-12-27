<?php
session_start();
include '../src/connect_db.php';

$query = "SELECT * FROM products";
$products = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baltic Logi | Logi</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body>
    <?php include '../public/components/navbar.php'; ?>

    <div class="max-w-screen-xl mx-auto mt-10 mb-10">
        <h1 class="text-3xl font-bold text-center mb-8">Visi logi</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php while ($product = $products->fetch_assoc()): ?>
                <a href="/BalticLogi/public/productSlug.php?id=<?php echo $product['id']; ?>" 
                   class="border rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow flex flex-col">
                    <?php if (!empty($product['image_path'])): ?>
                        <img class="w-full h-48 lg:h-64 object-cover rounded-t" 
                             src="<?php echo htmlspecialchars($product['image_path']); ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <?php else: ?>
                        <div class="w-full h-48 lg:h-64 bg-gray-300 flex items-center justify-center rounded-t">
                            <span class="text-gray-500">Nav attēla</span>
                        </div>
                    <?php endif; ?>

                    <div class="p-4">
                        <div class="flex-grow text-danger">
                            <h2 class="text-lg font-semibold text-gray-800 mb-2"><?php echo htmlspecialchars($product['name']); ?></h2>
                            <p class=" text-base font-bold mb-2">€<?php echo number_format($product['price'], 2); ?></p>
                        </div>

                        <ul class="text-xs text-gray-500 mt-auto">
                            <li><strong>Materiāls:</strong> <?php echo htmlspecialchars($product['material']); ?></li>
                            <li><strong>Dimensijas:</strong> <?php echo htmlspecialchars($product['dimensions']); ?></li>
                            <li><strong>Enerģijas reitings:</strong> <?php echo htmlspecialchars($product['energy_rating']); ?></li>
                        </ul>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
