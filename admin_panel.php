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

$num_of_bookings = $conn->query('SELECT COUNT(*) AS n FROM `bookings`')->fetch_assoc()['n'];
$num_of_movies = $conn->query('SELECT COUNT(*) AS n FROM `movies`')->fetch_assoc()['n'];
$num_of_regular_users = $conn->query("SELECT COUNT(*) AS n FROM `users` WHERE user_type = 'user'")->fetch_assoc()['n'];
$num_of_admins = $conn->query("SELECT COUNT(*) AS n FROM `users` WHERE user_type = 'admin'")->fetch_assoc()['n'];
$num_of_users_total = $conn->query('SELECT COUNT(*) AS n FROM `users`')->fetch_assoc()['n'];
$num_of_messages = $conn->query('SELECT COUNT(*) AS n FROM `message`')->fetch_assoc()['n'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Savoy Cinema</title>
    <link rel="icon" href="images/logo1.png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <div class="line4"></div>
    <section class="dashboard">
        <div class="box-container">
            <div class="box">
                <h3><?php echo (int) $num_of_bookings; ?></h3>
                <p>Bookings</p>
            </div>
            <div class="box">
                <h3><?php echo (int) $num_of_movies; ?></h3>
                <p>Movies added</p>
            </div>
            <div class="box">
                <h3><?php echo (int) $num_of_regular_users; ?></h3>
                <p>Total normal users</p>
            </div>
            <div class="box">
                <h3><?php echo (int) $num_of_admins; ?></h3>
                <p>Total admins</p>
            </div>
            <div class="box">
                <h3><?php echo (int) $num_of_users_total; ?></h3>
                <p>Total registered users</p>
            </div>
            <div class="box">
                <h3><?php echo (int) $num_of_messages; ?></h3>
                <p>New messages</p>
            </div>
        </div>
    </section>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
