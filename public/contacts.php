<?php
session_start();
include '../src/connect_db.php';

$contactDetails = [
    'Tel. num.' => '+371 2233 4455',
    'E-pasts' => 'support@balticlogi.lv',
    'Adrese' => '52 Taga iela, Rīga, Latvija LV-5252',
    'Darba laiks' => 'Pirm.-Piek.: 9:00 - 18.00',
];

$isLoggedIn = isset($_SESSION['user_id']);
$successMessage = $_SESSION['success_message'] ?? '';
$errorMessage = $_SESSION['error_message'] ?? '';

unset($_SESSION['success_message'], $_SESSION['error_message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baltic Logi | Kontakti</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body>
    <?php include '../public/components/navbar.php'; ?>

    <!-- Veiksmīgs paziņojums -->
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

    <!-- Kļūdas paziņojums -->
    <?php if (!empty($errorMessage)): ?>
        <div class="fixed top-[4.5rem] left-1/2 transform -translate-x-1/2 flex 
                    items-center w-full max-w-xs p-4 space-x-4 text-gray-500 
                    bg-white divide-x divide-gray-200 rounded-lg shadow-2xl 
                    dark:text-gray-400 dark:divide-gray-700 dark:bg-gray-800" 
            id="toast-error"
            role="alert"
        >
            <svg class="w-5 h-5 text-danger rotate-45" 
                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" 
                fill="none" viewBox="0 0 18 20"
            >
                <path stroke="currentColor" stroke-linecap="round" 
                    stroke-linejoin="round" stroke-width="2" 
                    d="m9 17 8 2L9 1 1 19l8-2Zm0 0V9"
                />
            </svg>
            <div class="pl-4 text-sm font-normal">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const toast = document.getElementById('toast-error');
                if (toast) toast.remove();
            }, 7000);
        </script>
    <?php endif; ?>


    <div class="max-w-screen-xl mx-auto mt-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h1 class="text-3xl font-bold mb-6">Kontaktu informācija</h1>
            <ul class="space-y-4">
                <?php foreach ($contactDetails as $key => $value): ?>
                    <li class="flex flex-col">
                        <span class="text-lg font-medium text-gray-700"><?php echo $key; ?>:</span>
                        <span class="text-gray-600"><?php echo $value; ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="bg-white p-6 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-6">Uzdodiet jautājumu vai veiciet pasūtījumu.</h2>
            <form method="POST" action="../src/sendContactForm.php">
                <div class="mb-4">
                    <label for="name" class="block text-gray-700 font-medium">Vārds</label>
                    <input type="text" id="name" name="name" 
                           value="<?php echo $isLoggedIn ? htmlspecialchars($_SESSION['user_name']) : ''; ?>" 
                           class="w-full p-2 border rounded" 
                           <?php echo $isLoggedIn ? 'readonly' : ''; ?> 
                           placeholder="Jūsu vārds"
                    >
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium">E-pasts</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?php echo $isLoggedIn ? htmlspecialchars($_SESSION['user_email'] ?? '') : ''; ?>"
                        class="w-full p-2 border rounded"
                        <?php echo $isLoggedIn ? 'readonly' : ''; ?>
                        placeholder="Jūsu E-pasts"
                    />
                </div>
                <div class="mb-4">
                    <label for="message" class="block text-gray-700 font-medium">Ziņa</label>
                    <textarea id="message" name="message" rows="5" class="w-full p-2 border rounded" 
                              placeholder="Jūsu ziņa" 
                              <?php echo $isLoggedIn ? '' : 'disabled'; ?>></textarea>
                </div>
                <button type="submit" 
                        class="py-3 px-8 bg-danger text-white font-semibold rounded-md shadow-md hover:opacity-90
                        <?php echo $isLoggedIn ? '' : 'opacity-50 cursor-not-allowed'; ?>" 
                        <?php echo $isLoggedIn ? '' : 'disabled'; ?>>
                    <?php echo $isLoggedIn ? 'Sūtīt Ziņu' : 'Pierakstieties lai Sūtīt Ziņu'; ?>
                </button>
            </form>
        </div>
    </div>
    <?php include '../public/components/footer.php'; ?>
</body>
</html>
