<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$booking = null;
$not_found = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reference = strtoupper(trim($_POST['booking_reference'] ?? ''));
    $email = trim($_POST['customer_email'] ?? '');

    if ($reference !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $conn->prepare(
            'SELECT b.*, m.title AS movie_title, m.poster_url, s.show_date, s.show_time, t.hall_code
             FROM bookings b
             JOIN showtimes s ON s.id = b.showtime_id
             JOIN movies m ON m.id = s.movie_id
             JOIN theatres t ON t.id = s.theatre_id
             WHERE b.booking_reference = ? AND b.customer_email = ?'
        );
        $stmt->bind_param('ss', $reference, $email);
        $stmt->execute();
        $booking = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $not_found = !$booking;
    } else {
        $not_found = true;
    }
}

$page_title = 'My Booking | Savoy Cinema';
$page_description = 'Look up your Savoy Cinema booking using your reference number and email address.';
$active_page = 'book';
require 'includes/header.php';
?>

<section class="page-header">
    <div class="container-fluid">
        <h1>Find Your Booking</h1>
        <p>Enter the booking reference and email address you used when booking.</p>
    </div>
</section>

<section class="lookup-section">
    <div class="container-fluid">
        <form method="post" class="lookup-form">
            <label for="booking_reference">Booking reference</label>
            <input type="text" id="booking_reference" name="booking_reference" placeholder="e.g. SVY-8F2K1A" required value="<?php echo h($_POST['booking_reference'] ?? ''); ?>">

            <label for="customer_email">Email address</label>
            <input type="email" id="customer_email" name="customer_email" required value="<?php echo h($_POST['customer_email'] ?? ''); ?>">

            <button type="submit" class="btn-book">Find Booking</button>
        </form>

        <?php if ($booking): ?>
        <div class="booking-confirmation-card lookup-result">
            <img src="<?php echo h($booking['poster_url']); ?>" alt="" width="80" height="120" loading="lazy" />
            <div>
                <h3><?php echo h($booking['movie_title']); ?></h3>
                <p><?php echo h(friendly_date($booking['show_date'])); ?> at <?php echo h(friendly_time($booking['show_time'])); ?> &middot; Hall <?php echo h($booking['hall_code']); ?></p>
                <p>Seats: <?php echo h(implode(', ', json_decode($booking['seat_codes'], true) ?: [])); ?></p>
                <p>Booked by: <?php echo h($booking['customer_name']); ?></p>
                <p class="booking-summary-total">Total paid: <?php echo h(money($booking['total_price'])); ?></p>
                <p>Reference: <strong><?php echo h($booking['booking_reference']); ?></strong></p>
            </div>
        </div>
        <?php elseif ($not_found): ?>
        <p class="catalog-empty">We couldn't find a booking with that reference and email. Double-check the details from your confirmation and try again.</p>
        <?php endif; ?>

        <p class="lookup-cta">Don't have a booking yet? <a href="book.php">Book seats now</a>.</p>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
