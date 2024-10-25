<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang - To-Do App</title>
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
            <h2>Meet Our Group Members</h2>
            <div class="card-container">
                <div class="card">
                    <h3>Atila Fazrul Falah</h3>
                    <p>(00000111118)</p>
                </div>
                <div class="card">
                    <h3>Fahmy Alfarezi Waltz</h3>
                    <p>(00000112080)</p>
                </div>
                <div class="card">
                    <h3>Fedora Anestasia</h3>
                    <p>(00000091646)</p>
                </div>
                <div class="card">
                    <h3>Mira Sophia Ikhsawiyanthi</h3>
                    <p>(00000112778)</p>
                </div>
            </div>
            <h2 class="thank-you-message">Thank You For Using Our App!!</h2>
            <a href="dashboard.php" class="btn btn-secondary center-btn">Kembali ke Dashboard</a>
        </main>
    </div>
</body>
</html>