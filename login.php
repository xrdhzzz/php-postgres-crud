<?php
require_once 'koneksi.php';

// Jika sudah login, langsung lempar ke index.php
if (isset($_SESSION['user_logged_in'])) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM accounts WHERE username = :username");
    $stmt->execute([':username' => $username]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifikasi password dengan hash yang tersimpan di database
    if ($account && password_verify($password, $account['password'])) {
        // Set session login aktif
        $_SESSION['user_logged_in'] = true;
        $_SESSION['username'] = $account['username'];

        $_SESSION['flash_message'] = "Selamat datang kembali, " . $account['username'] . "!";
        $_SESSION['flash_type'] = "success";
        header("Location: index.php");
        exit();
    } else {
        $error = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - PHP App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; font-size: 18px; margin: 20px; background-color: #f9f9f9; }
        .container { background: white; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        input[type="text"], input[type="password"] { width: 100%; padding: 10px; font-size: 16px; margin-top: 5px; margin-bottom: 15px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; font-size: 16px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .error { color: red; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login Sistem</h2>
        <!-- Render Flash Message dari Register -->
        <?php if (isset($_SESSION['flash_message'])): ?>
            <div style="padding: 10px; margin-bottom: 15px; border-radius: 5px; background-color: #d4edda; color: #155724;">
                <?= $_SESSION['flash_message']; ?>
            </div>
            <?php unset($_SESSION['flash_message']); unset($_SESSION['flash_type']); ?>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="error"><?= $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="username" required><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br>

            <button type="submit" name="login">Login</button>
        </form>
        <p style="text-align: center; margin-top: 15px;">Belum punya akun? <a href="register.php">Register di sini</a></p>
    </div>
</body>
</html>
