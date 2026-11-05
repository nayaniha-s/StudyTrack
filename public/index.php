<?php
require_once __DIR__ . '/../config/database.php';

$statusFilter = $_GET['status'] ?? 'all';

$sql = "SELECT
            tasks.id,
            tasks.title,
            tasks.description,
            tasks.due_date,
            tasks.status,
            subjects.name AS subject_name
        FROM tasks
        JOIN subjects ON tasks.subject_id = subjects.id";

if ($statusFilter === 'pending' || $statusFilter === 'completed') {
    $sql .= " WHERE tasks.status = :status";
}

$sql .= " ORDER BY tasks.due_date ASC";

$stmt = $pdo->prepare($sql);

if ($statusFilter === 'pending' || $statusFilter === 'completed') {
    $stmt->execute(['status' => $statusFilter]);
} else {
    $stmt->execute();
}

$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$subjectSql = "SELECT id, name FROM subjects ORDER BY name ASC";
$subjectStmt = $pdo->query($subjectSql);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

$countSql = "SELECT
                COUNT(*) AS total,
                SUM(status = 'pending') AS pending,
                SUM(status = 'completed') AS completed
             FROM tasks";

$countStmt = $pdo->query($countSql);
$taskCounts = $countStmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>StudyTrack</title>
</head>
<body>
    <header>
        <h1>Welcome to StudyTrack</h1>
        <p>Your personal study planner.</p>
    </header>

    <section class="task-summary">
        <div class="summary-card">
            <h3>Total Tasks</h3>
            <p><?= (int) $taskCounts['total'] ?></p>
        </div>

        <div class="summary-card">
            <h3>Pending</h3>
            <p><?= (int) $taskCounts['pending'] ?></p>
        </div>

        <div class="summary-card">
            <h3>Completed</h3>
            <p><?= (int) $taskCounts['completed'] ?></p>
        </div>
    </section>

    <main>

        <section class="task-form-section">
            <h2>Add a New Task</h2>
            
            <form action="create-task.php" method="POST">
                <label for="title">Task title</label>
                <input type="text" id="title" name="title" required>
                
                <label for="description">Description</label>
                <textarea id="description" name="description"></textarea>
                
                <label for="subject_id">Subject</label>
                <select id="subject_id" name="subject_id" required>
                    <option value="">Choose a subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= (int) $subject['id'] ?>">
                            <?= htmlspecialchars($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="due_date">Due date</label>
                <input type="date" id="due_date" name="due_date">

                <button type="submit">Add Task</button>
            </form>
        </section>
        
        <form action="index.php" method="GET" class="filter-form">
            <label for="status">Filter tasks:</label>

            <select name="status" id="status" onchange="this.form.submit()">
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>
                    All tasks
                </option>
                <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>
                    Pending
                </option>
                <option value="completed" <?= $statusFilter === 'completed' ? 'selected' : '' ?>>
                    Completed
                </option>
            </select>
        </form>

        <h2>My Tasks</h2>

        <h2>Available Subjects</h2>
        <ul>
            <?php foreach ($subjects as $subject): ?>
                <li><?= htmlspecialchars($subject['name']) ?></li>
            <?php endforeach; ?>
        </ul>

        <?php if (count($tasks) > 0): ?>
            <div class="task-list">
                <?php foreach ($tasks as $task): ?>
                    <div class="task-card">
                        <h3><?= htmlspecialchars($task['title']) ?></h3>
                        <p><?= htmlspecialchars($task['description'] ?? '') ?></p>
                        <p><strong>Subject:</strong> <?= htmlspecialchars($task['subject_name']) ?></p>
                        <p><strong>Due date:</strong> <?= htmlspecialchars($task['due_date'] ?? 'Not set') ?></p>
                        <p class="task-status"><?= htmlspecialchars($task['status']) ?></p>

                        <a href="edit-task.php?id=<?= (int) $task['id'] ?>" class="edit-button">
                            Edit Task
                        </a>

                        <form action="delete-task.php" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this task?');">
                            <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">
                            <button type="submit" class="delete-button">Delete Task</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No tasks yet. Add your first task soon!</p>
        <?php endif; ?>
    </main>
</body>
</html>