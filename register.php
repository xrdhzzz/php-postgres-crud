<?php
require_once 'koneksi.php';

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Username dan Password tidak boleh kosong!";
    } else {
        // Enskripsi password menggunakan password_hash (Secure Hashing)
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO accounts (username, password) VALUES (:username, :password)");
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashedPassword
            ]);

            $_SESSION['flash_message'] = "Registrasi berhasil! Silakan login.";
            $_SESSION['flash_type'] = "success";
            header("Location: login.php");
            exit();
        } catch (PDOException $e) {
            $error = "Username sudah digunakan atau terjadi kesalahan database!";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - PHP App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; font-size: 18px; margin: 20px; background-color: #f9f9f9; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; font-size: 16px; margin-top: 5px; margin-bottom: 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; font-size: 16px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register Akun Baru</h2>
        <?php if (isset($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="username" required><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br>

            <button type="submit" name="register">Daftar</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
