<?php
session_start();
require 'includes/config.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];

            // update login terakhir
            mysqli_query($conn, "UPDATE users SET last_login = NOW() WHERE id = " . $user['id']);

            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: admin_dashboard.php');
            }

            exit;
        } else {
            $error = 'Password salah!';
        }
    } else {
        $error = 'Email tidak ditemukan!';
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded shadow-md p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">LOGIN RT DIGITAL</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 border border-red-400 p-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="email" required
                       class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                Masuk
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="register.php" class="text-blue-500 text-sm hover:underline">Belum punya akun? Daftar</a>
        </div>
    </div>
</body>
</html>
