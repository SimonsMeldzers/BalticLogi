<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

$users = $conn->query("SELECT * FROM users");

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
    <title>Baltic Logi | Rediģēt lietotāju</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body class="bg-primary bg-opacity-5">
    <?php include '../../public/components/navbar.php'; ?>
    <div class="max-w-screen-xl mx-auto my-4">
        <a href="javascript:window.history.back();"
           class="text-danger hover:opacity-80"
        >
            Atpakaļ
        </a>
    </div>

    <!-- Paziņojums, pēc veiksmīgas rediģēšanas -->
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
        <h1 class="text-3xl font-bold text-center mb-6">Lietotāju rediģēšana</h1>
        <div class="relative overflow-x-auto shadow-3xl sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3"> ID </th>
                        <th scope="col" class="px-6 py-3"> Vārds </th>
                        <th scope="col" class="px-6 py-3"> E-pasts </th>
                        <th scope="col" class="px-6 py-3"> Loma </th>
                        <th scope="col" class="px-6 py-3"> Darbība </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo $user['id']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($user['name']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($user['email']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($user['role']); ?>
                            </td>
                            <td class="px-6 py-4 flex space-x-2">
                                <button onclick="openEditModal(<?php echo htmlspecialchars(json_encode($user)); ?>)"
                                        class="text-primary hover:underline"
                                    >
                                        Rediģ.
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Logs priekš lietotāja rediģēšanas -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-xl font-semibold mb-4">Edit User</h2>
            <form method="POST" action="updateUser.php">
                <input type="hidden" name="id" id="editUserId">
                <div class="mb-4">
                    <label for="editName" class="block text-gray-700"> Vārds </label>
                    <input type="text" name="name" id="editName" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editEmail" class="block text-gray-700"> E-pasts </label>
                    <input type="email" name="email" id="editEmail" class="w-full p-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="editRole" class="block text-gray-700"> Loma </label>
                    <select name="role" id="editRole" class="w-full p-2 border rounded" required>
                        <option value="user"> User </option>
                        <option value="admin"> Admin </option>
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

    <script>
        // Atver logu un pievieno datus
        function openEditModal(user) {
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editName').value = user.name;
            document.getElementById('editEmail').value = user.email;
            document.getElementById('editRole').value = user.role;
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Aizver logu ciet
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</body>
</html>
