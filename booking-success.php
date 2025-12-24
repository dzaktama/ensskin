<?php
session_start();

if (!isset($_SESSION['booking_success'])) {
    header('Location: index.php');
    exit;
}

$booking_id = $_SESSION['booking_id'];
$wa_admin_url = $_SESSION['wa_admin_url'];
$wa_customer_url = $_SESSION['wa_customer_url'];

// Clear session
unset($_SESSION['booking_success']);
unset($_SESSION['booking_id']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Berhasil - Ensskin Beauty Glow</title>
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
    
    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 text-center">
            <!-- Success Icon -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                <i class="fas fa-check text-green-500 text-5xl"></i>
            </div>
            
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Booking Berhasil!</h1>
            
            <p class="text-xl text-gray-600 mb-2">
                Terima kasih telah melakukan booking di Ensskin Beauty Glow
            </p>
            
            <div class="inline-block px-6 py-3 bg-blue-50 rounded-xl mb-8">
                <p class="text-sm text-gray-600">ID Booking Anda:</p>
                <p class="text-3xl font-bold text-primary">#<?php echo str_pad($booking_id, 5, '0', STR_PAD_LEFT); ?></p>
            </div>
            
            <div class="bg-gradient-to-r from-blue-50 to-white p-6 rounded-2xl mb-8">
                <h3 class="font-bold text-lg mb-4 text-gray-900">Langkah Selanjutnya:</h3>
                <div class="space-y-3 text-left">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                            1
                        </div>
                        <p class="text-gray-700 pt-1">Tim kami akan menghubungi Anda melalui WhatsApp untuk konfirmasi booking</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                            2
                        </div>
                        <p class="text-gray-700 pt-1">Anda akan menerima detail lengkap treatment dan instruksi persiapan</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-primary text-white rounded-full flex items-center justify-center flex-shrink-0 font-bold">
                            3
                        </div>
                        <p class="text-gray-700 pt-1">Datang sesuai jadwal yang telah dikonfirmasi</p>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="<?php echo htmlspecialchars($wa_customer_url); ?>" target="_blank"
                   class="inline-flex items-center justify-center px-8 py-4 bg-green-500 text-white rounded-full font-semibold hover:bg-green-600 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fab fa-whatsapp text-2xl mr-3"></i>
                    <span>Lihat Konfirmasi di WA</span>
                </a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                <a href="my-bookings.php"
                   class="inline-flex items-center justify-center px-8 py-4 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    <i class="fas fa-calendar-alt mr-3"></i>
                    <span>Lihat Riwayat Booking</span>
                </a>
                <?php endif; ?>
            </div>
            
            <!-- Back to Home -->
            <div class="pt-6 border-t border-gray-200">
                <a href="index.php" class="inline-flex items-center text-gray-600 hover:text-primary transition-colors font-semibold">
                    <i class="fas fa-home mr-2"></i>
                    Kembali ke Beranda
                </a>
            </div>
            
            <!-- Contact Info -->
            <div class="mt-8 p-4 bg-blue-50 rounded-xl">
                <p class="text-sm text-gray-600 mb-2">Butuh bantuan?</p>
                <a href="https://wa.me/6282112568941" target="_blank" class="text-primary font-semibold hover:underline">
                    Hubungi Customer Service
                </a>
            </div>
        </div>
    </div>
    
    <style>
    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }
    
    .animate-bounce {
        animation: bounce 1s ease-in-out 3;
    }
    </style>
    
    <script>
    // Auto open WhatsApp notification after 3 seconds
    setTimeout(function() {
        if (confirm('Buka WhatsApp untuk melihat konfirmasi booking?')) {
            window.open('<?php echo htmlspecialchars($wa_customer_url); ?>', '_blank');
        }
    }, 3000);
    </script>
</body>
</html>