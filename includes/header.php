<!-- Navigation Header -->
<header class="fixed w-full top-0 z-50 bg-white shadow-md">
    <nav class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="index.php" class="flex items-center">
                <img src="assets/img/logo-ensskin-main.png" alt="Ensskin Beauty Glow" class="h-12">
            </a>
            
            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-gray-700 hover:text-primary font-semibold transition-colors">Home</a>
                <a href="catalog.php" class="text-gray-700 hover:text-primary font-semibold transition-colors">Treatment</a>
                <a href="promo.php" class="text-gray-700 hover:text-primary font-semibold transition-colors">Promo</a>
                <a href="index.php#booking" class="text-gray-700 hover:text-primary font-semibold transition-colors">Booking</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Logged in user menu -->
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-gray-700 hover:text-primary font-semibold transition-colors">
                            <i class="fas fa-user-circle text-2xl"></i>
                            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            <?php if ($_SESSION['user_role'] == 'admin'): ?>
                            <a href="admin/dashboard.php" class="block px-4 py-3 text-gray-700 hover:bg-blue-50 rounded-t-lg">
                                <i class="fas fa-dashboard mr-2"></i> Dashboard
                            </a>
                            <?php endif; ?>
                            <a href="profile.php" class="block px-4 py-3 text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-user mr-2"></i> Profil Saya
                            </a>
                            <a href="my-bookings.php" class="block px-4 py-3 text-gray-700 hover:bg-blue-50">
                                <i class="fas fa-calendar mr-2"></i> Riwayat Booking
                            </a>
                            <a href="auth/logout.php" class="block px-4 py-3 text-red-600 hover:bg-red-50 rounded-b-lg">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Guest menu -->
                    <a href="auth/login.php" class="px-6 py-2 text-primary border-2 border-primary rounded-full font-semibold hover:bg-blue-50 transition-all">
                        Login
                    </a>
                    <a href="auth/register.php" class="px-6 py-2 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all shadow-lg">
                        Daftar
                    </a>
                <?php endif; ?>
            </div>
            
            <!-- Mobile Menu Button -->
            <button id="mobileMenuBtn" class="md:hidden text-gray-700 focus:outline-none">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
        
        <!-- Mobile Menu -->
        <div id="mobileMenu" class="md:hidden hidden pb-4">
            <div class="flex flex-col space-y-3">
                <a href="index.php" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">Home</a>
                <a href="catalog.php" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">Treatment</a>
                <a href="promo.php" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">Promo</a>
                <a href="index.php#booking" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">Booking</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if ($_SESSION['user_role'] == 'admin'): ?>
                    <a href="admin/dashboard.php" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">
                        <i class="fas fa-dashboard mr-2"></i> Dashboard
                    </a>
                    <?php endif; ?>
                    <a href="profile.php" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">
                        <i class="fas fa-user mr-2"></i> Profil
                    </a>
                    <a href="my-bookings.php" class="text-gray-700 hover:text-primary font-semibold py-2 border-b border-gray-100">
                        <i class="fas fa-calendar mr-2"></i> Riwayat
                    </a>
                    <a href="auth/logout.php" class="text-red-600 font-semibold py-2">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                <?php else: ?>
                    <a href="auth/login.php" class="text-primary font-semibold py-2">Login</a>
                    <a href="auth/register.php" class="px-6 py-2 bg-primary text-white rounded-full font-semibold text-center">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>

<!-- Spacer for fixed header -->
<div class="h-20"></div>

<script>
// Mobile menu toggle
document.getElementById('mobileMenuBtn').addEventListener('click', function() {
    const menu = document.getElementById('mobileMenu');
    const icon = this.querySelector('i');
    
    menu.classList.toggle('hidden');
    icon.classList.toggle('fa-bars');
    icon.classList.toggle('fa-times');
});
</script>