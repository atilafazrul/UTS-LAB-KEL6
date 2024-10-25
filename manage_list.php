<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('index.php');
}

$list_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$list_id) {
    redirect('dashboard.php');
}

$stmt = $pdo->prepare("SELECT * FROM lists WHERE id = ? AND user_id = ?");
$stmt->execute([$list_id, $_SESSION['user_id']]);
$list = $stmt->fetch();

if (!$list) {
    redirect('dashboard.php');
}

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE list_id = ? ORDER BY is_completed, id DESC");
$stmt->execute([$list_id]);
$tasks = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Daftar - To-Do App</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<video autoplay loop muted playsinline preload="auto" id="bgVideo">
  <source src="assets/videos/Background.mp4" type="video/mp4">
  Your browser does not support the video tag.
</video>
    <div class="container">
        <h1 class="app-title">Kelola Daftar: <?php echo htmlspecialchars($list['title']); ?></h1>
        
        <div class="filter-container">
            <input type="text" id="search" placeholder="Cari Tugas..." onkeyup="filterTasks()">
            <select id="statusFilter" onchange="filterTasks()">
                <option value="all">Semua Tugas</option>
                <option value="completed">Tugas Selesai</option>
                <option value="incomplete">Tugas Belum Selesai</option>
            </select>
        </div>

        <div class="task-list">
            <?php foreach ($tasks as $task): ?>
                <div class="task-item <?php echo $task['is_completed'] ? 'completed' : ''; ?>">
                    <div class="task-content">
                        <label class="custom-checkbox">
                            <input type="checkbox" <?php echo $task['is_completed'] ? 'checked' : ''; ?> 
                                   onchange="updateTaskStatus(<?php echo $task['id']; ?>, <?php echo $list_id; ?>)">
                            <span class="checkmark"></span>
                        </label>
                        <span class="task-text"><?php echo htmlspecialchars($task['description']); ?></span>
                    </div>
                    <button class="btn-danger" onclick="deleteTask(<?php echo $task['id']; ?>, <?php echo $list_id; ?>)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="POST" action="add_task.php" class="form">
            <input type="hidden" name="list_id" value="<?php echo $list_id; ?>">
            <input type="text" name="description" placeholder="Deskripsi Tugas" required>
            <button type="submit" class="btn btn-primary">Tambah Tugas</button>
        </form>
        <a href="dashboard.php" class="btn btn-secondary center-btn">Kembali ke Dashboard</a>
    </div>

    <script>
    function filterTasks() {
        const searchInput = document.getElementById('search').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const tasks = document.querySelectorAll('.task-item');

        tasks.forEach(task => {
            const taskDescription = task.querySelector('.task-text').textContent.toLowerCase();
            const isCompleted = task.classList.contains('completed');

            const matchesSearch = taskDescription.includes(searchInput);

            let matchesStatus = true;
            if (statusFilter === 'completed') {
                matchesStatus = isCompleted;
            } else if (statusFilter === 'incomplete') {
                matchesStatus = !isCompleted;
            }

            if (matchesSearch && matchesStatus) {
                task.style.display = '';
            } else {
                task.style.display = 'none';
            }
        });
    }

    function updateTaskStatus(taskId, listId) {
        fetch(`complete_task.php?id=${taskId}&list_id=${listId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const taskItem = document.querySelector(`.task-item:has(input[type="checkbox"][onchange*="${taskId}"])`);
                    taskItem.classList.toggle('completed');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function deleteTask(taskId, listId) {
        if (confirm('Anda yakin ingin menghapus tugas ini?')) {
            fetch(`delete_task.php?id=${taskId}&list_id=${listId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const taskItem = document.querySelector(`.task-item:has(button[onclick*="${taskId}"])`);
                        taskItem.remove();
                    } else {
                        alert('Gagal menghapus tugas');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    }
    </script>
</body>
</html>