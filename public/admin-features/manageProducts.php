<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

$products = $conn->query("SELECT * FROM products");

// Pārbaude
$successMessage = '';
if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baltic Logi | Rediģēt Produktus</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-primary bg-opacity-5">
    <?php include '../../public/components/navbar.php'; ?>

    <div class="max-w-screen-xl mx-auto my-4">
        <a href="javascript:window.history.back();"
           class="text-danger hover:opacity-80 hover:underline"
        >
            Atpakaļ
        </a>
    </div>

    <!-- Paziņojums, pēc veiksmīgas rediģēšanas/dzēšanas/pievienošanas -->
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

    <div class="max-w-screen-xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-6">Produktu Rediģēšana</h1>
        <div class="relative overflow-x-auto shadow-3xl sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3"> ID </th>
                        <th scope="col" class="px-6 py-3"> Nosaukums </th>
                        <th scope="col" class="px-6 py-3"> Cena </th>
                        <th scope="col" class="px-6 py-3"> Kategorija </th>
                        <th scope="col" class="px-6 py-3"> Materiāls </th>
                        <th scope="col" class="px-6 py-3"> Dimensijjas </th>
                        <th scope="col" class="px-6 py-3"> Enerģijas reitings </th>
                        <th scope="col" class="px-6 py-3"> Darbības </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo $product['id']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </td>
                            <td class="px-6 py-4">
                                €<?php echo number_format($product['price'], 2); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($product['category_id']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($product['material']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($product['dimensions']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($product['energy_rating']); ?>
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($product)); ?>)"
                                    class="text-primary hover:underline"> Rediģ. </button>
                                <form method="POST" action="deleteProduct.php" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <button type="submit" class="text-danger hover:underline"> Dzēst </button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="flex my-4">
            <button onclick="openCreateModal()" class="bg-primary text-white px-4 py-2 rounded shadow hover:opacity-90">
                Veidot Produktu
            </button>
        </div>
    </div>

    <!-- Logs priekš produktu rediģēšanas -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Produkta rediģēšana</h2>
            <form method="POST" action="updateProduct.php">
                <input type="hidden" name="id" id="editProductId">
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700"> Nosaukums </label>
                    <input type="text" name="name" id="editName" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editPrice" class="block text-gray-700">Cena</label>
                    <input type="number" step="0.01" name="price" id="editPrice" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editCategory" class="block text-gray-700">Kategorija</label>
                    <input type="text" name="category_id" id="editCategory" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editDescription" class="block text-gray-700">Apraksts</label>
                    <textarea name="description" id="editDescription" class="w-full p-2 border rounded" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="editImagePath" class="block text-gray-700">Attēla saite</label>
                    <input type="text" name="image_path" id="editImagePath" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="editMaterial" class="block text-gray-700">Materiāls</label>
                    <input type="text" name="material" id="editMaterial" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="editDimensions" class="block text-gray-700">Dimensijas</label>
                    <input type="text" name="dimensions" id="editDimensions" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="editEnergyRating" class="block text-gray-700">Enerģijas Reitings</label>
                    <input type="text" name="energy_rating" id="editEnergyRating" class="w-full p-2 border rounded">
                </div>
                <div class="flex justify-end gap-3">
                    <button class="py-2 px-8 mt-2  bg-gray-500 text-white font-semibold rounded-md shadow-sm 
                                   hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]" 
                            type="button" 
                            onclick="closeEditModal()"
                    >
                        Atcelt
                    </button>
                    <button class="py-2 px-8 mt-2 bg-danger text-white font-semibold rounded-md shadow-sm 
                                   hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]"
                            type="submit"       
                    >
                        Saglabāt
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs priekš produktu veidošanas -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4"> Jauns produkts </h2>
            <form method="POST" action="createProduct.php">
                <div class="mb-4">
                    <label for="createName" class="block text-gray-700"> Nosaukums </label>
                    <input type="text" name="name" id="createName" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="createPrice" class="block text-gray-700"> Price </label>
                    <input type="number" step="0.01" name="price" id="createPrice" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="createCategory" class="block text-gray-700"> Kategorija </label>
                    <input type="text" name="category_id" id="createCategory" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="createDescription" class="block text-gray-700"> Apraksts </label>
                    <textarea name="description" id="createDescription" class="w-full p-2 border rounded" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="createImagePath" class="block text-gray-700">Attēla saite</label>
                    <input type="text" name="image_path" id="createImagePath" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="createMaterial" class="block text-gray-700"> Materiāls</label>
                    <input type="text" name="material" id="createMaterial" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="createDimensions" class="block text-gray-700">Dimensijas</label>
                    <input type="text" name="dimensions" id="createDimensions" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="createEnergyRating" class="block text-gray-700">Enerģijas Reitings</label>
                    <input type="text" name="energy_rating" id="createEnergyRating" class="w-full p-2 border rounded">
                </div>
                <div class="flex justify-end gap-3">
                    <button class="py-2 px-8 mt-2  bg-gray-500 text-white font-semibold rounded-md shadow-sm 
                                   hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]" 
                            type="button" 
                            onclick="closeCreateModal()"
                    >
                        Atcelt
                    </button>
                    <button class="py-2 px-8 mt-2 bg-danger text-white font-semibold rounded-md shadow-sm 
                                   hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]"
                            type="submit"       
                    >
                        Saglabāt
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<script>
        // Atver un piepilda ar datiem rediģēšanas logu
        function openEditModal(product) {
            document.getElementById('editProductId').value = product.id;
            document.getElementById('editName').value = product.name;
            document.getElementById('editPrice').value = product.price;
            document.getElementById('editCategory').value = product.category_id;
            document.getElementById('editDescription').value = product.description;
            document.getElementById('editImagePath').value = product.image_path;
            document.getElementById('editMaterial').value = product.material;
            document.getElementById('editDimensions').value = product.dimensions;
            document.getElementById('editEnergyRating').value = product.energy_rating;
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Aizver rediģ. logu
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Apstiprina dzēšanu
        function confirmDelete() {
            return confirm('Vai tiešām vēlaties dzēst produktu?');
        }

        // Atver "Jauns produkts" logu
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        // Aizver "Jauns produkts" logu
        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }
</script>
