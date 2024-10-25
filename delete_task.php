<?php
require_once 'config.php';

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Tidak diizinkan']);
    exit;
}

$task_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$list_id = filter_input(INPUT_GET, 'list_id', FILTER_VALIDATE_INT);

if (!$task_id || !$list_id) {
    echo json_encode(['success' => false, 'message' => 'ID tugas atau daftar tidak valid']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND list_id IN (SELECT id FROM lists WHERE user_id = ?)");
$result = $stmt->execute([$task_id, $_SESSION['user_id']]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal menghapus tugas']);
}
