<?php
require_once __DIR__ . '/../config/database.php';

// Only accept form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validate task ID
$taskId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$taskId) {
    exit('Invalid task ID.');
}

// Get form data
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
$dueDate = $_POST['due_date'] ?? '';
$status = $_POST['status'] ?? 'pending';

// Validate required fields
if ($title === '' || !$subjectId) {
    exit('Task title and subject are required.');
}

// Validate status
if (!in_array($status, ['pending', 'completed'], true)) {
    exit('Invalid task status.');
}

// Convert empty date to NULL
$dueDate = $dueDate === '' ? null : $dueDate;

// Update task securely
$sql = "UPDATE tasks
        SET title = :title,
            description = :description,
            subject_id = :subject_id,
            due_date = :due_date,
            status = :status
        WHERE id = :id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':title' => $title,
    ':description' => $description,
    ':subject_id' => $subjectId,
    ':due_date' => $dueDate,
    ':status' => $status,
    ':id' => $taskId
]);

// Return to dashboard
header('Location: index.php');
exit;