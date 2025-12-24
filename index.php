<?php
session_start();
require_once 'config/db.php';

// Ambil data promo aktif
$promos = fetchAll("SELECT * FROM promos WHERE is_active = 1 AND end_date >= CURDATE() ORDER BY created_at DESC LIMIT 3");

// Ambil featured products
$featured = fetchAll("SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC LIMIT 6");

// Ambil settings
$settings = fetchAll("SELECT setting_key, setting_value FROM settings");
$config = [];
foreach ($settings as $setting) {
    $config[$setting['setting_key']] = $setting['setting_value'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config['clinic_name'] ?? 'Ensskin Beauty Glow'; ?> - Klinik Kecantikan Terpercaya</title>
    <meta name="description" content="Klinik kecantikan profesional dengan treatment modern dan hasil maksimal">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E40AF',
                        secondary: '#3B82F6',
                        accent: '#60A5FA'
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-white">

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative h-screen bg-gradient-to-br from-blue-50 to-white overflow-hidden">
    <div class="container mx-auto px-4 h-full flex items-center">
        <div class="grid md:grid-cols-2 gap-8 items-center w-full">
            <!-- Text Content -->
            <div class="space-y-6 z-10">
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 leading-tight">
                    Wujudkan Kulit<br>
                    <span class="text-primary">Impian Anda</span>
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    Treatment kecantikan profesional dengan teknologi terkini dan hasil yang terbukti. Raih kepercayaan diri Anda bersama Ensskin Beauty Glow.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#booking" class="px-8 py-4 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Booking Sekarang
                    </a>
                    <a href="catalog.php" class="px-8 py-4 bg-white text-primary border-2 border-primary rounded-full font-semibold hover:bg-blue-50 transition-all">
                        Lihat Treatment
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="flex gap-8 pt-8">
                    <div>
                        <div class="text-3xl font-bold text-primary">5000+</div>
                        <div class="text-gray-600">Pelanggan Puas</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-primary">15+</div>
                        <div class="text-gray-600">Treatment Unggulan</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-primary">98%</div>
                        <div class="text-gray-600">Kepuasan</div>
                    </div>
                </div>
            </div>
            
            <!-- Hero Image -->
            <div class="hidden md:block relative">
                <img src="assets/img/hero-banner-desktop.jpg" alt="Ensskin Beauty" class="rounded-3xl shadow-2xl object-cover w-full h-[600px]">
                <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-2xl shadow-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-white text-xl"></i>
                        </div>
                        <div>
                            <div class="font-bold text-2xl">4.9/5</div>
                            <div class="text-gray-600">Rating Google</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute top-20 right-20 w-72 h-72 bg-blue-100 rounded-full opacity-50 blur-3xl"></div>
    <div class="absolute bottom-20 left-20 w-96 h-96 bg-blue-50 rounded-full opacity-50 blur-3xl"></div>
</section>

<!-- Promo Section -->
<?php if (!empty($promos)): ?>
<section class="py-20 bg-gradient-to-r from-primary to-secondary">
    <div class="container mx-auto px-4">
        <div class="text-center text-white mb-12">
            <h2 class="text-4xl font-bold mb-4">Promo Spesial Bulan Ini</h2>
            <p class="text-xl opacity-90">Jangan lewatkan penawaran terbaik kami!</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($promos as $promo): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-xl hover:shadow-2xl transition-all transform hover:-translate-y-2">
                <?php if ($promo['banner_image']): ?>
                <img src="assets/img/<?php echo htmlspecialchars($promo['banner_image']); ?>" alt="<?php echo htmlspecialchars($promo['title']); ?>" class="w-full h-48 object-cover">
                <?php endif; ?>
                <div class="p-6">
                    <div class="inline-block px-4 py-1 bg-red-500 text-white text-sm font-semibold rounded-full mb-3">
                        Diskon <?php echo $promo['discount_percentage']; ?>%
                    </div>
                    <h3 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($promo['title']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($promo['description']); ?></p>
                    <div class="text-sm text-gray-500">
                        Berlaku hingga <?php echo date('d M Y', strtotime($promo['end_date'])); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="promo.php" class="inline-block px-8 py-4 bg-white text-primary rounded-full font-semibold hover:bg-gray-100 transition-all shadow-lg">
                Lihat Semua Promo
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Services/Products Section -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Treatment Unggulan Kami</h2>
            <p class="text-xl text-gray-600">Perawatan berkualitas dengan hasil yang nyata</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($featured as $product): ?>
            <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all group">
                <div class="relative overflow-hidden h-64">
                    <img src="assets/img/<?php echo htmlspecialchars($product['image']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute top-4 right-4 bg-primary text-white px-4 py-2 rounded-full font-semibold">
                        Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                    </div>
                </div>
                <div class="p-6">
                    <div class="text-xs text-primary font-semibold uppercase mb-2">
                        <?php echo ucfirst($product['category']); ?>
                    </div>
                    <h3 class="text-2xl font-bold mb-3"><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p class="text-gray-600 mb-4"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                    <a href="catalog.php?id=<?php echo $product['id']; ?>" class="inline-block w-full text-center px-6 py-3 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all">
                        Detail Treatment
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="catalog.php" class="inline-block px-8 py-4 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all shadow-lg">
                Lihat Semua Treatment
            </a>
        </div>
    </div>
</section>

<!-- Booking Section -->
<section id="booking" class="py-20 bg-white">
    <div class="container mx-auto px-4 max-w-4xl">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold mb-4">Reservasi Treatment</h2>
            <p class="text-xl text-gray-600">Isi form di bawah ini untuk booking jadwal Anda</p>
        </div>
        
        <div class="bg-gradient-to-br from-blue-50 to-white rounded-3xl shadow-xl p-8 md:p-12">
            <form id="bookingForm" action="process-booking.php" method="POST" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Nama Lengkap *</label>
                        <input type="text" name="customer_name" required 
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">No. WhatsApp *</label>
                        <input type="tel" name="customer_phone" required pattern="[0-9]{10,13}"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors">
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Email</label>
                    <input type="email" name="customer_email"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors">
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Pilih Treatment *</label>
                    <select name="treatment_type" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors">
                        <option value="">-- Pilih Treatment --</option>
                        <?php foreach ($featured as $product): ?>
                        <option value="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php echo htmlspecialchars($product['name']); ?> - Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Tanggal *</label>
                        <input type="date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Waktu *</label>
                        <select name="booking_time" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors">
                            <option value="">-- Pilih Waktu --</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                            <option value="17:00">17:00</option>
                            <option value="18:00">18:00</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Catatan Tambahan</label>
                    <textarea name="notes" rows="4"
                              class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-primary focus:outline-none transition-colors"
                              placeholder="Sampaikan keluhan atau permintaan khusus Anda..."></textarea>
                </div>
                
                <button type="submit" 
                        class="w-full px-8 py-4 bg-primary text-white rounded-full font-semibold text-lg hover:bg-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-calendar-check mr-2"></i> Konfirmasi Booking
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4">Apa Kata Mereka</h2>
            <p class="text-xl text-gray-600">Testimoni dari pelanggan setia Ensskin</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex gap-1 mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <p class="text-gray-700 mb-6 italic">"Treatment facial di Ensskin benar-benar mengubah kulitku! Wajah jadi lebih cerah dan halus. Pelayanannya juga sangat profesional."</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                        SA
                    </div>
                    <div>
                        <div class="font-bold">Siti Aminah</div>
                        <div class="text-sm text-gray-500">Jakarta</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex gap-1 mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <p class="text-gray-700 mb-6 italic">"Sudah 2 tahun langganan di Ensskin. Hasil treatment selalu memuaskan dan harga sangat reasonable. Highly recommended!"</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                        DP
                    </div>
                    <div>
                        <div class="font-bold">Dewi Putri</div>
                        <div class="text-sm text-gray-500">Bandung</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <div class="flex gap-1 mb-4">
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                    <i class="fas fa-star text-yellow-400"></i>
                </div>
                <p class="text-gray-700 mb-6 italic">"Tempat favorit untuk me time! Suasananya nyaman, staffnya ramah, dan yang paling penting hasilnya memuaskan."</p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary rounded-full flex items-center justify-center text-white font-bold">
                        LM
                    </div>
                    <div>
                        <div class="font-bold">Lisa Maharani</div>
                        <div class="text-sm text-gray-500">Surabaya</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/wa-button.php'; ?>

<script src="assets/js/main.js"></script>
</body>
</html>