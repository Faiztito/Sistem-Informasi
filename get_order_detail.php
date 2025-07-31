<?php
session_start();
if (!isset($_SESSION['admin'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Akses ditolak.']);
    exit();
}

include '../config.php';
$order_id = $_GET['id'] ?? null;

if (!$order_id || !is_numeric($order_id)) {
    echo json_encode(['error' => 'ID tidak valid.']);
    exit();
}

try {
    // Ambil total dan info pesanan
    $order = $pdo->prepare("SELECT total_price FROM orders WHERE id = ?");
    $order->execute([$order_id]);
    $orderData = $order->fetch();

    if (!$orderData) {
        echo json_encode(['error' => 'Pesanan tidak ditemukan.']);
        exit();
    }

    // Ambil item pesanan
    $items = $pdo->prepare("
        SELECT oi.quantity, oi.price, p.name, p.image 
        FROM order_items oi 
        JOIN products p ON oi.product_id = p.id 
        WHERE oi.order_id = ?
    ");
    $items->execute([$order_id]);
    $itemsData = $items->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'total' => $orderData['total_price'],
        'items' => $itemsData
    ]);
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['error' => 'Terjadi kesalahan.']);
}