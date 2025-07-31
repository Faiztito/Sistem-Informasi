<?php
session_start();
include 'config.php';
include 'navbar.php';

// Pastikan user sudah login
if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Ambil semua pesanan user dari database beserta itemnya
try {
    // Ambil data pesanan
    $stmt = $pdo->prepare("
        SELECT o.id, o.created_at, o.total_price, o.status
        FROM orders o
        WHERE o.user_id = ?
        ORDER BY o.created_at DESC
    ");
    $stmt->execute([$user_id]);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Untuk setiap pesanan, ambil item-itemnya
    foreach ($orders as &$order) {
        $stmt_items = $pdo->prepare("
            SELECT oi.*, p.name, p.image 
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?
        ");
        $stmt_items->execute([$order['id']]);
        $order['items'] = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
        $order['item_count'] = count($order['items']);
    }
    unset($order); // Hapus reference terakhir
    
} catch (PDOException $e) {
    $error = "Gagal memuat riwayat pesanan: " . $e->getMessage();
    $orders = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - UThrift</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B5A2B;
            --secondary: #F5F0E6;
            --dark: #3E2723;
            --light: #FFF8F0;
            --text: #333333;
            --border: #E0D5C8;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            --success: #4CAF50;
            --processing: #2196F3;
            --cancelled: #F44336;
        }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .order-history-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 5%;
        }
        
        .page-title {
            color: var(--dark);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            text-align: center;
        }
        
        .order-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .order-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: transform 0.3s;
        }
        
        .order-card:hover {
            transform: translateY(-3px);
        }
        
        .order-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
        }
        
        .order-id {
            font-weight: 600;
            color: var(--primary);
        }
        
        .order-date {
            color: #666;
        }
        
        .order-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .status-completed {
            background-color: #E8F5E9;
            color: var(--success);
        }
        
        .status-processing {
            background-color: #E3F2FD;
            color: var(--processing);
        }
        
        .status-cancelled {
            background-color: #FFEBEE;
            color: var(--cancelled);
        }
        
        .order-summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .order-items-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .order-items-count i {
            color: var(--primary);
        }
        
        .order-total {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .order-items-container {
            margin-top: 1rem;
        }
        
        .items-title {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .items-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .item-card {
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border);
            border-radius: 6px;
            overflow: hidden;
        }
        
        .item-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
        }
        
        .item-info {
            padding: 0.5rem;
        }
        
        .item-name {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .item-qty-price {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #666;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem;
            background-color: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #ccc;
            margin-bottom: 1rem;
        }
        
        .empty-state h3 {
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: #666;
            margin-bottom: 1.5rem;
        }
        
        .shop-now-btn {
            padding: 0.8rem 1.5rem;
            background-color: var(--primary);
            color: white;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
        }
        
        .shop-now-btn:hover {
            background-color: #a87c52;
        }
        
        @media (max-width: 600px) {
            .order-header {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .order-summary {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .items-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }
        }
    </style>
</head>
<body>
    <div class="order-history-container">
        <h1 class="page-title">Riwayat Pesanan</h1>
        
        <?php if (isset($error)): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <h3>Belum ada pesanan</h3>
                <p>Anda belum pernah melakukan pemesanan di UThrift.</p>
                <a href="index.php" class="shop-now-btn">Belanja Sekarang</a>
            </div>
        <?php else: ?>
            <div class="order-list">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span class="order-id">Pesanan #<?= htmlspecialchars($order['id']) ?></span>
                                <span class="order-date"> - <?= date('d F Y', strtotime($order['created_at'])) ?></span>
                            </div>
                            <span class="order-status status-<?= htmlspecialchars(strtolower($order['status'])) ?>">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </div>
                        
                        <div class="order-summary">
                            <div class="order-items-count">
                                <i class="fas fa-box"></i>
                                <span><?= htmlspecialchars($order['item_count']) ?> barang</span>
                            </div>
                            
                            <div class="order-total">
                                Total: Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
                            </div>
                        </div>
                        
                        <div class="order-items-container">
                            <div class="items-title">Barang yang dibeli:</div>
                            <div class="items-grid">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="item-card">
                                        <img src="asset/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-image">
                                        <div class="item-info">
                                            <div class="item-name" title="<?= htmlspecialchars($item['name']) ?>">
                                                <?= htmlspecialchars($item['name']) ?>
                                            </div>
                                            <div class="item-qty-price">
                                                <span>Qty: <?= $item['quantity'] ?></span>
                                                <span>Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>