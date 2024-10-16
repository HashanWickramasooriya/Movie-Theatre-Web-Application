<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_name'])) {
    $_SESSION['admin_name'] = "";
}

if (!isset($_SESSION['admin_email'])) {
    $_SESSION['admin_email'] = "";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
      <link rel="stylesheet" type="text/css" href="admin_style.css">
    <title>Dashboard</title>
</head>
<body>
    <header class="header">
        <div class="flex">
            <a href="admin_panel.php" class="logo"><img src=images/logo1.png width="170" height="100"></a>
            <nav class="navbar">
            <a href="admin_panel.php">Home</a>
        <a href="admin_booking.php">Bookings</a>
        <a href="admin.html">Movies</a>
        <a href="admin_user.php">Users</a>
        <a href="admin_message.php">Messages</a>
        <a href="index.html">Theater </a>
            </nav>
            <div class="icons">
                <i class="bx bxs-user" id="user-btn"></i>
                <i class="bx bxs-menu" id="menu-btn"></i>
            </div>

            <?php
                
                if(isset($_POST['logout'])){ 
                    session_unset();            
                    session_destroy();
                    header("Location: login.php");
                    exit;
                }
            ?>
           
            <div class="user-box">
                <p>Username : <span><?php echo $_SESSION['admin_name']; ?></span></p>
                <p>Email : <span><?php echo $_SESSION['admin_email']; ?></span></p>
                <from method="post">
                  <a href="login.php"><button type="submit" name="logout" class="logout-btn">log out</button></a>
                </from>
            </div>
        </div>
    </header>
    <div class="banner">
        <div class="detail">
            <h1>admin dashboard</h1>
           
        </div>
    </div>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>