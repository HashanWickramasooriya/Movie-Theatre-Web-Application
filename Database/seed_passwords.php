<?php
/**
 * One-time setup script: sets real password_hash() values for the demo accounts
 * seeded by savoy_movie_theater.sql. Run this once after importing the database
 * (from a browser: http://localhost/.../Database/seed_passwords.php), then delete it.
 */

require_once __DIR__ . '/../connection.php';

$demo_accounts = [
    'hashan@gmail.com' => 'hashan123',
    'janith@gmail.com' => 'janith123',
    'roshan@gmail.com' => 'roshan123',
    'kasun@gmail.com'  => 'kasun123',
];

$stmt = $conn->prepare('UPDATE users SET password = ? WHERE email = ?');

$updated = [];
foreach ($demo_accounts as $email => $plain_password) {
    $hash = password_hash($plain_password, PASSWORD_DEFAULT);
    $stmt->bind_param('ss', $hash, $email);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        $updated[] = $email;
    }
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Demo password seed</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 640px; margin: 40px auto; line-height: 1.6;">
    <h1>Demo passwords updated</h1>
    <?php if ($updated): ?>
        <p>Hashed passwords were set for:</p>
        <ul>
            <?php foreach ($updated as $email): ?>
                <li><?php echo htmlspecialchars($email); ?></li>
            <?php endforeach; ?>
        </ul>
        <p>You can now log in with the plaintext passwords listed in README.md. Delete this file when you're done, it should never ship to a live server.</p>
    <?php else: ?>
        <p>No matching demo accounts were found, nothing was changed.</p>
    <?php endif; ?>
</body>
</html>
