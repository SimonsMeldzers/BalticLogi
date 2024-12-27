<?php
include '../../src/connect_db.php';
include '../../src/isAdmin.php';

$messages = $conn->query("
    SELECT cm.*, u.name AS user_name, u.email AS user_email 
    FROM contact_messages cm
    LEFT JOIN users u ON cm.user_id = u.id
    ORDER BY cm.created_at DESC
");

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
    <title>Baltic Logi | Rediģēt Ziņas</title>
    <link href="../../src/output.css" rel="stylesheet">
</head>
<body>
    <?php include '../../public/components/navbar.php'; ?>

    <div class="max-w-screen-xl mx-auto my-4">
        <a href="javascript:window.history.back();"
           class="text-danger hover:opacity-80 hover:underline"
        >
            Atpakaļ
        </a>
    </div>

    <?php if (!empty($successMessage)): ?>
        <div class="fixed top-[4.5rem] left-1/2 transform -translate-x-1/2 flex 
                            items-center w-full max-w-xs p-4 space-x-4 text-gray-500 
                            bg-white divide-x divide-gray-200 rounded-lg shadow-2xl 
                            dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800" id="toast-success" role="alert">
            <svg class="w-5 h-5 text-green-700 rotate-45" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 18 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="m9 17 8 2L9 1 1 19l8-2Zm0 0V9" />
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

    <div class="max-w-screen-xl mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center mb-6">Lietotāju vēstuļu apskats</h1>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">ID</th>
                        <th scope="col" class="px-6 py-3">Lietotājs</th>
                        <th scope="col" class="px-6 py-3">E-pasts</th>
                        <th scope="col" class="px-6 py-3">Ziņa</th>
                        <th scope="col" class="px-6 py-3">Datums</th>
                        <th scope="col" class="px-6 py-3">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($message = $messages->fetch_assoc()): ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                            <td class="px-6 py-4"><?php echo $message['id']; ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($message['user_name'] ?? 'Guest'); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($message['user_email'] ?? 'N/A'); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($message['message']); ?></td>
                            <td class="px-6 py-4"><?php echo htmlspecialchars($message['created_at']); ?></td>
                            <td class="px-6 py-4">
                                <form method="POST" action="deleteMessage.php" class="inline" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                    <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                                    <button type="submit" class="text-danger hover:underline">Dzēst</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
