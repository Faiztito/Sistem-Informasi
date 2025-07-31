<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: auth.php");
    exit();
}

include '../config.php';

$id = $_GET['id'] ?? '';
if (empty($id)) {
    die("Produk tidak ditemukan.");
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header("Location: produk.php");
exit();