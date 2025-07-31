<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../auth.php");
    exit();
}

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_produk = trim($_POST['nama_produk']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = floatval($_POST['harga']);
    $stok = intval($_POST['stok']);
    $kategori = $_POST['kategori'];

    // Upload gambar
    $target_dir = "uploads/";
    $gambar = basename($_FILES["gambar"]["name"]);
    $target_file = $target_dir . $gambar;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Validasi gambar
    $check = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($check === false) {
        die("File yang diupload bukan gambar.");
    }

    // Batasi jenis file
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Hanya file JPG, JPEG, PNG, dan GIF yang diperbolehkan.");
    }

    // Upload file
    if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, stock, category, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama_produk, $deskripsi, $harga, $stok, $kategori, $gambar]);

            header("Location: produk.php");
            exit();
        } catch (PDOException $e) {
            die("Gagal menyimpan produk: " . $e->getMessage());
        }
    } else {
        die("Gagal mengunggah gambar.");
    }
}