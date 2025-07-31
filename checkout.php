<?php
session_start();
include 'config.php';
include 'navbar.php';

// 🟩 LANGKAH 1: PROSES FORM DI ATAS SEBELUM OUTPUT APA PUN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- 1. Ambil dan Validasi Item Terpilih ---
    $selected_item_ids = $_POST['selected_item_ids'] ?? [];
    if (empty($selected_item_ids) || !is_array($selected_item_ids)) {
        $error = "Tidak ada item yang dipilih untuk checkout.";
        // Kita tetap muat halaman, tapi dengan error
        $items_to_checkout = [];
        $subtotal = 0;
        goto DISPLAY_PAGE; // Lompat ke bagian tampilan
    }

    // Sanitasi ID (pastikan integer)
    $selected_item_ids = array_map('intval', $selected_item_ids);

    // --- 2. Ambil Data Item Terpilih dari Session ---
    $cart_items_session = $_SESSION['cart'] ?? [];
    if (empty($cart_items_session)) {
         $error = "Keranjang Anda kosong.";
         $items_to_checkout = [];
         $subtotal = 0;
         goto DISPLAY_PAGE;
    }

    // Filter item session berdasarkan ID yang dipilih
    $items_to_checkout = [];
    foreach ($selected_item_ids as $id) {
        if (isset($cart_items_session[$id])) {
            $items_to_checkout[$id] = $cart_items_session[$id];
        }
    }

    // --- 3. Validasi dan Hitung Total ---
    if (empty($items_to_checkout)) {
         $error = "Item yang dipilih tidak ditemukan di keranjang Anda.";
         $subtotal = 0;
         goto DISPLAY_PAGE;
    }

    $subtotal = 0;
    foreach ($items_to_checkout as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping_cost = 15000;
    $total = $subtotal + $shipping_cost;

    // --- 4. Proses Data Form Pengiriman ---
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error = "Semua field informasi pengiriman harus diisi!";
        // Tetap tampilkan item terpilih
        goto DISPLAY_PAGE; // Lompat ke bagian tampilan
    }

    // --- 5. Simpan ke Database ---
    try {
        $pdo->beginTransaction();

        // Dapatkan user_id (pastikan user login)
        $user_id = $_SESSION['user']['id'] ?? null; // Sesuaikan dengan cara Anda menyimpan user ID di session
        if (!$user_id) {
             // Jika tidak login, Anda bisa arahkan ke login atau buat logika lain
             die("Silakan login terlebih dahulu.");
        }

        // Simpan pesanan utama
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, name, email, phone, address, total_price) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $name, $email, $phone, $address, $total]);
        $order_id = $pdo->lastInsertId();

        // Simpan item-item pesanan
        $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($items_to_checkout as $item) {
            $stmt_item->execute([$order_id, $item['id'], $item['quantity'], $item['price']]);

            // --- 6. Hapus Item dari Session Cart Setelah Checkout Berhasil ---
            // Hapus hanya item yang berhasil di-checkout
            unset($_SESSION['cart'][$item['id']]);
        }

        // Optional: Bersihkan session cart jika kosong
        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }

        $pdo->commit();

     // Dengan:
echo "<script>window.location.href='order_success.php?order_id=$order_id';</script>";
exit();

    } catch (Exception $e) {
        $pdo->rollback();
        error_log("Checkout gagal: " . $e->getMessage());
        $error = "Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.";
        // Tetap tampilkan item terpilih
        goto DISPLAY_PAGE; // Lompat ke bagian tampilan
    }

} else {
    // 🟨 JIKA TIDAK POST (akses langsung ke checkout.php)
    // --- Logika fallback jika tidak ada POST ---
    $cart_items_session = $_SESSION['cart'] ?? [];
    if (empty($cart_items_session)) {
        $error = "Keranjang Anda kosong.";
        $items_to_checkout = [];
        $subtotal = 0;
        goto DISPLAY_PAGE;
    }

    // Default: ambil semua item di cart untuk tampilan awal (fallback)
    // Di sini kita tampilkan semua sebagai fallback jika user akses langsung
    $items_to_checkout = $cart_items_session;
    $subtotal = 0;
    foreach ($items_to_checkout as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
    $shipping_cost = 15000;
    $total = $subtotal + $shipping_cost;
}

// --- Label untuk goto ---
DISPLAY_PAGE:
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - UThrift</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css ">
    <style>
        /* ... (CSS Anda tetap sama) ... */
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
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .checkout-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 5%;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .checkout-form {
            flex: 2;
            min-width: 300px;
        }
        .order-summary {
            flex: 1;
            min-width: 300px;
        }
        .form-card, .summary-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        h2 {
            color: var(--dark);
            margin-bottom: 1.5rem;
            font-size: 1.4rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            font-size: 1rem;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        .btn-checkout {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: background-color 0.3s;
        }
        .btn-checkout:hover {
            background-color: #a87c52;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px dashed var(--border);
        }
        .summary-total {
            font-weight: 600;
            font-size: 1.2rem;
            margin-top: 1rem;
            border-bottom: 2px solid var(--primary);
            padding-bottom: 0.5rem;
        }
        .order-item {
            display: flex;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--border);
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
        .error-message {
            color: red;
            margin: 1rem 0;
            font-size: 0.9rem;
        }
        .back-to-cart {
             display: block;
             text-align: center;
             margin-top: 1rem;
             color: var(--primary);
             text-decoration: none;
        }
        .back-to-cart:hover {
             text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <!-- Form Checkout -->
        <div class="checkout-form">
            <div class="form-card">
                <h2>Informasi Pengiriman</h2>
                <?php if (isset($error)): ?>
                    <p class="error-message"><?= htmlspecialchars($error) ?></p>
                     <a href="cart.php" class="back-to-cart">← Kembali ke Keranjang</a>
                <?php endif; ?>
                <form method="POST" action="">
                    <!-- Sembunyikan item terpilih dalam input hidden untuk konsistensi jika form gagal -->
                    <?php foreach (array_keys($items_to_checkout ?? []) as $item_id): ?>
                        <input type="hidden" name="selected_item_ids[]" value="<?= htmlspecialchars($item_id) ?>">
                    <?php endforeach; ?>

                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor Telepon</label>
                        <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Alamat Lengkap</label>
                        <textarea name="address" id="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn-checkout">Buat Pesanan</button>
                </form>
            </div>
        </div>
        <!-- Ringkasan Pesanan -->
        <div class="order-summary">
            <div class="summary-card">
                <h2>Ringkasan Pesanan</h2>
                 <?php if (!empty($items_to_checkout)): ?>
                    <div id="order-items-list">
                        <?php foreach ($items_to_checkout as $item): ?>
                            <div class="order-item">
                                <img src="asset/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="item-img">
                                <div class="item-details">
                                    <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                                    <div class="item-qty">Jumlah: <?= $item['quantity'] ?></div>
                                    <div class="item-price">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span>Rp <?= number_format($shipping_cost, 0, ',', '.') ?></span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                 <?php else: ?>
                    <p>Tidak ada item untuk ditampilkan.</p>
                    <a href="cart.php" class="back-to-cart">← Pilih Item di Keranjang</a>
                 <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>