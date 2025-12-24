<?php
session_start();
require_once '../config/db.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Ambil statistik
$stats = [];
$stats['total_users'] = fetchOne("SELECT COUNT(*) as count FROM users WHERE role='customer'")['count'];
$stats['total_bookings'] = fetchOne("SELECT COUNT(*) as count FROM bookings")['count'];
$stats['pending_bookings'] = fetchOne("SELECT COUNT(*) as count FROM bookings WHERE status='pending'")['count'];
$stats['total_products'] = fetchOne("SELECT COUNT(*) as count FROM products WHERE is_active=1")['count'];

// Ambil booking terbaru
$recent_bookings = fetchAll("SELECT * FROM bookings ORDER BY created_at DESC LIMIT 10");

// Ambil promo aktif
$active_promos = fetchAll("SELECT * FROM promos WHERE is_active=1 AND end_date >= CURDATE()");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Ensskin</title>
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
<body class="bg-gray-50">

<!-- Sidebar -->
<div class="fixed left-0 top-0 h-full w-64 bg-gradient-to-b from-primary to-blue-900 text-white p-6 z-40">
    <div class="mb-8">
        <img src="../assets/img/logo-ensskin-white.png" alt="Ensskin" class="h-12 mb-2">
        <p class="text-sm text-blue-200">Admin Panel</p>
    </div>
    
    <nav class="space-y-2">
        <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 bg-white/10 rounded-lg font-semibold">
            <i class="fas fa-dashboard"></i>
            <span>Dashboard</span>
        </a>
        <a href="manage-products.php" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-colors">
            <i class="fas fa-box"></i>
            <span>Kelola Produk</span>
        </a>
        <a href="manage-promos.php" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-colors">
            <i class="fas fa-tags"></i>
            <span>Kelola Promo</span>
        </a>
        <a href="manage-bookings.php" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-colors">
            <i class="fas fa-calendar"></i>
            <span>Kelola Booking</span>
        </a>
        <a href="manage-users.php" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-colors">
            <i class="fas fa-users"></i>
            <span>Kelola User</span>
        </a>
        <a href="settings.php" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-colors">
            <i class="fas fa-cog"></i>
            <span>Pengaturan</span>
        </a>
    </nav>
    
    <div class="absolute bottom-6 left-6 right-6">
        <a href="../index.php" class="flex items-center gap-3 px-4 py-3 hover:bg-white/10 rounded-lg transition-colors mb-2">
            <i class="fas fa-home"></i>
            <span>Ke Website</span>
        </a>
        <a href="../auth/logout.php" class="flex items-center gap-3 px-4 py-3 bg-red-500/20 hover:bg-red-500/30 rounded-lg transition-colors">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</div>

<!-- Main Content -->
<div class="ml-64 p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
        <p class="text-gray-600">Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-primary text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900"><?php echo $stats['total_users']; ?></span>
            </div>
            <p class="text-gray-600 font-semibold">Total Customer</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-check text-green-600 text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900"><?php echo $stats['total_bookings']; ?></span>
            </div>
            <p class="text-gray-600 font-semibold">Total Booking</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900"><?php echo $stats['pending_bookings']; ?></span>
            </div>
            <p class="text-gray-600 font-semibold">Pending Booking</p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gray-900"><?php echo $stats['total_products']; ?></span>
            </div>
            <p class="text-gray-600 font-semibold">Produk Aktif</p>
        </div>
    </div>
    
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Booking Terbaru</h2>
                <a href="manage-bookings.php" class="text-primary hover:underline text-sm font-semibold">Lihat Semua</a>
            </div>
            
            <div class="space-y-4">
                <?php if (empty($recent_bookings)): ?>
                    <p class="text-gray-500 text-center py-8">Belum ada booking</p>
                <?php else: ?>
                    <?php foreach (array_slice($recent_bookings, 0, 5) as $booking): ?>
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl">
                        <div>
                            <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($booking['customer_name']); ?></p>
                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($booking['treatment_type']); ?></p>
                            <p class="text-xs text-gray-500"><?php echo date('d M Y, H:i', strtotime($booking['created_at'])); ?></p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            <?php 
                            echo $booking['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                ($booking['status'] == 'confirmed' ? 'bg-green-100 text-green-800' : 
                                ($booking['status'] == 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'));
                            ?>">
                            <?php echo ucfirst($booking['status']); ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Active Promos -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Promo Aktif</h2>
                <a href="manage-promos.php" class="text-primary hover:underline text-sm font-semibold">Kelola</a>
            </div>
            
            <div class="space-y-4">
                <?php if (empty($active_promos)): ?>
                    <p class="text-gray-500 text-center py-8">Belum ada promo aktif</p>
                <?php else: ?>
                    <?php foreach ($active_promos as $promo): ?>
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-white rounded-xl border-l-4 border-primary">
                        <h3 class="font-semibold text-gray-900 mb-1"><?php echo htmlspecialchars($promo['title']); ?></h3>
                        <p class="text-sm text-gray-600 mb-2"><?php echo htmlspecialchars(substr($promo['description'], 0, 80)); ?>...</p>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-500">
                                Sampai <?php echo date('d M Y', strtotime($promo['end_date'])); ?>
                            </span>
                            <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full">
                                <?php echo $promo['discount_percentage']; ?>% OFF
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="mt-8 bg-gradient-to-r from-primary to-blue-600 rounded-2xl shadow-lg p-8 text-white">
        <h2 class="text-2xl font-bold mb-6">Aksi Cepat</h2>
        <div class="grid md:grid-cols-4 gap-4">
            <a href="manage-products.php?action=add" class="bg-white/10 hover:bg-white/20 rounded-xl p-4 text-center transition-colors">
                <i class="fas fa-plus-circle text-3xl mb-2"></i>
                <p class="font-semibold">Tambah Produk</p>
            </a>
            <a href="manage-promos.php?action=add" class="bg-white/10 hover:bg-white/20 rounded-xl p-4 text-center transition-colors">
                <i class="fas fa-tag text-3xl mb-2"></i>
                <p class="font-semibold">Buat Promo</p>
            </a>
            <a href="manage-bookings.php?status=pending" class="bg-white/10 hover:bg-white/20 rounded-xl p-4 text-center transition-colors">
                <i class="fas fa-tasks text-3xl mb-2"></i>
                <p class="font-semibold">Cek Booking</p>
            </a>
            <a href="settings.php" class="bg-white/10 hover:bg-white/20 rounded-xl p-4 text-center transition-colors">
                <i class="fas fa-cog text-3xl mb-2"></i>
                <p class="font-semibold">Pengaturan</p>
            </a>
        </div>
    </div>
</div>

</body>
</html>