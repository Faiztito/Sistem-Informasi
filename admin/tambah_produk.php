<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UThrift - Tambah Produk</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f6f3;
        }

        .container {
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 200px;
            background-color: #b8a894;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
        }

        .sidebar h2 {
            margin-bottom: 20px;
            font-size: 1.4rem;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar li {
            margin-bottom: 15px;
        }

        .sidebar a {
            text-decoration: none;
            color: white;
            font-size: 1rem;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .sidebar a:hover {
            opacity: 0.9;
        }

        /* Konten Utama */
        .content {
            flex: 1;
            padding: 20px;
            margin-left: 240px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f0ebe0;
            padding: 10px 20px;
            margin-bottom: 30px;
            border-radius: 5px;
        }

        .header h3 {
            margin: 0;
            color: #333;
            font-size: 1.2rem;
        }

        /* Form Tambah Produk */
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            max-width: 600px;
            margin: 0 auto;
        }

        .form-container h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 0.95rem;
            box-sizing: border-box;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input[type="file"] {
            padding: 5px;
            font-size: 0.9rem;
        }

        .submit-btn {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 1rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }

        .submit-btn:hover {
            background-color: #219150;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }

            .form-container {
                margin: 0;
                padding: 20px;
            }
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
                <li><a href="transaksi.php">Transactions</a></li>
                <li><a href="../logout.php">Log Out</a></li>
            </ul>
        </div>

        <!-- Konten Utama -->
        <div class="content">
            <!-- Header -->
            <div class="header">
                <h3>Halo, <?php echo htmlspecialchars($_SESSION['admin']['username'] ?? 'Admin'); ?>!</h3>
            </div>

            <!-- Form Tambah Produk -->
            <div class="form-container">
                <h2>Tambah Produk Baru</h2>
                <form action="proses_tambah_produk.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <input type="text" id="nama_produk" name="nama_produk" required>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea id="deskripsi" name="deskripsi" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="harga">Harga (Rp)</label>
                        <input type="number" id="harga" name="harga" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" id="stok" name="stok" required>
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select id="kategori" name="kategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Baju">Baju</option>
                            <option value="Celana">Celana</option>
                            <option value="Kemeja">Kemeja</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gambar">Gambar Produk</label>
                        <input type="file" id="gambar" name="gambar" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="submit-btn">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>