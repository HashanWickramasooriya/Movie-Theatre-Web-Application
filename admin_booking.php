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

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    $stmt = $conn->prepare("DELETE FROM `bookings` WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $message[] = 'Booking removed successfully';
    header('location:admin_booking.php');
}
?>
<style type="text/css">
    <?php 
    include 'admin_style.css';
    ?>
    body {
        background-color: #d6efff;
        background-image: linear-gradient(43deg, #d6efff 0%, #1f1313 46%, #ef9c1c 100%);
        font-family: Arial, sans-serif;
    }

    h1 {
        color: aliceblue;
    }
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <title>Admin - Booking Page</title>
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '
                <div class="message">
                    <span>' . $message . '</span>
                    <i class="bx bxs-circle" onclick="this.parentElement.remove()"></i>
                </div>
            ';
        }
    }
    ?>
    <div class="line4"></div>
    <section class="order-container">
        <h1 class="title">Total Bookings</h1>
        <div class="box-container">
            <?php 
            $select_bookings = $conn->prepare("SELECT * FROM `bookings`");
            $select_bookings->execute();
            $result = $select_bookings->get_result();
            if ($result->num_rows > 0) {
                while ($fetch_bookings = $result->fetch_assoc()) {
            ?>
            <div class="box">
                <p>Movie Title: <span><?php echo $fetch_bookings['movie_title']; ?></span></p>
                <p>Show Time: <span><?php echo $fetch_bookings['show_time']; ?></span></p>
                <p>Seat Type: <span><?php echo $fetch_bookings['seat_type']; ?></span></p>
                <p>Seat Number: <span><?php echo $fetch_bookings['seat_number']; ?></span></p>
                <p>Name: <span><?php echo $fetch_bookings['name']; ?></span></p>
                <p>Booking Time: <span><?php echo $fetch_bookings['booking_time']; ?></span></p>
                <form method="post">
                    <input type="hidden" name="booking_id" value="<?php echo $fetch_bookings['id']; ?>">
                    <a href="admin_booking.php?delete=<?php echo $fetch_bookings['id']; ?>" onclick="return confirm('Delete this booking?');">Delete</a>
                </form>
            </div>
            <?php 
                }
            } else {
                echo '
                <div class="empty">
                    <p>No bookings placed yet!</p>
                </div>                             
                ';
            }
            ?>
        </div>
    </section>
    
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
