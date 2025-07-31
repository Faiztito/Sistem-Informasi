<?php
session_start();
include 'config.php'; // tambahkan ini

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$product_id = $data['id'] ?? null;

if (!$product_id) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
    exit;
}

// Hapus dari session
if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
    $_SESSION['cart'] = array_filter($_SESSION['cart']);
}

// Jika user login, hapus dari database
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
}

// Hitung ulang total
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal + 15000;

echo json_encode([
    'status' => 'success',
    'subtotal' => number_format($subtotal, 0, ',', '.'),
    'total' => number_format($total, 0, ',', '.'),
    'cart_count' => count($_SESSION['cart'])
]);