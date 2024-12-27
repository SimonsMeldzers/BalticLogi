<?php
session_start();
include '../src/connect_db.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (empty($email) || empty($password)) {
            $errors[] = "E-pasts un parole ir obligāti.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email']; 
                    $_SESSION['user_role'] = $user['role'];
                    header("Location: index.php");
                    exit;
                } else {
                    $errors[] = "Nepareiza parole.";
                }
            } else {
                $errors[] = "Nav lietotāju ar šo e-pastu.";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baltic Logi | Login</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body>
    <?php include '../public/components/navbar.php'; ?>
    <div class="flex flex-wrap-reverse h-[calc(100vh-64px)] bg-secondary bg-opacity-5 lg:flex-nowrap lg:flex-row">
        <div class="bg-[url(../images/window_illustration.png)] bg-center bg-cover w-full lg:w-5/12">
        </div>
        <div class="w-full flex justify-center lg:w-7/12">
            <div class="shadow-3xl m-auto bg-white p-6 md:p-12 w-[20rem] sm:w-[24rem] md:w-[32rem]">
                <div>
                    <h2 class="text-2xl font-semibold mb-4">Pierakstīties</h2>
                    <form method="POST">
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 font-medium">E-pasts</label>
                            <input type="email" name="email" id="email" required class="w-full p-2 border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 font-medium">Parole</label>
                            <input type="password" name="password" id="password" required class="w-full p-2 border rounded">
                        </div>
                        <p class="mt-2 text-gray-600">Vēl neesat piereģistrējies?</p>
                        <a class="text-primary underline mb-2" href="/BalticLogi/public/register.php">Reģistrēties</a>
                        <button class="py-3 mt-2 w-full bg-danger text-white font-semibold rounded-md shadow-sm 
                                       hover:drop-shadow-md hover:bg-opacity-90 active:scale-[1.01]"
                                type="submit" 
                                name="login"
                        >
                            Apstiprināt
                        </button>
                        <?php if (!empty($success)): ?>
                            <div class="bg-green-100 text-green-700 p-4 mb-4 rounded">
                                <p><?php echo htmlspecialchars($success); ?></p>
                            </div>
                            <?php $success = ''; ?>
                        <?php endif; ?>

                        <?php if (!empty($errors)): ?>
                            <div class="bg-red-100 text-red-700 px-4 py-3 my-4 rounded-md shadow-sm">
                                <?php foreach ($errors as $error): ?>
                                    <p><?php echo htmlspecialchars($error); ?></p>
                                <?php endforeach; ?>
                            </div>
                            <?php $errors = []; ?>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
