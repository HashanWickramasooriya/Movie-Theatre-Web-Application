<?php
require_once 'connection.php';
require_once 'includes/functions.php';

$movies = $conn->query('SELECT * FROM movies ORDER BY status ASC, release_date DESC')->fetch_all(MYSQLI_ASSOC);
$genres = [];
foreach ($movies as $movie) {
    foreach (array_map('trim', explode(',', $movie['genre'])) as $genre) {
        $genres[$genre] = true;
    }
}
ksort($genres);

$page_title = 'Movies | Savoy Cinema';
$page_description = 'Browse every movie now showing and coming soon at Savoy Cinema, with genres, runtimes, and ratings.';
$active_page = 'movies';
require 'includes/header.php';
?>

<section class="page-header">
    <div class="container-fluid">
        <h1>Movies</h1>
        <p>Everything playing at Savoy Cinema right now, and what's on the way.</p>
    </div>
</section>

<section class="movie-catalog">
    <div class="container-fluid">
        <div class="catalog-controls">
            <div class="catalog-search">
                <label for="movie-search" class="sr-only">Search movies</label>
                <i class="fa fa-search" aria-hidden="true"></i>
                <input type="search" id="movie-search" placeholder="Search movies by title..." autocomplete="off" />
            </div>
            <div class="catalog-filters">
                <label for="genre-filter">Genre</label>
                <select id="genre-filter">
                    <option value="">All genres</option>
                    <?php foreach (array_keys($genres) as $genre): ?>
                    <option value="<?php echo h($genre); ?>"><?php echo h($genre); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="status-filter">Showing</label>
                <select id="status-filter">
                    <option value="">All</option>
                    <option value="now_showing" <?php echo (($_GET['status'] ?? '') === 'now_showing') ? 'selected' : ''; ?>>Now Showing</option>
                    <option value="coming_soon" <?php echo (($_GET['status'] ?? '') === 'coming_soon') ? 'selected' : ''; ?>>Coming Soon</option>
                </select>
            </div>
        </div>

        <p id="movie-count" class="catalog-count" aria-live="polite"></p>

        <div class="movie-grid" id="movie-grid">
            <?php foreach ($movies as $movie): ?>
            <article class="movie-card" data-title="<?php echo h(strtolower($movie['title'])); ?>" data-genre="<?php echo h($movie['genre']); ?>" data-status="<?php echo h($movie['status']); ?>">
                <a href="movie.php?id=<?php echo (int) $movie['id']; ?>" class="movie-card-poster">
                    <img src="<?php echo h($movie['poster_url']); ?>" alt="<?php echo h($movie['title']); ?> movie poster" loading="lazy" width="300" height="450" />
                    <span class="movie-card-status movie-card-status--<?php echo h($movie['status']); ?>">
                        <?php echo $movie['status'] === 'now_showing' ? 'Now Showing' : 'Coming Soon'; ?>
                    </span>
                </a>
                <div class="movie-card-body">
                    <h2 class="movie-card-title"><a href="movie.php?id=<?php echo (int) $movie['id']; ?>"><?php echo h($movie['title']); ?></a></h2>
                    <p class="movie-card-meta"><?php echo h($movie['genre']); ?></p>
                    <p class="movie-card-meta movie-card-meta--muted">
                        <?php echo h(friendly_runtime($movie['runtime_minutes'])); ?> &middot; <?php echo h($movie['content_rating']); ?>
                        <?php if ($movie['imdb_rating']): ?> &middot; <i class="fa fa-star" aria-hidden="true"></i> <?php echo h($movie['imdb_rating']); ?><?php endif; ?>
                    </p>
                    <a href="movie.php?id=<?php echo (int) $movie['id']; ?>" class="btn-book"><?php echo $movie['status'] === 'now_showing' ? 'Book Tickets' : 'View Details'; ?></a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <p id="movie-empty" class="catalog-empty" hidden>No movies match your search. Try a different title or genre.</p>
    </div>
</section>

<script>
(function () {
    var search = document.getElementById('movie-search');
    var genreFilter = document.getElementById('genre-filter');
    var statusFilter = document.getElementById('status-filter');
    var cards = Array.prototype.slice.call(document.querySelectorAll('.movie-card'));
    var count = document.getElementById('movie-count');
    var empty = document.getElementById('movie-empty');

    var params = new URLSearchParams(window.location.search);
    if (params.get('status')) {
        statusFilter.value = params.get('status');
    }

    function applyFilters() {
        var term = search.value.trim().toLowerCase();
        var genre = genreFilter.value;
        var status = statusFilter.value;
        var visible = 0;

        cards.forEach(function (card) {
            var matchesTerm = !term || card.dataset.title.indexOf(term) !== -1;
            var matchesGenre = !genre || card.dataset.genre.split(',').map(function (g) { return g.trim(); }).indexOf(genre) !== -1;
            var matchesStatus = !status || card.dataset.status === status;
            var show = matchesTerm && matchesGenre && matchesStatus;
            card.hidden = !show;
            if (show) visible++;
        });

        count.textContent = visible + (visible === 1 ? ' movie found' : ' movies found');
        empty.hidden = visible !== 0;
    }

    search.addEventListener('input', applyFilters);
    genreFilter.addEventListener('change', applyFilters);
    statusFilter.addEventListener('change', applyFilters);
    applyFilters();
})();
</script>

<?php require 'includes/footer.php'; ?>
