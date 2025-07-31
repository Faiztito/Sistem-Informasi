<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth.php");
    exit();
}

include '../config.php'; // Sesuaikan path jika perlu

// Proses ubah status
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    // Validasi status untuk COD
    if (in_array($status, ['pending', 'dipacking', 'dikirim', 'selesai'])) {
        try {
            $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$status, $order_id]);
            $success_msg = "Status pesanan berhasil diperbarui.";
        } catch (PDOException $e) {
            $error_msg = "Gagal memperbarui status.";
        }
    }
}

// Filter berdasarkan tanggal
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$sql = "SELECT id, name, created_at, total_price, status FROM orders";
$params = [];
$where = [];

if ($start_date) {
    $where[] = "created_at >= ?";
    $params[] = $start_date . ' 00:00:00';
}
if ($end_date) {
    $where[] = "created_at <= ?";
    $params[] = $end_date . ' 23:59:59';
}

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UThrift - Transaction History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css ">
    <style>
        :root {
            --primary: #8B5A2B;
            --secondary: #F5F0E6;
            --dark: #3E2723;
            --light: #FFF8F0;
            --text: #333333;
            --border: #E0D5C8;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light);
        }
        .container {
            display: flex;
        }
        .sidebar {
            width: 200px;
            background-color: var(--primary);
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }
        .sidebar h2 {
            margin-bottom: 20px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar li {
            margin-bottom: 10px;
        }
        .sidebar a {
            text-decoration: none;
            color: white;
            font-size: 1rem;
            font-weight: 500;
        }
        .content {
            flex: 1;
            padding: 20px;
            margin-left: 240px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--secondary);
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .header h3 {
            margin: 0;
            color: var(--dark);
        }
        .filter-form {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        .filter-form input, .filter-form button {
            padding: 8px;
            border: 1px solid var(--border);
            border-radius: 4px;
        }
        .filter-form button {
            background-color: var(--primary);
            color: white;
            cursor: pointer;
        }
        .transactions-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: var(--shadow);
        }
        .transactions-container h2 {
            margin-top: 0;
            color: var(--dark);
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        th {
            background-color: var(--secondary);
            font-weight: 600;
            color: var(--dark);
        }
        /* Status Warna */
        .status-pending {
            color: #ff8c00;
            background-color: #fff3cd;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .status-dipacking {
            color: #0056b3;
            background-color: #d1ecf1;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .status-dikirim {
            color: #8b5a2b;
            background-color: #f5f0e6;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .status-selesai {
            color: #28a745;
            background-color: #d4edda;
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: 500;
        }
        .btn-detail {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .detail-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background: white;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        .close-modal {
            font-size: 1.5rem;
            cursor: pointer;
        }
        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <h2>UThrift</h2>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="produk.php">Products</a></li>
                <li><a href="transaksi.php" style="font-weight: bold; text-decoration: underline;">Transactions</a></li>
                <li><a href="../logout.php">Log Out</a></li>
            </ul>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Header -->
            <div class="header">
                <h3>Halo, <?php echo htmlspecialchars($_SESSION['admin']['username'] ?? 'Admin'); ?>!</h3>
            </div>

            <!-- Alert -->
            <?php if (isset($success_msg)): ?>
                <div class="alert alert-success"><?= $success_msg ?></div>
            <?php endif; ?>
            <?php if (isset($error_msg)): ?>
                <div class="alert alert-error"><?= $error_msg ?></div>
            <?php endif; ?>

            <!-- Filter Tanggal -->
            <div class="filter-form">
                <form method="GET" style="display: flex; gap: 10px;">
                    <div>
                        <label for="start_date">Dari Tanggal:</label>
                        <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                    </div>
                    <div>
                        <label for="end_date">Sampai:</label>
                        <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                    </div>
                    <button type="submit">Filter</button>
                    <a href="transaksi.php" style="margin-left: 10px; color: #666;">Reset</a>
                </form>
            </div>

            <!-- Transactions Content -->
            <div class="transactions-container">
                <h2>Riwayat Transaksi</h2>
                <?php if (empty($orders)): ?>
                    <p>Tidak ada transaksi ditemukan.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Pembeli</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#TRX<?= str_pad($order['id'], 3, '0', STR_PAD_LEFT) ?></td>
                                    <td><?= htmlspecialchars($order['name']) ?></td>
                                    <td><?= date('d-m-Y', strtotime($order['created_at'])) ?></td>
                                    <td>Rp <?= number_format($order['total_price'], 0, ',', '.') ?></td>
                                    <td>
                                        <span class="status-<?php echo strtolower($order['status']); ?>">
                                            <?= ucfirst($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                            <select name="status" onchange="this.form.submit()">
                                                <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="dipacking" <?= $order['status'] == 'dipacking' ? 'selected' : '' ?>>Dipacking</option>
                                                <option value="dikirim" <?= $order['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                                <option value="selesai" <?= $order['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                            </select>
                                            <input type="hidden" name="change_status" value="1">
                                        </form>
                                        <button class="btn-detail" onclick="showDetail(<?= $order['id'] ?>)">Detail</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div id="detailModal" class="detail-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detail Pesanan</h3>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div id="detailContent">
                Memuat...
            </div>
        </div>
    </div>

    <script>
        // Fungsi tampilkan detail
        async function showDetail(orderId) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');
            content.innerHTML = 'Memuat...';

            try {
                const response = await fetch(`get_order_detail.php?id=${orderId}`);
                const data = await response.json();

                if (data.error) {
                    content.innerHTML = '<p>' + data.error + '</p>';
                    return;
                }

                let itemsHtml = '<h4>Produk dalam Pesanan:</h4><ul>';
                data.items.forEach(item => {
                    itemsHtml += `
                        <li>
                            <img src="../asset/${item.image}" alt="${item.name}" width="50" style="border-radius:4px;">
                            ${item.name} - Rp ${Number(item.price).toLocaleString('id-ID')} x ${item.quantity}
                        </li>`;
                });
                itemsHtml += '</ul>';
                itemsHtml += `<p><strong>Total: Rp ${Number(data.total).toLocaleString('id-ID')}</strong></p>`;
                content.innerHTML = itemsHtml;

            } catch (err) {
                content.innerHTML = '<p>Gagal memuat detail.</p>';
            }

            modal.style.display = 'flex';
        }

        // Tutup modal
        function closeModal() {
            document.getElementById('detailModal').style.display = 'none';
        }

        // Close when click outside
        window.onclick = function(event) {
            const modal = document.getElementById('detailModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>