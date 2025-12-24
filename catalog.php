<?php
session_start();
require_once 'config/db.php';

// Filter berdasarkan kategori
$category_filter = isset($_GET['cat']) ? $_GET['cat'] : '';
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$query = "SELECT * FROM products WHERE is_active = 1";
$params = [];
$types = "";

if (!empty($category_filter)) {
    $query .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

if (!empty($search)) {
    $query .= " AND (name LIKE ? OR description LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss";
}

$query .= " ORDER BY created_at DESC";

$products = fetchAll($query, $params, $types);

// Ambil kategori untuk filter
$categories = [
    'facial' => 'Facial Treatment',
    'body' => 'Body Treatment',
    'laser' => 'Laser Treatment',
    'skincare' => 'Skincare Products'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Treatment - Ensskin Beauty Glow</title>
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
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-primary to-blue-900 text-white py-20">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-5xl font-bold mb-4">Katalog Treatment</h1>
        <p class="text-xl text-blue-100 mb-8">Temukan treatment yang tepat untuk kebutuhan kecantikan Anda</p>
        
        <!-- Search Bar -->
        <form method="GET" class="max-w-2xl mx-auto">
            <div class="flex gap-2">
                <input type="text" name="search" placeholder="Cari treatment..." value="<?php echo htmlspecialchars($search); ?>"
                       class="flex-1 px-6 py-4 rounded-full text-gray-900 focus:outline-none focus:ring-4 focus:ring-blue-300">
                <button type="submit" class="px-8 py-4 bg-white text-primary rounded-full font-semibold hover:bg-blue-50 transition-all">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</section>

<!-- Filter & Products -->
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filter -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Filter Kategori</h3>
                    
                    <div class="space-y-2">
                        <a href="catalog.php" 
                           class="block px-4 py-3 rounded-xl transition-colors <?php echo empty($category_filter) ? 'bg-primary text-white' : 'hover:bg-gray-50'; ?>">
                            <i class="fas fa-th mr-2"></i> Semua Treatment
                        </a>
                        
                        <?php foreach ($categories as $cat_key => $cat_name): ?>
                        <a href="catalog.php?cat=<?php echo $cat_key; ?>" 
                           class="block px-4 py-3 rounded-xl transition-colors <?php echo $category_filter == $cat_key ? 'bg-primary text-white' : 'hover:bg-gray-50'; ?>">
                            <i class="fas fa-tag mr-2"></i> <?php echo $cat_name; ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    
                    <?php if (!empty($category_filter) || !empty($search)): ?>
                    <a href="catalog.php" class="block mt-4 text-center text-primary hover:underline text-sm font-semibold">
                        Reset Filter
                    </a>
                    <?php endif; ?>
                </div>
            </aside>
            
            <!-- Products Grid -->
            <div class="flex-1">
                <?php if (!empty($search) || !empty($category_filter)): ?>
                <div class="mb-6">
                    <p class="text-gray-600">
                        Menampilkan <?php echo count($products); ?> treatment
                        <?php if (!empty($category_filter)): ?>
                            dalam kategori <strong><?php echo $categories[$category_filter]; ?></strong>
                        <?php endif; ?>
                        <?php if (!empty($search)): ?>
                            untuk pencarian <strong>"<?php echo htmlspecialchars($search); ?>"</strong>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if (empty($products)): ?>
                    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                        <i class="fas fa-search text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Tidak ada treatment ditemukan</h3>
                        <p class="text-gray-600 mb-6">Coba kata kunci atau kategori lain</p>
                        <a href="catalog.php" class="inline-block px-6 py-3 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all">
                            Lihat Semua Treatment
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all card-hover">
                            <div class="relative h-64 overflow-hidden">
                                <img src="assets/img/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>"
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute top-4 left-4 px-3 py-1 bg-white rounded-full text-xs font-semibold text-primary">
                                    <?php echo ucfirst($product['category']); ?>
                                </div>
                                <div class="absolute top-4 right-4 px-4 py-2 bg-primary text-white rounded-full font-bold shadow-lg">
                                    Rp <?php echo number_format($product['price'], 0, ',', '.'); ?>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="text-gray-600 mb-4 line-clamp-3"><?php echo htmlspecialchars($product['description']); ?></p>
                                
                                <div class="flex gap-3">
                                    <a href="index.php#booking" 
                                       class="flex-1 text-center px-4 py-3 bg-primary text-white rounded-xl font-semibold hover:bg-blue-800 transition-all">
                                        <i class="fas fa-calendar-check mr-2"></i> Booking
                                    </a>
                                    <button onclick="showProductDetail(<?php echo htmlspecialchars(json_encode($product)); ?>)"
                                            class="px-4 py-3 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all">
                                        <i class="fas fa-info-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Modal Product Detail -->
<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <h2 id="modalTitle" class="text-3xl font-bold text-gray-900"></h2>
                <button onclick="closeProductModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
            
            <div id="modalImage" class="mb-6 rounded-2xl overflow-hidden"></div>
            
            <div class="mb-6">
                <span id="modalCategory" class="inline-block px-4 py-2 bg-blue-100 text-primary rounded-full text-sm font-semibold mb-4"></span>
                <div id="modalPrice" class="text-3xl font-bold text-primary mb-4"></div>
                <p id="modalDescription" class="text-gray-700 leading-relaxed"></p>
            </div>
            
            <a href="index.php#booking" class="block w-full text-center px-8 py-4 bg-primary text-white rounded-full font-semibold hover:bg-blue-800 transition-all shadow-lg">
                <i class="fas fa-calendar-check mr-2"></i> Booking Treatment Ini
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/wa-button.php'; ?>

<script src="assets/js/main.js"></script>
<script>
function showProductDetail(product) {
    document.getElementById('modalTitle').textContent = product.name;
    document.getElementById('modalCategory').textContent = product.category.toUpperCase();
    document.getElementById('modalPrice').textContent = 'Rp ' + parseInt(product.price).toLocaleString('id-ID');
    document.getElementById('modalDescription').textContent = product.description;
    document.getElementById('modalImage').innerHTML = `<img src="assets/img/${product.image}" alt="${product.name}" class="w-full h-80 object-cover">`;
    
    document.getElementById('productModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeProductModal();
    }
});

// Close modal on background click
document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});
</script>

</body>
</html>