<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$now_showing = $conn->query('SELECT * FROM movies WHERE status = "now_showing" ORDER BY release_date DESC')->fetch_all(MYSQLI_ASSOC);
$coming_soon = $conn->query('SELECT * FROM movies WHERE status = "coming_soon" ORDER BY release_date ASC')->fetch_all(MYSQLI_ASSOC);
$hero_movies = array_slice($now_showing, 0, 3);

$page_title = 'Savoy Cinema | Now Showing in Colombo';
$page_description = 'Book tickets for the latest movies now showing and coming soon at Savoy Cinema, Colombo. Real showtimes, real seats, real cinema.';
$active_page = 'home';
$include_carousel_js = true;
require 'includes/header.php';
?>

<section id="home" class="iq-main-slider p-0">
    <div id="home-slider" class="slider m-0 p-0">
        <?php foreach ($hero_movies as $movie): ?>
        <div class="slide slick-bg" style="background-image:url('<?php echo h($movie['backdrop_url']); ?>')">
            <div class="container-fluid position-relative h-100">
                <div class="slider-inner h-100">
                    <div class="row align-items-center h--100">
                        <div class="col-xl-6 col-lg-12 col-md-12">
                            <h1 class="slider-text big-title title text-uppercase" data-animation-in="fadeInLeft" data-delay-in="0.6">
                                <?php echo h($movie['title']); ?>
                            </h1>
                            <div class="d-flex flex-wrap align-items-center fadeInLeft animated" data-animation-in="fadeInLeft" style="opacity: 1">
                                <?php if ($movie['imdb_rating']): ?>
                                <div class="slider-ratting d-flex align-items-center mr-4 mt-2 mt-md-3">
                                    <i class="fa fa-star text-primary" aria-hidden="true"></i>
                                    <span class="text-white ml-2"><?php echo h($movie['imdb_rating']); ?> IMDb</span>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex align-items-center mt-2 mt-md-3">
                                    <span class="badge badge-secondary p-2"><?php echo h($movie['content_rating']); ?></span>
                                    <span class="ml-3"><?php echo h(friendly_runtime($movie['runtime_minutes'])); ?></span>
                                </div>
                            </div>
                            <p data-animation-in="fadeInUp"><?php echo h($movie['description']); ?></p>
                            <div class="trending-list" data-animation-in="fadeInUp" data-delay-in="1.2">
                                <div class="text-primary title tag">
                                    Genre:
                                    <span class="text-body"><?php echo h($movie['genre']); ?></span>
                                    <div class="hover-buttons">
                                        <span class="btn btn-hover iq-button">
                                            <a href="movie.php?id=<?php echo (int) $movie['id']; ?>" class="btn btn-hover">Book Now</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<div class="main-content">
    <section id="iq-favorites">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 overflow-hidden">
                    <div class="iq-main-header d-flex align-items-center justify-content-between">
                        <h2 class="main-title">Now Showing</h2>
                        <a href="movies.php?status=now_showing" class="iq-view-all">View All</a>
                    </div>
                    <div class="favorite-contens">
                        <ul class="favorites-slider list-inline row p-0 mb-0">
                            <?php foreach ($now_showing as $movie): ?>
                            <li class="slide-item">
                                <div class="block-images position-relative">
                                    <div class="img-box">
                                        <img src="<?php echo h($movie['poster_url']); ?>" class="img-fluid" loading="lazy" alt="<?php echo h($movie['title']); ?> movie poster" />
                                    </div>
                                    <div class="block-description">
                                        <h3 class="iq-title">
                                            <a href="movie.php?id=<?php echo (int) $movie['id']; ?>"><?php echo h($movie['title']); ?></a>
                                        </h3>
                                        <div class="movie-time d-flex align-items-center my-2">
                                            <div class="badge badge-secondary p-1 mr-2"><?php echo h($movie['content_rating']); ?></div>
                                            <span class="text-white"><?php echo h(friendly_runtime($movie['runtime_minutes'])); ?></span>
                                        </div>
                                        <div class="parallax-buttons">
                                            <a href="movie.php?id=<?php echo (int) $movie['id']; ?>" class="btn btn-hover">Book Now</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="iq-upcoming-movie">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 overflow-hidden">
                    <div class="iq-main-header d-flex align-items-center justify-content-between">
                        <h2 class="main-title">Coming Soon</h2>
                        <a href="movies.php?status=coming_soon" class="iq-view-all">View All</a>
                    </div>
                    <div class="favorite-contens">
                        <ul class="favorites-slider list-inline row p-0 mb-0">
                            <?php foreach ($coming_soon as $movie): ?>
                            <li class="slide-item">
                                <div class="block-images position-relative">
                                    <div class="img-box">
                                        <img src="<?php echo h($movie['poster_url']); ?>" class="img-fluid" loading="lazy" alt="<?php echo h($movie['title']); ?> movie poster" />
                                    </div>
                                    <div class="block-description">
                                        <h3 class="iq-title">
                                            <a href="movie.php?id=<?php echo (int) $movie['id']; ?>"><?php echo h($movie['title']); ?></a>
                                        </h3>
                                        <div class="movie-time d-flex align-items-center my-2">
                                            <div class="badge badge-secondary p-1 mr-2">In cinemas <?php echo h(friendly_date($movie['release_date'])); ?></div>
                                        </div>
                                        <div class="hover-buttons">
                                            <a href="movie.php?id=<?php echo (int) $movie['id']; ?>" class="btn btn-hover iq-button">
                                                <i class="fa fa-info-circle mr-1" aria-hidden="true"></i>
                                                Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="parallex" class="parallax-window">
        <div class="container-fluid h-100">
            <div class="row align-items-center justify-content-center h-100 parallaxt-details">
                <div class="col-lg-4 r-mb-23">
                    <div class="text-left">
                        <h2 class="parallax-heading">Experience cinema the way it's meant to be seen</h2>
                        <p>
                            4K digital projection, Dolby Atmos sound, and Savoy's signature comfort. Grab your seat before showtimes sell out.
                        </p>
                        <div class="parallax-buttons">
                            <a href="movies.php" class="btn btn-hover">Browse Movies</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="parallax-img">
                        <img src="images/parallax/KRAVEN.jpg" alt="Savoy Cinema auditorium" class="img-fluid w-100" loading="lazy" />
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require 'includes/footer.php'; ?>
