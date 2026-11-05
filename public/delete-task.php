<?php
require_once __DIR__ . '/../config/database.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Validate task ID
$taskId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$taskId) {
    exit('Invalid task ID.');
}

// Delete the task securely
$sql = "DELETE FROM tasks WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $taskId]);

// Return to dashboard
header('Location: index.php');
exit;