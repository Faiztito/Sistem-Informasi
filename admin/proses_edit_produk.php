<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: auth.php");
    exit();
}

include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    // Update produk
    $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ? WHERE id = ?");
    $stmt->execute([$name, $description, $price, $stock, $category, $id]);

    // Jika upload gambar baru
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $pdo->prepare("UPDATE products SET image = ? WHERE id = ?")->execute([$image, $id]);
        } else {
            die("Gagal mengunggah gambar.");
        }
    }

    header("Location: produk.php");
    exit();
}