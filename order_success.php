<?php
session_start();
include 'config.php';
include 'navbar.php';

// Validasi order_id dari URL
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
if ($order_id <= 0) {
    header("Location: index.php");
    exit();
}

// Dapatkan user_id dari session
$user_id = $_SESSION['user']['id'] ?? null;
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Ambil data pesanan dari database
try {
    // Ambil data pesanan utama
    $stmt = $pdo->prepare("
        SELECT o.*, u.username 
        FROM orders o
        JOIN users u ON o.user_id = u.id
        WHERE o.id = ? AND o.user_id = ?
    ");
    $stmt->execute([$order_id, $user_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        throw new Exception("Pesanan tidak ditemukan");
    }

    // Ambil item-item pesanan
    $stmt_items = $pdo->prepare("
        SELECT oi.*, p.name, p.image 
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");
    $stmt_items->execute([$order_id]);
    $order_items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log("Error fetching order: " . $e->getMessage());
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil - UThrift</title>
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
        }
        
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .success-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 0 5%;
        }
        
        .success-card {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: var(--shadow);
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            color: var(--success);
            margin-bottom: 1.5rem;
        }
        
        h1 {
            color: var(--dark);
            margin-bottom: 1rem;
        }
        
        .order-id {
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
        }
        
        .order-id span {
            font-weight: 600;
            color: var(--primary);
        }
        
        .thank-you {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .order-details {
            margin-top: 2rem;
            text-align: left;
            border-top: 1px solid var(--border);
            padding-top: 2rem;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 1rem;
        }
        
        .detail-label {
            flex: 1;
            font-weight: 500;
            color: var(--dark);
        }
        
        .detail-value {
            flex: 2;
        }
        
        .order-items {
            margin-top: 2rem;
        }
        
        .order-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
            align-items: center;
        }
        
        .item-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .item-details {
            flex: 1;
        }
        
        .item-name {
            font-weight: 500;
        }
        
        .item-price {
            color: var(--primary);
            font-weight: 500;
        }
        
        .order-total {
            margin-top: 2rem;
            text-align: right;
            font-size: 1.2rem;
            font-weight: 600;
        }
        
        .action-buttons {
            margin-top: 3rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #a87c52;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: var(--dark);
            border: 1px solid var(--border);
        }
        
        .btn-secondary:hover {
            background-color: #e8e0d0;
        }
        
        @media (max-width: 600px) {
            .detail-row {
                flex-direction: column;
            }
            
            .detail-label {
                margin-bottom: 0.3rem;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1>Pesanan Berhasil Diproses!</h1>
            <div class="order-id">
                Nomor Pesanan: <span>#<?= htmlspecialchars($order_id) ?></span>
            </div>
            <p class="thank-you">
                Terima kasih telah berbelanja di UThrift. Pesanan Anda telah kami terima dan sedang diproses. 
                Kami akan mengirimkan konfirmasi melalui email ke <?= htmlspecialchars($order['email']) ?>.
            </p>
            
            <div class="order-details">
                <h2>Detail Pesanan</h2>
                
                <div class="detail-row">
                    <div class="detail-label">Tanggal Pesanan</div>
                    <div class="detail-value"><?= date('d F Y H:i', strtotime($order['created_at'])) ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Nama Pemesan</div>
                    <div class="detail-value"><?= htmlspecialchars($order['name']) ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Nomor Telepon</div>
                    <div class="detail-value"><?= htmlspecialchars($order['phone']) ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Alamat Pengiriman</div>
                    <div class="detail-value"><?= nl2br(htmlspecialchars($order['address'])) ?></div>
                </div>
                
                <div class="order-items">
                    <h3>Item Pesanan</h3>
                    <?php foreach ($order_items as $item): ?>
                        <div class="order-item">
                            <img src="asset/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-img">
                            <div class="item-details">
                                <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                <div class="item-qty">Jumlah: <?= $item['quantity'] ?></div>
                                <div class="item-price">Rp <?= number_format($item['price'], 0, ',', '.') ?> x <?= $item['quantity'] ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="order-total">
                    Total Pembayaran: Rp <?= number_format($order['total_price'], 0, ',', '.') ?>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="products.php" class="btn btn-primary">Lanjutkan Belanja</a>
                <a href="order_history.php" class="btn btn-secondary">Lihat Riwayat Pesanan</a>
            </div>
        </div>
    </div>
</body>
</html>