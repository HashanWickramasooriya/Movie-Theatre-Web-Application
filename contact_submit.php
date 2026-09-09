<?php
require_once 'connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$contact = trim($_POST['contact'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $contact === '' || $message === '') {
    header('Location: contact.php?error=1');
    exit;
}

$stmt = $conn->prepare('INSERT INTO `message` (name, email, contact, message) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $name, $email, $contact, $message);
$success = $stmt->execute();
$stmt->close();

header('Location: contact.php?' . ($success ? 'sent=1' : 'error=1'));
exit;
