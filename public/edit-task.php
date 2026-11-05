<?php
require_once __DIR__ . '/../config/database.php';

// Get the task ID from the URL
$taskId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$taskId) {
    exit('Invalid task ID.');
}

// Retrieve the selected task
$sql = "SELECT id, subject_id, title, description, due_date, status
        FROM tasks
        WHERE id = :id";

$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $taskId]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    exit('Task not found.');
}

// Retrieve subjects for the dropdown
$subjectSql = "SELECT id, name FROM subjects ORDER BY name ASC";
$subjectStmt = $pdo->query($subjectSql);
$subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - StudyTrack</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edit Task</h1>
        <p>Update your task details.</p>
    </header>

    <main>
        <section class="task-form-section">
            <form action="update-task.php" method="POST">
                <input type="hidden" name="id" value="<?= (int) $task['id'] ?>">

                <label for="title">Task title</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($task['title']) ?>"
                    required
                >

                <label for="description">Description</label>
                <textarea id="description" name="description"><?= htmlspecialchars($task['description'] ?? '') ?></textarea>

                <label for="subject_id">Subject</label>
                <select id="subject_id" name="subject_id" required>
                    <?php foreach ($subjects as $subject): ?>
                        <option
                            value="<?= (int) $subject['id'] ?>"
                            <?= $subject['id'] == $task['subject_id'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($subject['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="due_date">Due date</label>
                <input
                    type="date"
                    id="due_date"
                    name="due_date"
                    value="<?= htmlspecialchars($task['due_date'] ?? '') ?>"
                >

                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="pending" <?= $task['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $task['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                </select>

                <button type="submit">Save Changes</button>
            </form>

            <p><a href="index.php">Back to Dashboard</a></p>
        </section>
    </main>
</body>
</html>