<?php

    include 'connection.php';
    session_start();
    $admin_id = $_SESSION['admin_name'];

    if (!isset($admin_id)) {
        header('location:login.php');
    }

    if (isset($_POST['logout'])) {
       session_destroy();
       header('location:login.php');
    }
?>
<style type="text/css">
    <?php 
        include 'admin_style.css';
    ?>
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <title>admin panel</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <div class="line4"></div>
    <section class="dashboard">
        <div class="box-container">
            

        <div class="box">
                <?php 
                  
                    $select_bookings = mysqli_query($conn, "SELECT * FROM `bookings`") or die('query failed');
                    $num_of_bookings = mysqli_num_rows($select_bookings);
                ?>
                <h3><?php echo  $num_of_bookings; ?></h3>
                <p>Bookings</p>
            </div>
            <div class="box">
                <?php 
                  
                    $select_movies = mysqli_query($conn, "SELECT * FROM `movies`") or die('query failed');
                    $num_of_movies  = mysqli_num_rows($select_movies );
                ?>
                <h3><?php echo  $num_of_movies ; ?></h3>
                <p>movies added</p>
            </div>
            <div class="box">
                <?php 
                  
                    $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'user'") or die('query failed');
                    $num_of_users  = mysqli_num_rows($select_users );
                ?>
                <h3><?php echo  $num_of_users; ?></h3>
                <p>total normal users</p>
            </div>
            <div class="box">
                <?php 
                  
                    $select_admin = mysqli_query($conn, "SELECT * FROM `users` WHERE user_type = 'admin'") or die('query failed');
                    $num_of_admin  = mysqli_num_rows($select_admin );
                ?>
                <h3><?php echo  $num_of_admin; ?></h3>
                <p>total admin</p>
            </div>
            <div class="box">
                <?php 
                    $select_users = mysqli_query($conn, "SELECT * FROM `users`") or die('query failed');
                    $num_of_users   = mysqli_num_rows($select_users  );
                ?>
                <h3><?php echo  $num_of_users ; ?></h3>
                <p>total registered users </p>
            </div>
            <div class="box">
                <?php 
                    $select_message = mysqli_query($conn, "SELECT * FROM `message`") or die('query failed');
                    $num_of_message  = mysqli_num_rows($select_message );
                ?>
                <h3><?php echo  $num_of_message; ?></h3>
                <p>new messages</p>
            </div>
        </div>
    </section>
    <div class="line"></div>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>