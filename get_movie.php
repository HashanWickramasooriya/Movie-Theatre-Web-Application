<?php
require_once 'connection.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if (empty($_SESSION['admin_name'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authorized.']);
    exit;
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare('SELECT * FROM movies WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();

echo json_encode($movie ?: []);
