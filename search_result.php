<?php
include 'config.php';
include 'navbar.php';
$q = $_GET['q'] ?? '';
$products = [];

if ($q) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ? ORDER BY created_at DESC");
    $stmt->execute(["%$q%", "%$q%"]);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Pencarian: <?= htmlspecialchars($q) ?></title>
    <style>
        body { font-family: sans-serif; padding: 40px; text-align: center; }
        .product { margin: 20px auto; width: 300px; padding: 15px; border: 1px solid #ddd; border-radius: 10px; }
    </style>
</head>
<body>
    <h2>Hasil Pencarian untuk: "<?= htmlspecialchars($q) ?>"</h2>
    <?php if ($products): ?>
        <?php foreach ($products as $p): ?>
            <div class="product">
                <h3><?= htmlspecialchars($p['name']) ?></h3>
                <p><?= htmlspecialchars($p['description']) ?></p>
                <p><strong>Rp <?= number_format($p['price'], 0, ',', '.') ?></strong></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Produk tidak ditemukan.</p>
    <?php endif; ?>
</body>
</html>