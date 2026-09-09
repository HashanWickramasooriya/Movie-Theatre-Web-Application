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
    $stmt = $conn->prepare('DELETE FROM `bookings` WHERE id = ?');
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin_booking.php?deleted=1');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Bookings | Savoy Cinema</title>
    <link rel="icon" href="images/logo1.png">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>
    <?php if (isset($_GET['deleted'])): ?>
    <div class="message message--success" role="status">
        <span>Booking removed successfully.</span>
        <i class="bx bxs-circle" onclick="this.parentElement.remove()" role="button" tabindex="0" aria-label="Dismiss"></i>
    </div>
    <?php endif; ?>

    <div class="line4"></div>
    <section class="order-container">
        <h1 class="title">Total Bookings</h1>
        <div class="box-container">
            <?php
            $result = $conn->query(
                'SELECT b.*, m.title AS movie_title, s.show_date, s.show_time, t.hall_code
                 FROM bookings b
                 JOIN showtimes s ON s.id = b.showtime_id
                 JOIN movies m ON m.id = s.movie_id
                 JOIN theatres t ON t.id = s.theatre_id
                 ORDER BY b.created_at DESC'
            );
            if ($result->num_rows > 0):
                while ($booking = $result->fetch_assoc()):
            ?>
            <div class="box">
                <p>Movie: <span><?php echo h($booking['movie_title']); ?></span></p>
                <p>Showtime: <span><?php echo h(friendly_date($booking['show_date'])); ?> at <?php echo h(friendly_time($booking['show_time'])); ?>, Hall <?php echo h($booking['hall_code']); ?></span></p>
                <p>Seats: <span><?php echo h(implode(', ', json_decode($booking['seat_codes'], true) ?: [])); ?></span></p>
                <p>Customer: <span><?php echo h($booking['customer_name']); ?> (<?php echo h($booking['customer_email']); ?>)</span></p>
                <p>Total: <span><?php echo h(money($booking['total_price'])); ?></span></p>
                <p>Reference: <span><?php echo h($booking['booking_reference']); ?></span></p>
                <a href="admin_booking.php?delete=<?php echo (int) $booking['id']; ?>" onclick="return confirm('Delete this booking?');">Delete</a>
            </div>
            <?php
                endwhile;
            else:
            ?>
            <div class="empty">
                <p>No bookings placed yet!</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <script type="text/javascript" src="script.js"></script>
</body>
</html>
