<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $description = sanitizeInput($_POST['description']);
    $list_id = filter_input(INPUT_POST, 'list_id', FILTER_VALIDATE_INT);

    if (!empty($description) && $list_id) {
        $stmt = $pdo->prepare("INSERT INTO tasks (list_id, description) VALUES (?, ?)");
        if ($stmt->execute([$list_id, $description])) {
            setFlashMessage('success', 'Tugas berhasil ditambahkan.');
        } else {
            setFlashMessage('error', 'Gagal menambahkan tugas. Silakan coba lagi.');
        }
    } else {
        setFlashMessage('error', 'Data tidak valid. Silakan coba lagi.');
    }
    redirect('manage_list.php?id=' . $list_id);
}

redirect('dashboard.php');
?>

