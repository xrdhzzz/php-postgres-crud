<?php
require_once 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute([':id' => $id]);

    header("Location: index.php?status=dihapus");
    exit();
}
?>
