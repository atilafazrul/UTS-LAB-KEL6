<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $new_password = $_POST['new_password'];

    $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
    if ($stmt->execute([$username, $email, $_SESSION['user_id']])) {
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed_password, $_SESSION['user_id']]);
        }
        $message = 'Profil berhasil diperbarui.';
    } else {
        $message = 'Gagal memperbarui profil. Silakan coba lagi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - To-Do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<video autoplay loop muted playsinline preload="auto" id="bgVideo">
  <source src="assets/videos/Background.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
    <div class="container">
        <h1 class="app-title">Profil Anda</h1>
        <?php if ($message): ?>
            <div class="success"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" class="form">
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            <input type="password" name="new_password" placeholder="Password Baru">
            <button type="submit" class="btn btn-primary">Perbarui Profil</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary center-btn">Kembali Ke Dashboard</a>
    </div>
</body>
</html>