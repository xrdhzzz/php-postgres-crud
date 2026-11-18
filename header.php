<?php
// Pastikan koneksi dan session selalu aktif di header
require_once 'koneksi.php';
?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Auth: Jika belum login, tendang ke halaman login
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}
require_once 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aplikasi CRUD PHP & PostgreSQL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; font-size: 18px; margin: 20px; background-color: #f9f9f9; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input[type="text"] { width: 100%; padding: 10px; font-size: 16px; margin-top: 5px; margin-bottom: 15px; box-sizing: border-box; }
        button { padding: 10px 20px; font-size: 16px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .btn-search { background-color: #007BFF; }
        ul { padding-left: 20px; }
        li { margin-bottom: 10px; }
        a { text-decoration: none; color: #007BFF; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .alert-success { background-color: #d4edda; color: #155724; }
        .alert-danger { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
