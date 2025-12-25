<?php
session_start();
require_once 'config/db.php';
$promos = fetchAll("SELECT * FROM promos WHERE is_active = 1 AND end_date >= CURDATE()");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><title>Promo - Ensskin Beauty Glow</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-gray-50">
    <?php include 'includes/header.php'; ?>
    <div class="container mx-auto px-4 py-12">
        <h1 class="text-4xl font-bold text-center mb-12 text-blue-900">Promo Spesial Ensskin</h1>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach($promos as $p): ?>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-blue-100">
                <img src="assets/img/<?= $p['banner_image'] ?>" class="w-full h-48 object-cover">
                <div class="p-6">
                    <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold"><?= $p['discount_percentage'] ?>% OFF</span>
                    <h3 class="text-xl font-bold mt-3"><?= $p['title'] ?></h3>
                    <p class="text-gray-600 my-4"><?= $p['description'] ?></p>
                    <p class="text-sm text-gray-400">Berlaku s/d: <?= date('d M Y', strtotime($p['end_date'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>