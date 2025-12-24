<?php
session_start();
require_once '../config/db.php';

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi!';
    } else {
        // Cari user di database
        $user = fetchOne("SELECT * FROM users WHERE email = ?", [$email], "s");
        
        if ($user && password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['fullname'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];
            
            // Redirect berdasarkan role
            if ($user['role'] == 'admin') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: ../index.php');
            }
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ensskin Beauty Glow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                        secondary: '#3B82F6'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-white min-h-screen flex items-center justify-center p-4">
    
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="../index.php">
                <img src="../assets/img/logo-ensskin-main.png" alt="Ensskin" class="h-16 mx-auto mb-4">
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Selamat Datang</h1>
            <p class="text-gray-600 mt-2">Masuk ke akun Anda</p>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white rounded-3xl shadow-xl p-8">
            <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
                    <p class="text-red-700"><?php echo htmlspecialchars($error); ?></p>
                </div>
            </div>
            <?php endif; ?>
            
            <form method="POST" action="" class="space-y-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-envelope mr-2 text-primary"></i> Email
                    </label>
                    <input type="email" name="email" required
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors"
                           placeholder="contoh@email.com"
                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">
                        <i class="fas fa-lock mr-2 text-primary"></i> Password
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors pr-12"
                               placeholder="Masukkan password">
                        <button type="button" onclick="togglePassword()" 
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="forgot-password.php" class="text-sm text-primary hover:underline">Lupa password?</a>
                </div>
                
                <button type="submit" 
                        class="w-full py-3 bg-primary text-white rounded-xl font-semibold hover:bg-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Belum punya akun? 
                    <a href="register.php" class="text-primary font-semibold hover:underline">Daftar sekarang</a>
                </p>
            </div>
            
            <div class="mt-6 pt-6 border-t border-gray-200">
                <a href="../index.php" class="flex items-center justify-center text-gray-600 hover:text-primary transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Home
                </a>
            </div>
        </div>
        
        <!-- Demo Account Info -->
        <div class="mt-6 p-4 bg-blue-50 rounded-xl text-sm">
            <p class="font-semibold text-primary mb-2">Demo Account:</p>
            <p class="text-gray-600">
                Admin: admin@ensskin.com / password123<br>
                <span class="text-xs text-gray-500">(Segera ganti password setelah login pertama kali!)</span>
            </p>
        </div>
    </div>

    <script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
    </script>
</body>
</html>