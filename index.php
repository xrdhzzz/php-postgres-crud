<?php
require_once 'header.php';
// 1. Tentukan jumlah data per halaman
$limit = 5;

// 2. Ambil nomor halaman aktif dari URL (default halaman 1 jika tidak ada)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); // Mencegah halaman bernilai negatif atau 0

// 3. Hitung offset (titik awal data yang diambil)
$offset = ($page - 1) * $limit;

// Ambil keyword pencarian jika ada
$cari = $_GET['cari'] ?? '';
?>

<!-- Tombol Logout & Info User -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <span>Halo, <strong><?= htmlspecialchars($_SESSION['username'] ?? 'User'); ?></strong>!</span>
    <a href="logout.php" style="background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 4px; text-decoration: none;">Logout</a>
</div>

<hr>

<h2>Form Input Data & Search</h2>

<!-- Render Flash Message -->
<?php if (isset($_SESSION['flash_message'])): ?>
    <div class="alert alert-<?= $_SESSION['flash_type']; ?>">
        <?= $_SESSION['flash_message']; ?>
    </div>
    <?php 
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
    ?>
<?php endif; ?>

<form action="proses.php" method="POST">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br>

    <label>Hobi:</label><br>
    <input type="text" name="hobi" required><br>

    <button type="submit" name="simpan">Simpan Data</button>
</form>

<hr>

<!-- Form Search Bar -->
<form method="GET">
    <label>Cari Data:</label><br>
    <input type="text" name="cari" value="<?= htmlspecialchars($cari); ?>" placeholder="Ketik nama atau hobi..."><br>
    <button type="submit" class="btn-search">Cari</button>
    <?php if($cari != ''): ?>
        <a href="index.php" style="margin-left: 10px; color: red;">Reset</a>
    <?php endif; ?>
</form>

<hr>

<h3>Daftar Data dari Database:</h3>
<ul>
<?php
// 4. Hitung total data keseluruhan untuk menentukan jumlah total halaman
if ($cari != '') {
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE nama ILIKE :cari OR hobi ILIKE :cari");
    $countStmt->execute([':cari' => '%' . $cari . '%']);
} else {
    $countStmt = $pdo->query("SELECT COUNT(*) FROM users");
}
$totalData = $countStmt->fetchColumn();
$totalPages = ceil($totalData / $limit); // Membulatkan ke atas (total halaman)

// 5. Query data dengan LIMIT dan OFFSET
if ($cari != '') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nama ILIKE :cari OR hobi ILIKE :cari ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':cari', '%' . $cari . '%', PDO::PARAM_STR);
} else {
    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT :limit OFFSET :offset");
}

// Binding parameter LIMIT dan OFFSET secara aman menggunakan tipe integer
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

// Render data ke browser
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li>";
    echo "<strong>" . htmlspecialchars($row['nama']) . "</strong> - Hobi: " . htmlspecialchars($row['hobi']);
    echo " | <a href='edit.php?id=" . $row['id'] . "'>Edit</a>";
    echo " | <a href='hapus.php?id=" . $row['id'] . "' onclick='return confirm(\"Yakin ingin hapus data ini?\")' style='color:red;'>Hapus</a>";
    echo "</li>";
}
?>
</ul>

<!-- 6. Tampilkan Tombol Navigasi Halaman (Pagination Links) -->
<div style="margin-top: 20px;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
     <?php 
            // Membuat link URL yang aman dengan mempertahankan status pencarian jika ada
            $queryUrl = "page=" . $i;
            if (!empty($cari)) {
                $queryUrl .= "&cari=" . urlencode($cari);
            }
        ?>    
    <a href="index.php?<?= $queryUrl; ?>" 
           style="padding: 5px 10px; margin-right: 5px; border: 1px solid #ccc; 
           background-color: <?= ($page == $i) ? '#007BFF' : '#fff'; ?>; 
           color: <?= ($page == $i) ? '#fff' : '#007BFF'; ?>; border-radius: 3px;">
            <?= $i; ?>
        </a>
    <?php endfor; ?>
</div>

<?php
require_once 'footer.php';
?>
