<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$message = [];

if (isset($_POST['submit-btn'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $cpassword = (string) ($_POST['cpassword'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message[] = 'Please enter a valid name and email address.';
    } elseif (strlen($password) < 6) {
        $message[] = 'Password must be at least 6 characters long.';
    } elseif ($password !== $cpassword) {
        $message[] = 'Passwords do not match.';
    } else {
        $stmt = $conn->prepare('SELECT id FROM `users` WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            $message[] = 'An account with that email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO `users` (name, email, password) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $name, $email, $hash);
            $stmt->execute();
            $stmt->close();

            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Savoy Cinema</title>
    <meta name="description" content="Create a Savoy Cinema account to book tickets faster.">
    <link rel="icon" href="images/logo1.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <a href="index.php" class="auth-brand-link">Savoy Cinema</a>
    <section class="form-container">
        <?php foreach ($message as $item): ?>
        <div class="message" role="alert">
            <span><?php echo h($item); ?></span>
            <i class="bx bxs-circle" onclick="this.parentElement.remove()" role="button" tabindex="0" aria-label="Dismiss"></i>
        </div>
        <?php endforeach; ?>

        <form method="post" novalidate>
            <h1>Register Now</h1>
            <div class="input-field">
                <label for="name">Full name</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="input-field">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="input-field">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" minlength="6" required>
            </div>
            <div class="input-field">
                <label for="cpassword">Confirm password</label>
                <input type="password" id="cpassword" name="cpassword" placeholder="Confirm your password" minlength="6" required>
            </div>
            <input type="submit" name="submit-btn" value="Register Now" class="btn">
            <p>Already have an account? <a href="login.php">Login now</a></p>
        </form>
    </section>
</body>
</html>
