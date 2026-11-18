<?php
session_start();

// Menyalakan pelapor error PHP agar tidak stuck putih polos
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "localhost";
$port = "5432";
$dbname = "belajar_db";
$user = ""; //kosong karena user u 
$password = "";

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Koneksi gagal: " . $e->getMessage();
}
?>
