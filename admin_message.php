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
    $stmt = $conn->prepare('DELETE FROM `message` WHERE id = ?');
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin_message.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Messages | Savoy Cinema</title>
    <link rel="icon" href="images/logo1.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php if (isset($_GET['deleted'])): ?>
    <div class="message message--success" role="status">
        <span>Message removed successfully.</span>
        <i class="bx bxs-circle" onclick="this.parentElement.remove()" role="button" tabindex="0" aria-label="Dismiss"></i>
    </div>
    <?php endif; ?>

    <div class="line4"></div>
    <section class="message-container">
        <h1 class="title">Messages</h1>
        <div class="box-container">
            <?php
            $result = $conn->query('SELECT * FROM `message` ORDER BY created_at DESC');
            if ($result->num_rows > 0):
                while ($msg = $result->fetch_assoc()):
            ?>
            <div class="box">
                <p>Name: <span><?php echo h($msg['name']); ?></span></p>
                <p>Email: <span><?php echo h($msg['email']); ?></span></p>
                <p>Contact: <span><?php echo h($msg['contact']); ?></span></p>
                <p>Message: <span><?php echo h($msg['message']); ?></span></p>
                <a href="admin_message.php?delete=<?php echo (int) $msg['id']; ?>" onclick="return confirm('Delete this message?');">Delete</a>
            </div>
            <?php
                endwhile;
            else:
            ?>
            <div class="empty">
                <p>No messages yet!</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <script type="text/javascript" src="script.js"></script>
</body>
</html>
