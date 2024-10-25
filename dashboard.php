<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'] ?? 'Pengguna'; 

if (!isset($_SESSION['username'])) {
    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();
    if ($user) {
        $username = $user['username'];
        $_SESSION['username'] = $username; 
    }
}

$stmt = $pdo->prepare("SELECT * FROM lists WHERE user_id = ?");
$stmt->execute([$userId]);
$lists = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - To-Do App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<video autoplay loop muted playsinline preload="auto" id="bgVideo">
  <source src="assets/videos/Background.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
    <div class="container">
        <header>
            <h1 class="app-title">To-Do App</h1>
            <nav>
                <a href="profile.php" class="btn">Profile</a>
                <a href="about.php" class="btn">About</a>
                <a href="logout.php" class="btn">Logout</a>
            </nav>
        </header>
        <main>
            <h2 class="welcome-message">Welcome, <?php echo htmlspecialchars($username); ?></h2>
            
            <div class="list-container">
                <?php foreach ($lists as $list): ?>
                    <div class="list-item">
                        <h3><?php echo htmlspecialchars($list['title']); ?></h3>
                        <div class="button-group">
                            <a href="manage_list.php?id=<?php echo $list['id']; ?>" class="btn btn-secondary">Kelola</a>
                            <a href="delete_list.php?id=<?php echo $list['id']; ?>" class="btn btn-danger" onclick="return confirm('Anda yakin ingin menghapus daftar ini?')">Hapus</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="create_list.php" class="btn btn-primary">Buat Daftar Baru</a>
        </main>
    </div>
</body>
</html>