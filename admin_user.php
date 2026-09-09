<?php
require_once 'connection.php';
require_once 'includes/functions.php';

if (empty($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit;
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $delete_id = (int) $_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM `users` WHERE id = ?');
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin_user.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Users | Savoy Cinema</title>
    <link rel="icon" href="images/logo1.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php if (isset($_GET['deleted'])): ?>
    <div class="message message--success" role="status">
        <span>User removed successfully.</span>
        <i class="bx bxs-circle" onclick="this.parentElement.remove()" role="button" tabindex="0" aria-label="Dismiss"></i>
    </div>
    <?php endif; ?>

    <div class="line4"></div>
    <section class="message-container">
        <h1 class="title">Total User Accounts</h1>
        <div class="box-container">
            <?php
            $result = $conn->query('SELECT * FROM `users` ORDER BY user_type DESC, name ASC');
            if ($result->num_rows > 0):
                while ($user = $result->fetch_assoc()):
            ?>
            <div class="box">
                <p>User ID: <span><?php echo (int) $user['id']; ?></span></p>
                <p>Name: <span><?php echo h($user['name']); ?></span></p>
                <p>Email: <span><?php echo h($user['email']); ?></span></p>
                <p>User Type: <span style="color: <?php echo $user['user_type'] === 'admin' ? 'orange' : 'inherit'; ?>"><?php echo h($user['user_type']); ?></span></p>
                <a href="admin_user.php?delete=<?php echo (int) $user['id']; ?>" onclick="return confirm('Delete this user?');">Delete</a>
            </div>
            <?php
                endwhile;
            else:
            ?>
            <div class="empty">
                <p>No users found!</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <script type="text/javascript" src="script.js"></script>
</body>
</html>
