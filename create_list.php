<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO lists (user_id, title) VALUES (?, ?)");
        if ($stmt->execute([$_SESSION['user_id'], $title])) {
            setFlashMessage('success', 'Daftar baru berhasil dibuat.');
            redirect('dashboard.php');
        } else {
            $error = 'Gagal membuat daftar baru. Silakan coba lagi.';
        }
    } else {
        $error = 'Judul daftar tidak boleh kosong.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Daftar Baru - To-Do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<video autoplay loop muted playsinline preload="auto" id="bgVideo">
  <source src="assets/videos/Background.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
    <div class="container">
        <h1>Buat Daftar Baru</h1>
        <?php if (isset($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST" class="form">
            <input type="text" name="title" placeholder="Judul Daftar" required>
            <button type="submit" class="btn btn-primary">Buat Daftar Baru</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary center-btn">Kembali ke Dashboard</a>
    </div>
</body>
</html>
