<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['list_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$list_id = filter_input(INPUT_GET, 'list_id', FILTER_VALIDATE_INT); // Memvalidasi list_id
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE list_id = ? AND list_id IN (SELECT list_id FROM lists WHERE user_id = ?)");
$stmt->execute([$list_id, $user_id]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($tasks);