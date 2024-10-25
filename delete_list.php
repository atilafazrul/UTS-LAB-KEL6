<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$list_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($list_id) {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE list_id = ? AND list_id IN (SELECT id FROM lists WHERE user_id = ?)");
        $stmt->execute([$list_id, $_SESSION['user_id']]);

        $stmt = $pdo->prepare("DELETE FROM lists WHERE id = ? AND user_id = ?");
        $stmt->execute([$list_id, $_SESSION['user_id']]);

        $pdo->commit();
        setFlashMessage('success', 'Daftar dan semua tugas di dalamnya berhasil dihapus.');
    } catch (Exception $e) {
        $pdo->rollBack();
        setFlashMessage('error', 'Gagal menghapus daftar. Silakan coba lagi.');
    }
}

redirect('dashboard.php');