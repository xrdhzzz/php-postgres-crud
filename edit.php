<?php
require_once 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

// Ambil data user berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "Data tidak ditemukan!";
    exit();
}

// Jika tombol update ditekan
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $hobi = $_POST['hobi'];

    $updateStmt = $pdo->prepare("UPDATE users SET nama = :nama, hobi = :hobi WHERE id = :id");
    $updateStmt->execute([
        ':nama' => $nama,
        ':hobi' => $hobi,
        ':id'   => $id
    ]);

    header("Location: index.php?status=diupdate");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
</head>
<body>
    <h2>Edit Data User</h2>
    <form method="POST">
        <label>Nama:</label><br>
        <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required><br><br>

        <label>Hobi:</label><br>
        <input type="text" name="hobi" value="<?= htmlspecialchars($user['hobi']); ?>" required><br><br>

        <button type="submit" name="update">Simpan Perubahan</button>
    </form>
    <br>
    <a href="index.php">Kembali</a>
</body>
</html>
