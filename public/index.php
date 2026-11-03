<?php
require_once __DIR__ . '/../config/database.php';

$sql = "SELECT
            tasks.id,
            tasks.title,
            tasks.description,
            tasks.due_date,
            tasks.status,
            subjects.name AS subject_name
        FROM tasks
        JOIN subjects ON tasks.subject_id = subjects.id
        ORDER BY tasks.due_date ASC";

$stmt = $pdo->query($sql);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyTrack</title>
</head>
<body>
    <h1>Welcome to StudyTrack</h1>
    <p>Your personal study planner.</p>

    <h2>My Tasks</h2>

    <?php if (count($tasks) > 0): ?>
        <?php foreach ($tasks as $task): ?>
            <div>
                <h3><?= htmlspecialchars($task['title']) ?></h3>
                <p><?= htmlspecialchars($task['description'] ?? '') ?></p>
                <p>Subject: <?= htmlspecialchars($task['subject_name']) ?></p>
                <p>Due date: <?= htmlspecialchars($task['due_date'] ?? 'Not set') ?></p>
                <p>Status: <?= htmlspecialchars($task['status']) ?></p>
            </div>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tasks yet. Add your first task soon!</p>
    <?php endif; ?>
</body>
</html>