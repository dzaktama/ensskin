<?php
session_start();
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) { header('Location: ../index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = fetchOne("SELECT id FROM users WHERE email = ?", [$email], "s");
    if ($check) {
        $error = 'Email sudah terdaftar!';
    } else {
        executeQuery("INSERT INTO users (fullname, email, phone, password) VALUES (?, ?, ?, ?)", 
                    [$fullname, $email, $phone, $password], "ssss");
        header('Location: login.php?msg=success');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Daftar - Ensskin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-3xl shadow-xl w-full max-w-md">
        <h1 class="text-3xl font-bold mb-6 text-center text-blue-900">Buat Akun</h1>
        <?php if($error): ?><p class="text-red-500 mb-4"><?= $error ?></p><?php endif; ?>
        <form method="POST" class="space-y-4">
            <input type="text" name="fullname" placeholder="Nama Lengkap" required class="w-full p-3 border rounded-xl">
            <input type="email" name="email" placeholder="Email" required class="w-full p-3 border rounded-xl">
            <input type="tel" name="phone" placeholder="No. WhatsApp" required class="w-full p-3 border rounded-xl">
            <input type="password" name="password" placeholder="Password" required class="w-full p-3 border rounded-xl">
            <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700">Daftar</button>
        </form>
        <p class="mt-4 text-center">Sudah punya akun? <a href="login.php" class="text-blue-600 font-bold">Login</a></p>
    </div>
</body>
</html>