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

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = ? AND list_id IN (SELECT id FROM lists WHERE user_id = ?)");
$stmt->execute([$task_id, $_SESSION['user_id']]);
$task = $stmt->fetch();

if (!$task) {
    echo json_encode(['success' => false, 'message' => 'Tugas tidak ditemukan']);
    exit;
}

$new_status = $task['is_completed'] ? 0 : 1;

$stmt = $pdo->prepare("UPDATE tasks SET is_completed = ? WHERE id = ?");
$result = $stmt->execute([$new_status, $task_id]);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status tugas']);
}