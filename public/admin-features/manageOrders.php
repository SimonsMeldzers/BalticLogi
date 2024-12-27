<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

// Fetch all orders with dynamically calculated total price
$query = "
    SELECT 
        o.id, 
        o.user_id, 
        u.name AS user_name, 
        o.status, 
        o.created_at, 
        SUM(oi.quantity * p.price) AS calculated_total_price
    FROM orders o
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    LEFT JOIN users u ON o.user_id = u.id
    GROUP BY o.id
";

$orders = $conn->query($query);

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
    <title>Baltic Logi | Rediģēt Pasūt.</title>
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

    <div class="max-w-screen-xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-6">Pasūtījumu rediģēšana</h1>

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

        <!-- Tabula ar pasūtījumiem -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3"> ID </th>
                        <th scope="col" class="px-6 py-3"> Lietotājs </th>
                        <th scope="col" class="px-6 py-3"> Kopā </th>
                        <th scope="col" class="px-6 py-3"> Status </th>
                        <th scope="col" class="px-6 py-3"> Izveidots </th>
                        <th scope="col" class="px-6 py-3"> Darbības </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $orders->fetch_assoc()): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo $order['id']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($order['user_name']); ?>
                            </td>
                            <td class="px-6 py-4">
                                €<?php echo number_format($order['calculated_total_price'], 2); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($order['status']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($order['created_at']); ?>
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($order)); ?>)"
                                        class="text-primary hover:underline">Rediģ.</button>
                                <button onclick="openViewModal(<?php echo $order['id']; ?>)"
                                        class="text-secondary hover:underline">Apsk.</button>
                                <form method="POST" action="deleteOrder.php" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <button type="submit" class="text-danger hover:underline">Dzēst</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <div class="my-4">
            <button onclick="openCreateModal()" class="bg-primary text-white px-4 py-2 rounded shadow hover:opacity-90">
                Veidot pasūtījumu
            </button>
        </div>        
    </div>

    <!-- Visi logi -->

    <!-- "Rediģēt pasūtījumu" logs -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Rediģēt pasūtījumu</h2>
            <form method="POST" action="updateOrder.php">
                <input type="hidden" name="id" id="editOrderId">
                <div class="mb-4">
                    <label for="editTotalPrice" class="block text-gray-700">Kopā</label>
                    <input type="number" step="0.01" name="total_price" id="editTotalPrice" class="w-full p-2 border rounded">
                </div>
                <div class="mb-4">
                    <label for="editStatus" class="block text-gray-700">Status</label>
                    <select name="status" id="editStatus" class="w-full p-2 border rounded">
                        <option value="pending">Procesā</option>
                        <option value="cancelled">Atcelts</option>
                        <option value="completed">Pabeigts</option>
                    </select>
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


    <!-- "Jauns pasūtījums" logs -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Jauns pasūtījums</h2>
            <form method="POST" action="createOrder.php">
                <div class="mb-4">
                    <label for="createUserId" class="block text-gray-700">Lietotāja ID</label>
                    <input type="number" name="user_id" id="createUserId" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="createStatus" class="block text-gray-700">Status</label>
                    <input type="text" name="status" id="createStatus" class="w-full p-2 border rounded" required>
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

    <!-- "Apskatīt" logs -->
    <div id="viewModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-[32rem]">
            <h2 class="text-xl font-semibold mb-4">Pasūtītie produkti</h2>
            <div id="orderItemsContainer" class="mb-4">
                <!-- Pasūtītās preces caur js -->
            </div>
            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold my-2">Pievienot</h3>
                <form id="addProductForm">
                    <input type="hidden" id="orderIdForAdd" name="order_id">
                    <div class="mb-4">
                        <label for="addProduct" class="block text-gray-700">Produkts</label>
                        <select id="addProduct" name="product_id" class="w-full p-2 border rounded" required>
                            <option value="" disabled selected>Izvēlieties produktus</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="addQuantity" class="block text-gray-700">Daudzums</label>
                        <input type="number" id="addQuantity" name="quantity" class="w-full p-2 border rounded" min="1" required>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button class="py-2 px-8 mt-2  bg-gray-500 text-white font-semibold rounded-md shadow-sm 
                                    hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]" 
                                type="button" 
                                onclick="closeViewModal()"
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
    </div>
</body>
</html>

<script>
        function openEditModal(order) {
            document.getElementById('editOrderId').value = order.id;
            document.getElementById('editTotalPrice').value = order.total_price;

            const statusSelect = document.getElementById('editStatus');
            Array.from(statusSelect.options).forEach(option => {
                option.selected = option.value === order.status.toLowerCase();
            });

            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function confirmDelete() {
            return confirm('Vai tiešām vēlaties dzēst pasūtījumu?');
        }

        function openViewModal(orderId) {
            const modal = document.getElementById('viewModal');
            const container = document.getElementById('orderItemsContainer');
            const productDropdown = document.getElementById('addProduct');
            const orderIdInput = document.getElementById('orderIdForAdd');

            container.innerHTML = '<p class="text-gray-500">Ielādē...</p>';
            productDropdown.innerHTML = '<option value="" disabled selected>Ielādē...</option>';

            orderIdInput.value = orderId;

            // Saņemam pasūtītos produktus
            fetch(`getOrderItems.php?order_id=${orderId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const items = data.items;
                        container.innerHTML = `
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Produkts</th>
                                        <th class="px-6 py-3">Daudzums</th>
                                        <th class="px-6 py-3">Cena</th>
                                        <th class="px-6 py-3">Kopā</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${items.map(item => `
                                        <tr>
                                            <td class="px-6 py-4">${item.product_name}</td>
                                            <td class="px-6 py-4">${item.quantity}</td>
                                            <td class="px-6 py-4">€${parseFloat(item.product_price).toFixed(2)}</td>
                                            <td class="px-6 py-4">€${(item.quantity * parseFloat(item.product_price)).toFixed(2)}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        `;
                    } else {
                        container.innerHTML = '<p class="text-red-500">Neizdevās ielādēt pasūtītos produktus</p>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    container.innerHTML = '<p class="text-red-500">Notika kļūda meklējot produktus</p>';
                });

            fetch(`getProducts.php`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        productDropdown.innerHTML = `
                            <option value="" disabled selected>Izvēlieties produktu</option>
                            ${data.products.map(product => `
                                <option value="${product.id}">${product.name} (€${parseFloat(product.price).toFixed(2)})</option>
                            `).join('')}
                        `;
                    } else {
                        productDropdown.innerHTML = '<option value="" disabled>Neizdevās ielādēt produktus</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    productDropdown.innerHTML = '<option value="" disabled>Notika kļūda</option>';
                });

            modal.classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        document.getElementById('addProductForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('addOrderItem.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    openViewModal(document.getElementById('orderIdForAdd').value); 
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Notika kļūda mēģinot pievienot produktu.');
            });
    });

</script>
