<?php
session_start();
require 'includes/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password_plain = $_POST['password'];
    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);
    $role = 'warga';

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email sudah digunakan!";
    } else {
        $query = "INSERT INTO users (nama, email, password, role) 
                  VALUES ('$nama', '$email', '$password_hash', '$role')";
        if (mysqli_query($conn, $query)) {
            $success = "Registrasi berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded shadow-md p-8 max-w-md w-full">
        <h2 class="text-2xl font-bold text-center text-blue-600 mb-6">DAFTAR AKUN RT DIGITAL</h2>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4"><?= $success ?></div>
        <?php elseif (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1" for="nama">Nama</label>
                <input type="text" id="nama" name="nama" required
                       class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-1" for="email">Email</label>
                <input type="email" id="email" name="email" required
                       class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-1" for="password">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition">
                Daftar
            </button>
        </form>

        <div class="text-center mt-4">
            <a href="login.php" class="text-blue-500 text-sm hover:underline">Sudah punya akun? Login</a>
        </div>
    </div>
</body>
</html>
