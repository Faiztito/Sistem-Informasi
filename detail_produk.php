<?php
session_start(); // Pastikan session dimulai
include 'navbar.php'; 
include 'config.php';

// Ambil ID produk dari URL
$id = $_GET['id'] ?? '';
if (empty($id)) {
    die("Produk tidak ditemukan.");
}

// Ambil data produk dari database
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Produk tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - <?= htmlspecialchars($product['name']) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Product Detail Styles */
        :root {
            --primary: #8B5A2B;
            --primary-light: #A87C52;
            --secondary: #F5F0E6;
            --dark: #3E2723;
            --light: #FFF8F0;
            --accent: #D4A76A;
            --success: #4CAF50;
            --text: #333333;
            --text-light: #777777;
            --border: #E0D5C8;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            /* Modal Vars */
            --modal-bg: white;
            --modal-overlay: rgba(0, 0, 0, 0.5);
        }

        body {
            font-family: 'Segoe UI', 'Helvetica Neue', sans-serif;
            background-color: var(--light);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            /* Prevent background scroll when modal is open */
            overflow-x: hidden;
        }

        .product-detail {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 5%;
            display: flex;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .product-gallery {
            flex: 1;
            min-width: 300px;
            max-width: 500px;
        }

        .main-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: var(--shadow);
            margin-bottom: 1rem;
            transition: transform 0.3s;
        }

        .main-image:hover {
            transform: scale(1.02);
        }

        .thumbnail-container {
            display: flex;
            gap: 1rem;
        }

        .thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 4px;
            cursor: pointer;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }

        .thumbnail:hover {
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .product-info {
            flex: 1;
            min-width: 300px;
        }

        .product-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .product-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .product-meta span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .product-price {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin: 1.5rem 0;
        }

        .product-description {
            margin-bottom: 2rem;
            color: var(--text);
            line-height: 1.7;
        }

        .product-stock {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background-color: var(--secondary);
            border-radius: 4px;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .in-stock {
            color: var(--success);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.8rem 1.8rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(139, 90, 43, 0.2);
        }

        .btn-secondary {
            background-color: var(--secondary);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background-color: #e8e0d1;
            transform: translateY(-2px);
        }

        .divider {
            height: 1px;
            background-color: var(--border);
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
            .product-detail {
                flex-direction: column;
                padding: 0 1rem;
            }

            .product-gallery, .product-info {
                max-width: 100%;
            }
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 5%;
            text-align: center;
            font-weight: 500;
            border: 1px solid #c3e6cb;
        }

        /* --- MODAL STYLES (Popup dari bawah) --- */
        .modal-overlay {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: var(--modal-overlay); /* Black w/ opacity */
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: block;
            opacity: 1;
        }

        .modal {
            position: fixed;
            left: 0;
            bottom: 0; /* Start from the bottom */
            width: 100%;
            background-color: var(--modal-bg);
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(100%); /* Start hidden below the screen */
            transition: transform 0.3s ease-out;
            max-height: 90vh; /* Prevent modal from being too tall */
            display: flex;
            flex-direction: column;
        }

        .modal-overlay.active .modal {
            transform: translateY(0); /* Slide up to view */
        }

        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
        }

        .modal-body {
            padding: 1.5rem;
            flex-grow: 1;
            overflow-y: auto; /* Scroll if content is long */
        }

        .product-preview {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .preview-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .preview-info h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
        }

        .preview-price {
            font-weight: 600;
            color: var(--primary);
            font-size: 1.1rem;
        }

        .quantity-selector {
            margin: 1.5rem 0;
        }

        .quantity-selector label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn-modal {
            width: 40px;
            height: 40px;
            border: 1px solid var(--border);
            background-color: var(--secondary);
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-input-modal {
            width: 60px;
            text-align: center;
            border: 1px solid var(--border);
            border-radius: 4px;
            padding: 0.5rem;
            font-size: 1rem;
        }

        .stock-info {
            font-size: 0.9rem;
            color: var(--text-light);
            margin-top: 0.5rem;
        }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .btn-modal {
            padding: 0.8rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
            width: 100%; /* Tombol penuh lebar */
        }

        .btn-modal-cart {
            background-color: var(--secondary);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-modal-cart:hover {
            background-color: #e8e0d1;
        }

        .btn-modal-buy {
            background-color: var(--primary);
            color: white;
        }

        .btn-modal-buy:hover {
            background-color: var(--primary-light);
        }

        /* Utility class untuk menyembunyikan elemen */
        .hidden {
            display: none !important;
        }

        /* Prevent background scroll */
        body.modal-open {
            overflow: hidden;
        }
    </style>
</head>
<body>
<!-- Di bagian atas detail_produk.php -->
<?php if (isset($_GET['added']) && $_GET['added'] == 'true'): ?>
    <div class="alert-success">
        Produk berhasil ditambahkan ke keranjang!
    </div>
<?php endif; ?>

    <!-- Product Detail Section -->
    <section class="product-detail">
        <div class="product-gallery">
            <img src="asset/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="main-image" id="main-image">
            
            <div class="thumbnail-container">
                <img src="asset/<?= htmlspecialchars($product['image']) ?>" alt="Thumbnail 1" class="thumbnail" data-src="asset/<?= htmlspecialchars($product['image']) ?>">
                <!-- Tambahkan thumbnail lainnya jika ada -->
            </div>
        </div>
        
        <div class="product-info">
            <h1 class="product-title"><?= htmlspecialchars($product['name']) ?></h1>
            
            <div class="product-meta">
                <span>
                    <i class="fas fa-tag"></i>
                    <?= htmlspecialchars($product['category']) ?>
                </span>
                <span>
                    <i class="fas fa-star"></i>
                    4.8 (120 reviews)
                </span>
            </div>
            
            <div class="product-price">
                Rp <?= number_format($product['price'], 0, ',', '.') ?>
            </div>
            
            <div class="product-stock in-stock">
                <i class="fas fa-check-circle"></i>
                Stok Tersedia: <span id="product-stock"><?= htmlspecialchars($product['stock']) ?></span>
            </div>
            
            <div class="divider"></div>
            
            <div class="product-description">
                <h3>Deskripsi Produk</h3>
                <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
            </div>
            
            <div class="action-buttons">
                <!-- Tombol untuk memicu popup -->
                <button class="btn btn-primary" id="open-cart-modal">
                    <i class="fas fa-shopping-cart"></i>
                    Tambah ke Keranjang
                </button>

                <button class="btn btn-secondary" id="open-buy-modal">
                    <i class="fas fa-bolt"></i>
                    Beli Sekarang
                </button>
            </div>
        </div>
    </section>

    <!-- --- MODAL (Popup dari bawah) --- -->
    <div class="modal-overlay" id="product-modal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Pilih Jumlah</h2>
                <button class="close-modal" id="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="product-preview">
                    <img src="asset/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="preview-img" id="modal-img">
                    <div class="preview-info">
                        <h3 id="modal-name"><?= htmlspecialchars($product['name']) ?></h3>
                        <div class="preview-price" id="modal-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                    </div>
                </div>
                <div class="quantity-selector">
                    <label for="modal-quantity">Jumlah:</label>
                    <div class="quantity-control">
                        <button class="quantity-btn-modal" id="modal-qty-minus">-</button>
                        <input type="number" id="modal-quantity" class="quantity-input-modal" value="1" min="1" max="<?= $product['stock'] ?>">
                        <button class="quantity-btn-modal" id="modal-qty-plus">+</button>
                    </div>
                    <div class="stock-info">
                        Stok tersedia: <span id="modal-stock"><?= $product['stock'] ?></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Tombol akan ditampilkan/sembunyikan berdasarkan context -->
                <button class="btn-modal btn-modal-cart" id="modal-add-to-cart">
                    <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                </button>
                <button class="btn-modal btn-modal-buy" id="modal-buy-now">
                    <i class="fas fa-bolt"></i> Beli Sekarang
                </button>
            </div>
        </div>
    </div>
    <!-- --- END MODAL --- -->

    <script>
        // --- MODAL LOGIC ---
        const modal = document.getElementById('product-modal');
        const openCartBtn = document.getElementById('open-cart-modal');
        const openBuyBtn = document.getElementById('open-buy-modal');
        const closeBtn = document.getElementById('close-modal');
        const qtyInput = document.getElementById('modal-quantity');
        const qtyPlusBtn = document.getElementById('modal-qty-plus');
        const qtyMinusBtn = document.getElementById('modal-qty-minus');
        const addToCartBtn = document.getElementById('modal-add-to-cart');
        const buyNowBtn = document.getElementById('modal-buy-now');

        // Data produk dari PHP
        const productId = <?= json_encode($product['id']) ?>;
        const productName = <?= json_encode($product['name']) ?>;
        const productPrice = <?= json_encode($product['price']) ?>;
        const productStock = <?= json_encode($product['stock']) ?>;
        const productImage = <?= json_encode("asset/" . $product['image']) ?>;

        let currentMode = 'cart'; // Mode default

        // Fungsi untuk membuka modal
        function openModal(mode) {
            currentMode = mode; // Simpan mode saat ini ('cart' atau 'buy')
            
            // Reset nilai quantity ke 1 setiap kali modal dibuka
            qtyInput.value = 1;
            qtyInput.max = productStock;
            document.getElementById('modal-stock').textContent = productStock;
            
            // Reset tampilan tombol berdasarkan mode
            if (currentMode === 'cart') {
                addToCartBtn.classList.remove('hidden');
                buyNowBtn.classList.add('hidden');
                document.querySelector('.modal-title').textContent = 'Tambah ke Keranjang';
            } else if (currentMode === 'buy') {
                addToCartBtn.classList.add('hidden');
                buyNowBtn.classList.remove('hidden');
                document.querySelector('.modal-title').textContent = 'Beli Sekarang';
            }

            modal.classList.add('active');
            document.body.classList.add('modal-open'); // Mencegah scroll background
        }

        // Fungsi untuk menutup modal
        function closeModal() {
            modal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Event Listeners untuk membuka modal
        openCartBtn.addEventListener('click', () => openModal('cart'));
        openBuyBtn.addEventListener('click', () => openModal('buy'));

        // Event Listener untuk tombol close
        closeBtn.addEventListener('click', closeModal);

        // Event Listener untuk klik di luar modal untuk menutup
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Kontrol jumlah di modal
        qtyPlusBtn.addEventListener('click', () => {
            let value = parseInt(qtyInput.value) || 1;
            if (value < productStock) {
                qtyInput.value = value + 1;
            }
        });

        qtyMinusBtn.addEventListener('click', () => {
            let value = parseInt(qtyInput.value) || 1;
            if (value > 1) {
                qtyInput.value = value - 1;
            }
        });

        // Validasi input jumlah manual
        qtyInput.addEventListener('input', function() {
            let val = parseInt(this.value) || 1;
            if (val < 1) val = 1;
            if (val > productStock) val = productStock;
            this.value = val;
        });

        // --- FUNGSI TAMBAH KE KERANJANG ---
        function addToCart(quantity, callback) {
             fetch('add_to_cart.php', {
                 method: 'POST',
                 headers: {
                     'Content-Type': 'application/x-www-form-urlencoded',
                 },
                 body: `id=${encodeURIComponent(productId)}&quantity=${encodeURIComponent(quantity)}`
             })
             .then(response => response.json())
             .then(data => {
                 if (data.success) {
                     console.log('Produk berhasil ditambahkan ke keranjang.');
                     if (callback) callback();
                 } else {
                     alert('Gagal menambahkan ke keranjang: ' + (data.message || 'Kesalahan tidak diketahui'));
                 }
             })
             .catch(error => {
                 console.error('Error:', error);
                 alert('Terjadi kesalahan saat menambahkan ke keranjang.');
             });
        }

        // Event Listener untuk tombol "Tambah ke Keranjang" di modal
        addToCartBtn.addEventListener('click', () => {
            const quantity = parseInt(qtyInput.value) || 1;
            if (quantity > productStock) {
                alert('Jumlah melebihi stok yang tersedia.');
                return;
            }
            addToCart(quantity, () => {
                closeModal();
                // Tampilkan pesan sukses di halaman ini
                 const alertDiv = document.createElement('div');
                 alertDiv.className = 'alert-success';
                 alertDiv.textContent = `"${productName}" (${quantity} pcs) berhasil ditambahkan ke keranjang!`;
                 document.body.insertBefore(alertDiv, document.querySelector('.product-detail'));
                 // Hilangkan pesan setelah beberapa detik
                 setTimeout(() => {
                     if (alertDiv.parentNode) {
                         alertDiv.parentNode.removeChild(alertDiv);
                     }
                 }, 3000);
            });
        });

        // Event Listener untuk tombol "Beli Sekarang" di modal
        buyNowBtn.addEventListener('click', () => {
            const quantity = parseInt(qtyInput.value) || 1;
            if (quantity > productStock) {
                alert('Jumlah melebihi stok yang tersedia.');
                return;
            }
            
            // Tambahkan ke keranjang dulu, lalu redirect
            addToCart(quantity, () => {
                 window.location.href = 'checkout.php?from_buy_now=1&single=' + productId;
            });
        });

        // Interaksi thumbnail (diperbarui untuk mendukung modal)
        document.querySelectorAll('.thumbnail').forEach(thumb => {
            thumb.addEventListener('click', function() {
                const mainImage = document.getElementById('main-image');
                const modalImage = document.getElementById('modal-img');
                const newSrc = this.getAttribute('data-src') || this.src;
                mainImage.src = newSrc;
                mainImage.alt = this.alt;
                // Juga update gambar di modal jika modal terbuka
                if (modal.classList.contains('active')) {
                    modalImage.src = newSrc;
                    modalImage.alt = this.alt;
                }
            });
        });
    </script>
</body>
</html>