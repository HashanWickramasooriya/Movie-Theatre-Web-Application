<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$movies_result = $conn->query(
    'SELECT id, title, poster_url, runtime_minutes, content_rating FROM movies WHERE status = "now_showing" ORDER BY title ASC'
);
$movies = $movies_result->fetch_all(MYSQLI_ASSOC);

$showtimes_result = $conn->query(
    'SELECT s.id, s.movie_id, s.show_date, s.show_time, s.standard_price, s.premium_price, t.hall_code
     FROM showtimes s
     WHERE s.show_date >= CURDATE()
     ORDER BY s.show_date ASC, s.show_time ASC'
);
$showtimes_by_movie = [];
while ($row = $showtimes_result->fetch_assoc()) {
    $showtimes_by_movie[$row['movie_id']][$row['show_date']][] = [
        'id' => (int) $row['id'],
        'time' => friendly_time($row['show_time']),
        'hall' => $row['hall_code'],
        'standard_price' => (float) $row['standard_price'],
        'premium_price' => (float) $row['premium_price'],
    ];
}

foreach ($movies as &$movie) {
    $movie['id'] = (int) $movie['id'];
    $movie['runtime_label'] = friendly_runtime($movie['runtime_minutes']);
    $dates = [];
    foreach ($showtimes_by_movie[$movie['id']] ?? [] as $date => $times) {
        $dates[] = ['date' => $date, 'label' => friendly_date($date), 'showtimes' => $times];
    }
    $movie['dates'] = $dates;
}
unset($movie);

$preselect_showtime = filter_input(INPUT_GET, 'showtime', FILTER_VALIDATE_INT) ?: null;
$preselect_movie = filter_input(INPUT_GET, 'movie', FILTER_VALIDATE_INT) ?: null;

$page_title = 'Book Seats | Savoy Cinema';
$page_description = 'Choose your movie, showtime, and seats at Savoy Cinema.';
$active_page = 'book';
require 'includes/header.php';
?>

<section class="page-header">
    <div class="container-fluid">
        <h1>Book Your Seats</h1>
        <p>Pick a movie, choose a showtime, and select your seats.</p>
    </div>
</section>

<section class="booking-flow">
    <div class="container-fluid">
        <ol class="booking-steps" id="booking-steps">
            <li data-step="movie" class="is-active">1. Movie</li>
            <li data-step="showtime">2. Showtime</li>
            <li data-step="seats">3. Seats</li>
            <li data-step="details">4. Details</li>
            <li data-step="confirm">5. Confirmation</li>
        </ol>

        <div id="booking-error" class="booking-error" role="alert" hidden></div>

        <!-- Step 1: Movie -->
        <div class="booking-panel" id="panel-movie" data-panel="movie">
            <?php if (!$movies): ?>
            <p class="catalog-empty">There are no movies open for booking right now. Please check back soon.</p>
            <?php else: ?>
            <div class="booking-movie-grid">
                <?php foreach ($movies as $movie): ?>
                <button type="button" class="booking-movie-card" data-movie-id="<?php echo $movie['id']; ?>" <?php echo empty($movie['dates']) ? 'disabled aria-disabled="true"' : ''; ?>>
                    <img src="<?php echo h($movie['poster_url']); ?>" alt="<?php echo h($movie['title']); ?> movie poster" loading="lazy" width="150" height="225" />
                    <span class="booking-movie-title"><?php echo h($movie['title']); ?></span>
                    <span class="booking-movie-meta"><?php echo h($movie['runtime_label']); ?> &middot; <?php echo h($movie['content_rating']); ?></span>
                    <?php if (empty($movie['dates'])): ?><span class="booking-movie-meta">No upcoming showtimes</span><?php endif; ?>
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- Step 2: Showtime -->
        <div class="booking-panel" id="panel-showtime" data-panel="showtime" hidden>
            <button type="button" class="booking-back" data-back="movie">&larr; Change movie</button>
            <h2 id="showtime-movie-title"></h2>
            <div class="booking-date-tabs" id="booking-date-tabs"></div>
            <div class="showtime-list" id="booking-showtime-list"></div>
        </div>

        <!-- Step 3: Seats -->
        <div class="booking-panel" id="panel-seats" data-panel="seats" hidden>
            <button type="button" class="booking-back" data-back="showtime">&larr; Change showtime</button>
            <h2 id="seats-summary-title"></h2>
            <div class="seat-map-wrap">
                <div class="screen-indicator">Screen this way</div>
                <div id="seat-map" class="seat-map" aria-label="Seat map"></div>
                <ul class="seat-legend">
                    <li><span class="seat-swatch seat-swatch--available"></span> Available</li>
                    <li><span class="seat-swatch seat-swatch--selected"></span> Selected</li>
                    <li><span class="seat-swatch seat-swatch--occupied"></span> Occupied</li>
                    <li><span class="seat-swatch seat-swatch--premium"></span> Premium</li>
                </ul>
            </div>
            <div class="seat-summary-bar">
                <span id="seat-count-label">0 seats selected</span>
                <span id="seat-total-label">Rs. 0.00</span>
                <button type="button" class="btn-book" id="seats-continue" disabled>Continue</button>
            </div>
        </div>

        <!-- Step 4: Details -->
        <div class="booking-panel" id="panel-details" data-panel="details" hidden>
            <button type="button" class="booking-back" data-back="seats">&larr; Change seats</button>
            <h2>Your details</h2>
            <div class="booking-summary-card" id="booking-summary-card"></div>
            <form id="booking-details-form" class="booking-details-form" novalidate>
                <label for="customer-name">Full name</label>
                <input type="text" id="customer-name" name="customer_name" required maxlength="100" autocomplete="name" />
                <label for="customer-email">Email address</label>
                <input type="email" id="customer-email" name="customer_email" required autocomplete="email" />
                <button type="submit" class="btn-book" id="booking-submit">Confirm Booking</button>
            </form>
        </div>

        <!-- Step 5: Confirmation -->
        <div class="booking-panel" id="panel-confirm" data-panel="confirm" hidden>
            <div class="booking-confirmation" id="booking-confirmation"></div>
        </div>
    </div>
</section>

<script>
window.SAVOY_BOOKING_DATA = <?php echo json_encode(['movies' => $movies, 'preselectShowtime' => $preselect_showtime, 'preselectMovie' => $preselect_movie]); ?>;
</script>
<script src="js/booking.js"></script>

<?php require 'includes/footer.php'; ?>
