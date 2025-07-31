<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: auth.php");
    exit();
}

include '../config.php';

$category = $_GET['category'] ?? '';

if (empty($category)) {
    echo "<p>Tidak ada kategori yang dipilih.</p>";
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE category = ?");
$stmt->execute([$category]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h3>Produk dalam Kategori: <?php echo htmlspecialchars($category); ?></h3>

<?php if (count($products) > 0): ?>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td>Rp <?php echo number_format($product['price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($product['stock']); ?></td>
                    <td>
                        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Gambar Produk" style="width: 80px; height: auto;">
                    </td>
                    <td>
<a href="#" class="action-btn" onclick="openEditModal('<?php echo $product['id']; ?>', '<?php echo addslashes($product['name']); ?>', '<?php echo addslashes($product['description']); ?>', '<?php echo $product['price']; ?>', '<?php echo $product['stock']; ?>', '<?php echo $product['category']; ?>', '<?php echo $product['image']; ?>')">Edit</a>                        <a href="hapus_produk.php?id=<?php echo $product['id']; ?>" class="action-btn" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Tidak ada produk dalam kategori ini.</p>
<?php endif; ?>