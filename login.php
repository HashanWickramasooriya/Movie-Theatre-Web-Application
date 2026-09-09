<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$message = [];

if (isset($_GET['registered'])) {
    $message[] = 'Account created. You can now log in.';
}

if (isset($_POST['submit-btn'])) {
    $email = trim($_POST['email'] ?? '');
    $password = (string) ($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $message[] = 'Please enter a valid email and password.';
    } else {
        $stmt = $conn->prepare('SELECT * FROM `users` WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            if ($user['user_type'] === 'admin') {
                $_SESSION['admin_name'] = $user['name'];
                $_SESSION['admin_email'] = $user['email'];
                $_SESSION['admin_id'] = $user['id'];
                header('Location: admin_panel.php');
                exit;
            }
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_id'] = $user['id'];
            header('Location: index.php');
            exit;
        }

        $message[] = 'Incorrect email or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Savoy Cinema</title>
    <meta name="description" content="Log in to your Savoy Cinema account.">
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
            <h1>Login</h1>
            <div class="input-field">
                <label for="email">Your email</label>
                <input type="email" id="email" name="email" placeholder="enter your email" required>
            </div>

            <div class="input-field">
                <label for="password">Your password</label>
                <input type="password" id="password" name="password" placeholder="enter your password" required>
            </div>
            <input type="submit" name="submit-btn" value="Login" class="btn">
            <p>Don't have an account? <a href="register.php">Register now</a></p>
        </form>
    </section>
</body>
</html>
