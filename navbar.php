<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UThrift - <?php echo ucfirst(basename($_SERVER['SCRIPT_NAME'],'.php')); ?></title>
    <style>
        /* Navbar Styles */
        :root {
            --primary-color: #a38764;
            --secondary-color: #ece0d0;
            --dark-color: #3a2a22;
            --light-color: #d9cbbb;
            --search-bg: #f5f0e8;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            background-color: var(--secondary-color);
            border-bottom: 1px solid rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.3rem;
            color: var(--dark-color);
            text-decoration: none;
        }

        .logo img {
            height: 80px;
            width: 100px;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        nav a {
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        nav a:hover,
        nav a.active {
            color: var(--primary-color);
            background-color: rgba(163, 135, 100, 0.15);
        }

        nav a.active {
            border-bottom: 3px solid var(--primary-color);
            padding-bottom: 5px;
        }

        .logout-btn {
            background-color: var(--primary-color);
            color: white !important;
            padding: 8px 16px !important;
            margin-left: 10px;
            border-radius: 5px;
        }

        .logout-btn:hover {
            background-color: #8a6f4f !important;
        }

        /* Icons & Search */
        .icons {
            display: flex;
            gap: 15px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        .icons span {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .icons span:hover {
            transform: scale(1.1);
            color: var(--primary-color);
        }

        /* Search Box - Hidden by default */
        .search-container {
            width: 100%;
            padding: 15px 40px;
            background-color: var(--search-bg);
            display: none;
            justify-content: center;
            border-top: 1px solid rgba(0,0,0,0.1);
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .search-box {
            width: 100%;
            max-width: 500px;
            padding: 12px 16px;
            padding-left: 45px;
            border: 1px solid #ccc;
            border-radius: 30px;
            font-size: 1rem;
            outline: none;
            background-color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .search-box:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(163, 135, 100, 0.2);
        }

        .search-box::before {
            content: "🔍";
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.1rem;
            color: #999;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            width: 100%;
            border: none;
            outline: none;
            background: transparent;
            font: inherit;
        }
    </style>
</head>
<body>

    <!-- navbar.php -->
<header>
    <a href="home.php" class="logo">
        <img src="asset/uthrift.png" alt="UThrift Logo" />
        UThrift
    </a>
    <nav>
        <a href="home.php" <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'home.php') ? 'class="active"' : ''; ?>>Beranda</a>
        <a href="products.php" <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'products.php') ? 'class="active"' : ''; ?>>Produk</a>
        <a href="order_history.php" <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'order_history.php') ? 'class="active"' : ''; ?>>Riwayat Order</a>
        <a href="about.php" <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'about.php') ? 'class="active"' : ''; ?>>Tentang Kami</a>
        <a href="contact.php" <?php echo (basename($_SERVER['SCRIPT_NAME']) == 'contact.php') ? 'class="active"' : ''; ?>>Kontak</a>
        <a href="logout.php" class="logout-btn">Logout</a>
    </nav>
    <div class="icons">
        <a href="cart.php"><span title="Keranjang">🛒</span></a>
        <span id="search-icon" title="Pencarian">🔍</span>
    </div>
</header>

<!-- Kotak Pencarian (Slide Down) -->
<div class="search-container" id="searchContainer">
    <input 
        type="text" 
        id="globalSearch" 
        placeholder="Cari produk..." 
        style="width: 100%; max-width: 500px; padding: 12px 16px; border-radius: 30px; border: 1px solid #ccc; outline: none; font-size: 1rem;"
    />
</div>

<style>
    .search-container {
        display: none;
        justify-content: center;
        padding: 15px 40px;
        background-color: #f5f0e8;
        border-top: 1px solid #ddd;
    }
</style>

<script>
    // Toggle search box
    const searchIcon = document.getElementById('search-icon');
    const searchContainer = document.getElementById('searchContainer');
    const globalSearch = document.getElementById('globalSearch');

    searchIcon.addEventListener('click', function (e) {
        e.stopPropagation();
        searchContainer.style.display = searchContainer.style.display === 'flex' ? 'none' : 'flex';
        if (searchContainer.style.display === 'flex') globalSearch.focus();
    });

    // Tutup saat klik di luar
    document.addEventListener('click', function (e) {
        if (!searchContainer.contains(e.target) && e.target !== searchIcon) {
            searchContainer.style.display = 'none';
        }
    });

    // Jika di halaman products.php, tambahkan fungsi filter
    if (window.location.pathname.includes('products.php')) {
        globalSearch.addEventListener('input', function () {
            const query = this.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');

            cards.forEach(card => {
                const name = card.querySelector('h3').textContent.toLowerCase();
                const desc = card.querySelector('p').textContent.toLowerCase();
                const category = card.getAttribute('data-category').toLowerCase();

                if (name.includes(query) || desc.includes(query) || category.includes(query)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
</script>
</body>
</html>