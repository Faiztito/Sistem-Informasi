<?php
// Konfigurasi database
$host = "localhost";
$dbname = "tokobaju";
$username = "root"; 
$password = ""; 

try {
    // Membuat koneksi PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Set error mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}