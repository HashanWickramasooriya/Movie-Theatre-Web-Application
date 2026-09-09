<?php
require_once __DIR__ . '/includes/functions.php';

if (!isset($_SESSION['admin_name'])) {
    $_SESSION['admin_name'] = "";
}

if (!isset($_SESSION['admin_email'])) {
    $_SESSION['admin_email'] = "";
}

// Logout is handled by whichever page includes this header, before any output starts.
?>
<header class="header">
    <div class="flex">
        <a href="admin_panel.php" class="logo"><img src="images/logo1.png" width="170" height="100" alt="Savoy Cinema admin"></a>
        <nav class="navbar">
            <a href="admin_panel.php">Home</a>
            <a href="admin_booking.php">Bookings</a>
            <a href="admin_movies.php">Movies</a>
            <a href="admin_user.php">Users</a>
            <a href="admin_message.php">Messages</a>
            <a href="index.php">Theater</a>
        </nav>
        <div class="icons">
            <i class="bx bxs-user" id="user-btn" role="button" tabindex="0" aria-label="Toggle account menu"></i>
            <i class="bx bxs-menu" id="menu-btn" role="button" tabindex="0" aria-label="Toggle navigation menu"></i>
        </div>

        <div class="user-box">
            <p>Username: <span><?php echo h($_SESSION['admin_name']); ?></span></p>
            <p>Email: <span><?php echo h($_SESSION['admin_email']); ?></span></p>
            <form method="post">
                <button type="submit" name="logout" class="logout-btn">Log Out</button>
            </form>
        </div>
    </div>
</header>
<div class="banner">
    <div class="detail">
        <h1>Admin Dashboard</h1>
    </div>
</div>
<div class="line"></div>
