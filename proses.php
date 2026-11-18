<?php
require_once 'koneksi.php';

// Cek apakah tombol 'simpan' ditekan
if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama']);
    $hobi = trim($_POST['hobi']);

    // Validasi input kosong
    if (empty($nama) || empty($hobi)) {
        $_SESSION['flash_message'] = "Error: Nama dan Hobi tidak boleh kosong!";
        $_SESSION['flash_type'] = "danger";
        header("Location: index.php");
        exit();
    }

    // Eksekusi database
    try {
        $sql = "INSERT INTO users (nama, hobi) VALUES (:nama, :hobi)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama' => $nama,
            ':hobi' => $hobi
        ]);

        $_SESSION['flash_message'] = "Data berhasil ditambahkan!";
        $_SESSION['flash_type'] = "success";
        header("Location: index.php");
        exit();
        
    } catch (PDOException $e) {
        // Jika ada error database, tampilkan di layar
        echo "Error Database: " . $e->getMessage();
    }
}
?>
