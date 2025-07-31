<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: auth.php");
    exit();
}

include 'config.php';
include 'navbar.php'; 

// Ambil semua produk dari database
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>UThrift - Produk</title>
  <style>
    :root {
      --primary-color: #a38764;
      --secondary-color: #ece0d0;
      --dark-color: #3a2a22;
      --light-color: #d9cbbb;
      --border-color: #b8a693;
      --card-bg: #fff;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: var(--light-color);
      color: var(--dark-color);
      min-height: 100vh;
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
    }

    .logo img {
      height: 30px;
      margin-right: 10px;
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

    nav a:hover {
      color: var(--primary-color);
      background-color: rgba(163, 135, 100, 0.1);
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

    .icons {
      display: flex;
      gap: 15px;
      font-size: 1.2rem;
      cursor: pointer;
    }

    .icons span {
      transition: transform 0.3s ease;
    }

    .icons span:hover {
      transform: scale(1.1);
      color: var(--primary-color);
    }

    .main-container {
      padding: 60px 80px;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
    }

    .category-filter {
      width: 100%;
      text-align: center;
      margin-bottom: 40px;
    }

    .category-filter button {
      background-color: var(--primary-color);
      color: white;
      padding: 10px 20px;
      margin: 0 10px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    .category-filter button:hover {
      background-color: #8a6f4f;
    }

    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 25px;
      width: 100%;
    }

    .product-card {
      background-color: var(--card-bg);
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }

    .product-card:hover {
      transform: translateY(-5px);
    }

    .product-card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .product-info {
      padding: 15px;
    }

    .product-info h3 {
      font-size: 1.1rem;
      margin: 0 0 10px;
      color: var(--dark-color);
    }

    .product-info p {
      font-size: 0.95rem;
      color: #5a4a42;
      margin: 0 0 10px;
    }

    .product-info .price {
      font-weight: bold;
      color: var(--primary-color);
    }

    .btn {
      display: inline-block;
      margin-top: 10px;
      background-color: var(--primary-color);
      color: white;
      padding: 8px 16px;
      border-radius: 25px;
      text-decoration: none;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .btn:hover {
      background-color: #8a6f4f;
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .main-container {
        padding: 40px 20px;
      }

      .category-filter button {
        font-size: 0.9rem;
        padding: 8px 16px;
      }
    }
  </style>
</head>
<body>

  <div class="main-container">
    <!-- Filter Kategori -->
    <div class="category-filter">
      <button onclick="filterCategory('all')">Semua</button>
      <button onclick="filterCategory('Baju')">Baju</button>
      <button onclick="filterCategory('Celana')">Celana</button>
      <button onclick="filterCategory('Kemeja')">Kemeja</button>
    </div>

    <!-- Grid Produk -->
   <div class="product-grid" id="productGrid">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card" 
                 data-category="<?= htmlspecialchars($product['category']) ?>"
                 data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>"
                 data-desc="<?= htmlspecialchars(strtolower($product['description'])) ?>">
                <img src="asset/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <div class="product-info">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p class="price">Rp <?= number_format($product['price'], 0, ',', '.') ?></p>
                    <a href="detail_produk.php?id=<?= $product['id'] ?>" class="btn">Lihat Detail</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Tidak ada produk tersedia saat ini.</p>
    <?php endif; ?>
</div>
  </div>
  <?php include 'footer.php'; ?>

  <script>
    function filterCategory(category) {
      const cards = document.querySelectorAll('.product-card');
      cards.forEach(card => {
        if (category === 'all' || card.getAttribute('data-category') === category) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    }
  </script>
</body>
</html>