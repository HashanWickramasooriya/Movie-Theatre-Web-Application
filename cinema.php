<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$theatres = $conn->query('SELECT * FROM theatres ORDER BY hall_code ASC')->fetch_all(MYSQLI_ASSOC);

$page_title = 'Our Cinema | Savoy Cinema';
$page_description = 'Savoy Cinema in Colombo, Sri Lanka: three halls with 4K digital and laser projection, Dolby Atmos sound, and comfortable seating.';
$active_page = 'cinema';
require 'includes/header.php';
?>

<section class="page-header">
    <div class="container-fluid">
        <h1>Savoy Cinema</h1>
        <p>A landmark of Colombo's film scene since it first opened its doors.</p>
    </div>
</section>

<section class="cinema-about">
    <div class="container-fluid">
        <div class="cinema-about-grid">
            <div class="cinema-about-image">
                <img src="images/CINEMA/IMG1.jpg" alt="Savoy Cinema auditorium interior" loading="lazy" />
            </div>
            <div class="cinema-about-copy">
                <h2>About Savoy Cinema</h2>
                <p>Savoy Cinema, located in Colombo, Sri Lanka, is one of the country's most iconic and well loved movie theatres. Owned and operated by EAP Films and Theaters, Savoy is known for its long history and its modern cinematic upgrades: advanced sound systems and high quality digital projection built for an immersive night out.</p>
                <p>We screen everything from Hollywood blockbusters to popular Bollywood titles and local Sinhala films, with comfortable seating and a welcoming atmosphere that keeps locals and visitors coming back for the latest releases.</p>
                <p>Savoy also hosts special previews, premieres, and festival screenings, making it a genuine cultural hub in Colombo's entertainment scene.</p>
            </div>
        </div>
    </div>
</section>

<section class="cinema-halls">
    <div class="container-fluid">
        <h2>Our Halls</h2>
        <div class="hall-grid">
            <?php foreach ($theatres as $theatre): ?>
            <div class="hall-card">
                <span class="hall-code"><?php echo h($theatre['hall_code']); ?></span>
                <h3><?php echo h($theatre['name']); ?></h3>
                <p><?php echo h($theatre['projection_type']); ?></p>
                <p class="hall-capacity"><?php echo (int) ($theatre['total_rows'] * $theatre['seats_per_row']); ?> seats</p>
                <a href="book.php" class="btn-book">View Showtimes</a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
