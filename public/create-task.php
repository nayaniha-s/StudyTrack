<?php
require_once __DIR__ . '/../config/database.php';

// Only accept form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Get and validate form data
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$subjectId = filter_input(INPUT_POST, 'subject_id', FILTER_VALIDATE_INT);
$dueDate = $_POST['due_date'] ?? '';

if ($title === '' || !$subjectId) {
    exit('Task title and subject are required.');
}

// Convert an empty due date to NULL
$dueDate = $dueDate === '' ? null : $dueDate;

// Insert task securely using a prepared statement
$sql = "INSERT INTO tasks (user_id, subject_id, title, description, due_date)
        VALUES (:user_id, :subject_id, :title, :description, :due_date)";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':user_id' => 1,
    ':subject_id' => $subjectId,
    ':title' => $title,
    ':description' => $description,
    ':due_date' => $dueDate
]);

// Return to the dashboard
header('Location: index.php');
exit;