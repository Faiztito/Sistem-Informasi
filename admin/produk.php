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
    <title>UThrift - Admin Products</title>
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
        .sidebar {
            width: 200px;
            background-color: #8B5A2B;
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
            background-color: #f0ebe0;
            padding: 10px 20px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .header h3 {
            margin: 0;
            color: #333;
        }
        .products-container {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .products-container h2 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f8f8;
            font-weight: 600;
        }
        .action-btn {
            padding: 5px 10px;
            background-color: #b8a894;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
        }
        .new-product {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed #ccc;
        }
        .new-product h3 {
            margin-bottom: 10px;
        }
        #product-list table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
#product-list th, #product-list td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    text-align: left;
}
#product-list th {
    background-color: #f8f8f8;
}
#product-list img {
    max-width: 80px;
    height: auto;
}
.action-btn {
    padding: 5px 10px;
    background-color: #b8a894;
    color: white;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    margin-right: 5px;
    text-decoration: none;
    font-size: 0.9rem;
    display: inline-block;
    text-align: center;
    transition: background-color 0.3s;
}

.action-btn:hover {
    background-color: #a89784;
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

        <!-- Content -->
        <div class="content">
            <!-- Header -->
            <div class="header">
                <h3>Halo, <?php echo htmlspecialchars($_SESSION['admin']['username'] ?? 'Admin'); ?>!</h3>
            </div>

            <!-- Products Content -->
            <div class="products-container">
                <h2>Products List</h2>
<table>
    <thead>
        <tr>
            <th>Category Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Baju</td>
            <td>All sort of Accessories.</td>
            <td>
                <button class="action-btn" onclick="showProducts('Baju')">Lihat</button>
            </td>
        </tr>
        <tr>
            <td>Celana</td>
            <td>All sort of Decorations.</td>
            <td>
                <button class="action-btn" onclick="showProducts('Celana')">Lihat</button>
            </td>
        </tr>
        <tr>
            <td>Kemeja</td>
            <td>All sort of Gowns.</td>
            <td>
                <button class="action-btn" onclick="showProducts('Kemeja')">Lihat</button>
            </td>
        </tr>
    </tbody>
</table>
<div id="product-list" style="margin-top: 30px;"></div>
                <div class="new-product">
<a href="tambah_produk.php" style="display: inline-block; margin-bottom: 20px; padding: 10px 20px; background-color: #27ae60; color: white; text-decoration: none; border-radius: 5px;">+ Tambah Produk</a>                </div>
            </div>
            <!-- Modal Edit Produk -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeModal()">&times;</span>
        <h2>Edit Produk</h2>
        <form id="editForm" action="proses_edit_produk.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="name" id="edit_name" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="description" id="edit_description" required></textarea>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="price" id="edit_price" step="0.01" required>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stock" id="edit_stock" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="category" id="edit_category" required>
                    <option value="Baju">Baju</option>
                    <option value="Celana">Celana</option>
                    <option value="Kemeja">Kemeja</option>
                </select>
            </div>
            <div class="form-group">
                <label>Gambar Saat Ini</label><br>
                <img id="current_image" src="" width="100"><br><br>
                <label>Ganti Gambar (opsional)</label>
                <input type="file" name="image">
            </div>
            <button type="submit" class="login-btn">Simpan Perubahan</button>
        </form>
    </div>
</div>
<!-- Style untuk Modal -->
<style>
.modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-content {
    background-color: #fff;
    margin: 8% auto;
    padding: 30px;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    position: relative;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 24px;
    cursor: pointer;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 0.95rem;
}

.login-btn {
    width: 100%;
    padding: 12px;
    background-color: #b8a894;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1rem;
}

.login-btn:hover {
    background-color: #a89784;
}
</style>
        </div>
    </div>
    <script>
    function showProducts(category) {
        const productList = document.getElementById("product-list");
        productList.innerHTML = "<p>Loading products...</p>";

        fetch(`daftar_produk.php?category=${encodeURIComponent(category)}`)
            .then(response => {
                if (!response.ok) throw new Error("Network response was not ok");
                return response.text();
            })
            .then(data => {
                productList.innerHTML = data;
            })
            .catch(error => {
                productList.innerHTML = "<p>Gagal memuat produk.</p>";
                console.error("Error fetching products:", error);
            });
    }
</script>
<script>
    function openEditModal(id, name, description, price, stock, category, image) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_description').value = description;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_stock').value = stock;
        document.getElementById('edit_category').value = category;
        document.getElementById('current_image').src = 'uploads/' + image;
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('editModal').style.display = 'none';
    }

    // Tutup modal saat klik di luar
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
</script>
</body>
</html>