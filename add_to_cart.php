<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

if (!isset($_POST['id']) || !isset($_POST['quantity'])) {
    echo json_encode(['success' => false, 'message' => 'Missing product ID or quantity']);
    exit;
}

$product_id = intval($_POST['id']);
$quantity = intval($_POST['quantity']);

if ($product_id <= 0 || $quantity <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid product ID or quantity']);
    exit;
}

// Cek stok produk (opsional, untuk keamanan tambahan)
$stmt = $pdo->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();
if (!$product || $quantity > $product['stock']) {
     echo json_encode(['success' => false, 'message' => 'Jumlah melebihi stok yang tersedia.']);
     exit;
}

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$product_id])) {
    // Jika sudah ada, tambahkan kuantitas
    $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    // Optional: Validasi ulang stok total di keranjang
    if($_SESSION['cart'][$product_id]['quantity'] > $product['stock']) {
         $_SESSION['cart'][$product_id]['quantity'] = $product['stock'];
    }
} else {
    // Jika belum ada, tambahkan item baru
    // Anda perlu mengambil data produk lagi untuk menyimpannya di session
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $product_data = $stmt->fetch();
    if ($product_data) {
        $_SESSION['cart'][$product_id] = [
            'id' => $product_data['id'],
            'name' => $product_data['name'],
            'price' => $product_data['price'],
            'image' => $product_data['image'],
            'stock' => $product_data['stock'], // Simpan stok untuk validasi di cart
            'quantity' => $quantity
        ];
    } else {
        echo json_encode(['success' => false, 'message' => 'Produk tidak ditemukan']);
        exit;
    }
}

echo json_encode(['success' => true, 'message' => 'Produk berhasil ditambahkan ke keranjang']);
?>