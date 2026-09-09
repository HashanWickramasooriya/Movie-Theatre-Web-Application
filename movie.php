<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$movie_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$movie_id) {
    http_response_code(404);
    $page_title = 'Movie not found | Savoy Cinema';
    $active_page = 'movies';
    require 'includes/header.php';
    echo '<section class="page-header"><div class="container-fluid"><h1>Movie not found</h1><p>That movie does not exist. <a href="movies.php">Browse all movies</a>.</p></div></section>';
    require 'includes/footer.php';
    exit;
}

$stmt = $conn->prepare('SELECT * FROM movies WHERE id = ?');
$stmt->bind_param('i', $movie_id);
$stmt->execute();
$movie = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$movie) {
    http_response_code(404);
    $page_title = 'Movie not found | Savoy Cinema';
    $active_page = 'movies';
    require 'includes/header.php';
    echo '<section class="page-header"><div class="container-fluid"><h1>Movie not found</h1><p>That movie does not exist. <a href="movies.php">Browse all movies</a>.</p></div></section>';
    require 'includes/footer.php';
    exit;
}

$showtimes = [];
if ($movie['status'] === 'now_showing') {
    $stmt = $conn->prepare(
        'SELECT s.id, s.show_date, s.show_time, s.standard_price, s.premium_price, t.name AS theatre_name, t.hall_code
         FROM showtimes s JOIN theatres t ON t.id = s.theatre_id
         WHERE s.movie_id = ? AND s.show_date >= CURDATE()
         ORDER BY s.show_date ASC, s.show_time ASC'
    );
    $stmt->bind_param('i', $movie_id);
    $stmt->execute();
    $rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    foreach ($rows as $row) {
        $showtimes[$row['show_date']][] = $row;
    }
}

$page_title = $movie['title'] . ' | Savoy Cinema';
$page_description = mb_strimwidth($movie['description'], 0, 160, '...');
$og_image = $movie['poster_url'];
$active_page = 'movies';
require 'includes/header.php';
?>

<section class="movie-hero" style="background-image: linear-gradient(180deg, rgba(10,10,10,0.55), rgba(10,10,10,0.92)), url('<?php echo h($movie['backdrop_url']); ?>')">
    <div class="container-fluid">
        <div class="movie-hero-inner">
            <img class="movie-hero-poster" src="<?php echo h($movie['poster_url']); ?>" alt="<?php echo h($movie['title']); ?> movie poster" width="300" height="450" />
            <div class="movie-hero-info">
                <span class="movie-card-status movie-card-status--<?php echo h($movie['status']); ?>">
                    <?php echo $movie['status'] === 'now_showing' ? 'Now Showing' : 'Coming Soon'; ?>
                </span>
                <h1><?php echo h($movie['title']); ?></h1>
                <p class="movie-hero-meta">
                    <?php echo h($movie['genre']); ?><br>
                    <?php echo h(friendly_runtime($movie['runtime_minutes'])); ?> &middot;
                    <?php echo h($movie['content_rating']); ?> &middot;
                    <?php echo h($movie['language']); ?>
                    <?php if ($movie['imdb_rating']): ?> &middot; <i class="fa fa-star" aria-hidden="true"></i> <?php echo h($movie['imdb_rating']); ?> IMDb<?php endif; ?>
                </p>
                <p class="movie-hero-release">In cinemas <?php echo h(friendly_date($movie['release_date'])); ?></p>
                <p class="movie-hero-description"><?php echo h($movie['description']); ?></p>
                <?php if ($movie['status'] === 'now_showing' && $showtimes): ?>
                <a href="#showtimes" class="btn-book btn-book--lg">Book Tickets</a>
                <?php elseif ($movie['status'] === 'coming_soon'): ?>
                <span class="btn-book btn-book--lg btn-book--disabled" aria-disabled="true">Booking opens <?php echo h(friendly_date($movie['release_date'])); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php if ($movie['status'] === 'now_showing'): ?>
<section id="showtimes" class="movie-showtimes">
    <div class="container-fluid">
        <h2>Showtimes</h2>
        <?php if (!$showtimes): ?>
        <p class="catalog-empty">No upcoming showtimes are scheduled for this movie right now. Please check back soon.</p>
        <?php else: ?>
        <?php foreach ($showtimes as $date => $times): ?>
        <div class="showtime-day">
            <h3><?php echo h(friendly_date($date)); ?></h3>
            <div class="showtime-list">
                <?php foreach ($times as $show): ?>
                <a class="showtime-pill" href="book.php?showtime=<?php echo (int) $show['id']; ?>">
                    <span class="showtime-time"><?php echo h(friendly_time($show['show_time'])); ?></span>
                    <span class="showtime-hall"><?php echo h($show['hall_code']); ?></span>
                    <span class="showtime-price"><?php echo h(money($show['standard_price'])); ?>+</span>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>
