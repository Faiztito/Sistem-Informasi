<?php
session_start();
include 'config.php';
include 'navbar.php';

// Ambil data dari keranjang sesi
$cart_items = $_SESSION['cart'] ?? [];

// Hitung subtotal awal (untuk PHP, hanya tampilan awal)
$subtotal = 0;
if (!empty($cart_items)) {
    foreach ($cart_items as $item) {
        $subtotal += $item['price'] * $item['quantity'];
    }
}
$shipping_cost = 15000;
$total = $subtotal + $shipping_cost;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - UThrift</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css ">
    <style>
        :root {
            --primary: #8B5A2B;
            --primary-light: #A87C52;
            --secondary: #F5F0E6;
            --dark: #3E2723;
            --light: #FFF8F0;
            --accent: #D4A76A;
            --success: #4CAF50;
            --danger: #E74C3C;
            --text: #333333;
            --text-light: #777777;
            --border: #E0D5C8;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
        body {
            font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .cart-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 5%;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        .cart-items {
            flex: 2;
            min-width: 300px;
        }
        .cart-summary {
            flex: 1;
            min-width: 300px;
        }
        .cart-header {
            margin-bottom: 1.5rem;
        }
        .cart-header h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }
        .cart-table th, .cart-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .cart-table th {
            background-color: var(--secondary);
            font-weight: 600;
        }
        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .cart-item-price {
            color: var(--primary);
            font-weight: 600;
        }
        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .quantity-btn {
            width: 30px;
            height: 30px;
            border: 1px solid var(--border);
            background-color: transparent;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        .quantity-btn:hover {
            background-color: var(--secondary);
        }
        .quantity-input {
            width: 50px;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 0.3rem;
        }
        .remove-btn {
            color: var(--danger);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            transition: transform 0.3s;
        }
        .remove-btn:hover {
            transform: scale(1.1);
        }
        .summary-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
        }
        .summary-title {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            padding-bottom: 0.8rem;
            border-bottom: 1px solid var(--border);
        }
        .summary-total {
            font-weight: 600;
            font-size: 1.1rem;
            margin-top: 1rem;
        }
        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1.5rem;
            transition: background-color 0.3s;
        }
        .checkout-btn:hover {
            background-color: var(--primary-light);
        }
        .empty-cart {
            text-align: center;
            padding: 3rem 0;
        }
        .empty-cart i {
            font-size: 3rem;
            color: var(--text-light);
            margin-bottom: 1rem;
        }
        .empty-cart p {
            color: var(--text-light);
            margin-bottom: 1.5rem;
        }
        .continue-shopping {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: var(--primary);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .continue-shopping:hover {
            background-color: var(--primary-light);
        }
        @media (max-width: 768px) {
            .cart-container {
                flex-direction: column;
                padding: 0 1rem;
            }
            .cart-items, .cart-summary {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="cart-container">
        <!-- Daftar Item Keranjang -->
        <div class="cart-items">
            <div class="cart-header">
                <h1>Keranjang Belanja</h1>
            </div>

            <?php if (count($cart_items) > 0): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"> Pilih</th>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr data-id="<?= $item['id'] ?>">
                                <td>
                                    <input type="checkbox" class="item-checkbox" checked data-id="<?= $item['id'] ?>">
                                </td>
                                <td>
                                    <img src="asset/<?= $item['image'] ?>" alt="<?= $item['name'] ?>" class="cart-item-img">
                                    <span><?= $item['name'] ?></span>
                                </td>
                                <td class="cart-item-price">Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
                                <td>
                                    <div class="quantity-control">
                                        <button class="quantity-btn minus" data-id="<?= $item['id'] ?>">-</button>
                                        <input type="number"
                                               class="quantity-input"
                                               value="<?= $item['quantity'] ?>"
                                               min="1"
                                               max="<?= $item['stock'] ?>"
                                               data-id="<?= $item['id'] ?>">
                                        <button class="quantity-btn plus" data-id="<?= $item['id'] ?>">+</button>
                                    </div>
                                </td>
                                <td class="cart-item-subtotal">Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></td>
                                <td>
                                    <button class="remove-btn" data-id="<?= $item['id'] ?>"><i class="fas fa-trash-alt"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Keranjang Belanja Kosong</h3>
                    <p>Belum ada produk yang ditambahkan ke keranjang belanja Anda</p>
                    <a href="products.php" class="continue-shopping">Lanjutkan Belanja</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Ringkasan Belanja -->
        <?php if (count($cart_items) > 0): ?>
            <div class="cart-summary">
                <div class="summary-card">
                    <h2 class="summary-title">Ringkasan Belanja</h2>
                    <div class="summary-row">
                        <span>Subtotal (<span id="selected-count"><?= count($cart_items) ?></span> produk)</span>
                        <span id="subtotal-amount">Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Ongkos Kirim</span>
                        <span id="shipping-amount">Rp <?= number_format($shipping_cost, 0, ',', '.') ?></span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span id="total-amount">Rp <?= number_format($total, 0, ',', '.') ?></span>
                    </div>
                    <button class="checkout-btn">Lanjut ke Pembayaran</button>
                    <p style="margin-top: 1rem; font-size: 0.9rem; color: var(--text-light); text-align: center;">
                        <i class="fas fa-lock"></i> Transaksi Anda aman dan terlindungi
                    </p>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    // Fungsi utama: update ringkasan hanya dari item yang dicentang
    function updateSummary() {
        let subtotal = 0;
        let selectedCount = 0;
        document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
            const id = checkbox.getAttribute('data-id');
            const row = document.querySelector(`tr[data-id="${id}"]`);
            const subtotalText = row.querySelector('.cart-item-subtotal').textContent;
            const value = parseInt(subtotalText.replace(/[^0-9]/g, '')) || 0;
            subtotal += value;
            selectedCount++;
        });
        const shipping = <?= $shipping_cost ?>;
        const total = subtotal + shipping;
        const format = num => 'Rp ' + num.toLocaleString('id-ID');
        document.getElementById('selected-count').textContent = selectedCount;
        document.getElementById('subtotal-amount').textContent = format(subtotal);
        document.getElementById('total-amount').textContent = format(total);
    }

    // Setup handler interaksi
    function setupCartHandlers() {
        // Pilih / Batalkan Semua
        const selectAll = document.getElementById('select-all');
        selectAll.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(cb => cb.checked = this.checked);
            updateSummary();
        });
        // Update status "Pilih Semua" saat centang manual
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                const all = document.querySelectorAll('.item-checkbox');
                const checked = document.querySelectorAll('.item-checkbox:checked');
                selectAll.checked = all.length === checked.length;
                updateSummary();
            });
        });
        // Tombol +/- jumlah
        document.querySelectorAll('.quantity-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
                let value = parseInt(input.value);
                if (this.classList.contains('minus') && value > 1) {
                    input.value = value - 1;
                } else if (this.classList.contains('plus')) {
                    const max = parseInt(input.max);
                    if (value < max) {
                        input.value = value + 1;
                    }
                }
                // Update subtotal per item dan kirim ke server
                updateItemQuantity(id, input.value);
            });
        });
        // Input jumlah manual
        document.querySelectorAll('.quantity-input').forEach(input => {
            // Gunakan 'input' untuk respon langsung saat mengetik
            input.addEventListener('input', function() {
                 // Validasi input sederhana
                 let val = parseInt(this.value);
                 const min = parseInt(this.min) || 1;
                 const max = parseInt(this.max) || 999; // Default jika tidak ada max
                 if (isNaN(val) || val < min) val = min;
                 if (val > max) val = max;
                 this.value = val;
                 // Update subtotal per item dan kirim ke server
                 updateItemQuantity(this.getAttribute('data-id'), this.value);
            });

            // Validasi saat kehilangan fokus (blur)
            input.addEventListener('blur', function() {
                 let val = parseInt(this.value);
                 const min = parseInt(this.min) || 1;
                 const max = parseInt(this.max) || 999;
                 if (isNaN(val) || val < min) {
                     this.value = min;
                 } else if (val > max) {
                     this.value = max;
                 }
                 // Trigger update lagi setelah koreksi
                 this.dispatchEvent(new Event('input'));
            });
        });
        // Hapus item
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', async function () {
                const id = this.getAttribute('data-id');
                if (!confirm('Yakin ingin hapus produk ini?')) return;
                try {
                    const response = await fetch('remove_cart.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: id })
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        this.closest('tr').remove();
                        updateSummary();
                        if (document.querySelectorAll('.cart-table tbody tr').length === 0) {
                            location.reload(); // Cara paling sederhana
                        }
                    } else {
                        alert('Gagal: ' + result.message);
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan. Halaman akan dimuat ulang.');
                    location.reload();
                }
            });
        });
    }

    // --- FUNGSI PENTING UNTUK UPDATE QUANTITY ---
    // Fungsi ini menggabungkan update tampilan lokal dan panggilan ke update_cart.php
    function updateItemQuantity(id, newQuantity) {
        const inputElement = document.querySelector(`.quantity-input[data-id="${id}"]`);
        if (!inputElement) return; // Jaga-jaga

        const priceText = inputElement.closest('tr').querySelector('.cart-item-price').textContent;
        const price = parseInt(priceText.replace(/[^0-9]/g, '')) || 0;
        const newSubtotal = price * parseInt(newQuantity);
        
        // Update tampilan subtotal item
        inputElement.closest('tr').querySelector('.cart-item-subtotal').textContent =
            'Rp ' + newSubtotal.toLocaleString('id-ID');
        
        // Update ringkasan jika item ini dipilih
        if (inputElement.closest('tr').querySelector('.item-checkbox').checked) {
            updateSummary();
        }

        // Kirim update ke server (update_cart.php)
        fetch('update_cart.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, // Perubahan: Gunakan form-urlencoded
            body: `id=${encodeURIComponent(id)}&quantity=${encodeURIComponent(newQuantity)}` // Perubahan: Format data
        })
        .then(response => response.json())
        .then(data => {
             if (!data.success) {
                 console.error('Gagal update quantity di server:', data.message);
                 // Opsional: Tampilkan pesan error ke user
             }
        })
        .catch(error => {
            console.error('Error:', error);
            // Opsional: Tampilkan pesan error ke user
        });
    }

    // --- FUNGSI UNTUK SUBMIT CHECKOUT DENGAN ITEM TERPILIH ---
    function submitCheckout() {
        const selectedCheckboxes = document.querySelectorAll('.item-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Pilih minimal satu produk untuk melanjutkan ke pembayaran.');
            return;
        }

        // Buat form hidden dan submit
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'checkout.php';

        // Tambahkan setiap ID item terpilih sebagai input hidden
        selectedCheckboxes.forEach(checkbox => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_item_ids[]'; // Nama array
            input.value = checkbox.getAttribute('data-id');
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Jalankan setelah DOM siap
    document.addEventListener('DOMContentLoaded', function () {
        setupCartHandlers();
        updateSummary(); // Inisialisasi

        // Ganti event listener tombol checkout
        const checkoutBtn = document.querySelector('.checkout-btn');
        if (checkoutBtn) {
            checkoutBtn.removeEventListener('click', null); // Hapus listener lama jika ada
            checkoutBtn.addEventListener('click', function(e) {
                e.preventDefault(); // Cegah aksi default
                submitCheckout();   // Gunakan fungsi custom
            });
        }
    });
</script>
</body>
</html>