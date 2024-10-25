<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

    if (empty($email)) {
        $message = 'Silakan masukkan alamat email Anda.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Format email tidak valid.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
        
            $new_password = bin2hex(random_bytes(8));
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            $update_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
            $update_stmt->execute([$hashed_password, $email]);

            $message = "Password baru Anda adalah: " . $new_password . " Silakan login dengan password ini dan segera ubah password Anda.";
        } else {
           
            $message = 'Jika email terdaftar, instruksi reset password akan dikirim ke alamat tersebut.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - To-Do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<video autoplay loop muted playsinline preload="auto" id="bgVideo">
  <source src="assets/videos/Background.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
    <div class="container">
        <h1 class="app-title">To-Do App</h1>
        <h2>Lupa Password</h2>
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8')); ?></div>
        <?php endif; ?>
        <form method="POST" class="form">
            <input type="email" name="email" placeholder="Masukkan email Anda" required>
            <button type="submit" class="btn">Reset Password</button>
        </form>
        <p class="text-center">Ingat password Anda? <a href="index.php" class="link">Login di sini</a></p>
    </div>
</body>
</html>