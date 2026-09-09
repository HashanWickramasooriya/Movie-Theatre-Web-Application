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

$feedback = null;

function movie_fields_from_post(): array
{
    return [
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'genre' => trim($_POST['genre'] ?? ''),
        'language' => trim($_POST['language'] ?? 'English'),
        'runtime_minutes' => (int) ($_POST['runtime_minutes'] ?? 0),
        'content_rating' => trim($_POST['content_rating'] ?? ''),
        'imdb_rating' => ($_POST['imdb_rating'] ?? '') === '' ? null : (float) $_POST['imdb_rating'],
        'release_date' => trim($_POST['release_date'] ?? ''),
        'status' => in_array($_POST['status'] ?? '', ['now_showing', 'coming_soon'], true) ? $_POST['status'] : 'coming_soon',
        'poster_url' => trim($_POST['poster_url'] ?? ''),
        'backdrop_url' => trim($_POST['backdrop_url'] ?? ''),
    ];
}

if (isset($_POST['add_movie'])) {
    $m = movie_fields_from_post();
    if ($m['title'] === '' || $m['poster_url'] === '') {
        $feedback = ['type' => 'error', 'text' => 'Title and poster URL are required.'];
    } else {
        $stmt = $conn->prepare(
            'INSERT INTO movies (title, description, genre, language, runtime_minutes, content_rating, imdb_rating, release_date, status, poster_url, backdrop_url)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'ssssisdssss',
            $m['title'], $m['description'], $m['genre'], $m['language'], $m['runtime_minutes'],
            $m['content_rating'], $m['imdb_rating'], $m['release_date'], $m['status'], $m['poster_url'], $m['backdrop_url']
        );
        $stmt->execute();
        $stmt->close();
        $feedback = ['type' => 'success', 'text' => 'Movie added.'];
    }
}

if (isset($_POST['update_movie'])) {
    $id = (int) ($_POST['id'] ?? 0);
    $m = movie_fields_from_post();
    if ($id <= 0 || $m['title'] === '' || $m['poster_url'] === '') {
        $feedback = ['type' => 'error', 'text' => 'Title and poster URL are required.'];
    } else {
        $stmt = $conn->prepare(
            'UPDATE movies SET title=?, description=?, genre=?, language=?, runtime_minutes=?, content_rating=?,
             imdb_rating=?, release_date=?, status=?, poster_url=?, backdrop_url=? WHERE id=?'
        );
        $stmt->bind_param(
            'ssssisdssssi',
            $m['title'], $m['description'], $m['genre'], $m['language'], $m['runtime_minutes'],
            $m['content_rating'], $m['imdb_rating'], $m['release_date'], $m['status'], $m['poster_url'], $m['backdrop_url'], $id
        );
        $stmt->execute();
        $stmt->close();
        $feedback = ['type' => 'success', 'text' => 'Movie updated.'];
    }
}

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $stmt = $conn->prepare('DELETE FROM movies WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header('Location: admin_movies.php');
    exit;
}

$movies = $conn->query('SELECT * FROM movies ORDER BY status ASC, release_date DESC')->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Manage Movies</title>
    <link rel="icon" href="images/logo1.png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="admin_style.css">
</head>
<body>
    <?php include 'admin_header.php'; ?>

    <section class="movie-admin">
        <?php if ($feedback): ?>
        <div class="message <?php echo $feedback['type'] === 'success' ? 'message--success' : ''; ?>" role="status">
            <span><?php echo h($feedback['text']); ?></span>
            <i class="bx bxs-circle" onclick="this.parentElement.remove()" role="button" tabindex="0" aria-label="Dismiss"></i>
        </div>
        <?php endif; ?>

        <h1 class="title">Manage Movies</h1>

        <form action="admin_movies.php" method="post" class="movie-admin-form" id="movie-form">
            <input type="hidden" name="id" id="movie-id" value="">
            <div class="form-grid">
                <div>
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div>
                    <label for="genre">Genre (comma separated)</label>
                    <input type="text" name="genre" id="genre" required>
                </div>
                <div>
                    <label for="language">Language</label>
                    <input type="text" name="language" id="language" value="English" required>
                </div>
                <div>
                    <label for="runtime_minutes">Runtime (minutes)</label>
                    <input type="number" name="runtime_minutes" id="runtime_minutes" min="1" max="500" required>
                </div>
                <div>
                    <label for="content_rating">Content rating</label>
                    <input type="text" name="content_rating" id="content_rating" placeholder="PG-13" required>
                </div>
                <div>
                    <label for="imdb_rating">IMDb rating (optional)</label>
                    <input type="number" name="imdb_rating" id="imdb_rating" step="0.1" min="0" max="10">
                </div>
                <div>
                    <label for="release_date">Release date</label>
                    <input type="date" name="release_date" id="release_date" required>
                </div>
                <div>
                    <label for="status">Status</label>
                    <select name="status" id="status" required>
                        <option value="now_showing">Now Showing</option>
                        <option value="coming_soon">Coming Soon</option>
                    </select>
                </div>
                <div class="form-grid-full">
                    <label for="poster_url">Poster image URL</label>
                    <input type="url" name="poster_url" id="poster_url" placeholder="https://image.tmdb.org/t/p/w500/..." required>
                </div>
                <div class="form-grid-full">
                    <label for="backdrop_url">Backdrop image URL</label>
                    <input type="url" name="backdrop_url" id="backdrop_url" placeholder="https://image.tmdb.org/t/p/w1280/...">
                </div>
                <div class="form-grid-full">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" rows="4" required></textarea>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="add_movie" value="1" id="submit-add">Add Movie</button>
                <button type="submit" name="update_movie" value="1" id="submit-update" hidden>Save Changes</button>
                <button type="button" id="cancel-edit" hidden>Cancel Edit</button>
            </div>
        </form>

        <table class="movie-admin-table">
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Status</th>
                    <th>Release Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movies as $movie): ?>
                <tr>
                    <td><img src="<?php echo h($movie['poster_url']); ?>" alt="<?php echo h($movie['title']); ?> poster" width="50" loading="lazy"></td>
                    <td><?php echo h($movie['title']); ?></td>
                    <td><?php echo h($movie['genre']); ?></td>
                    <td><?php echo $movie['status'] === 'now_showing' ? 'Now Showing' : 'Coming Soon'; ?></td>
                    <td><?php echo h(friendly_date($movie['release_date'])); ?></td>
                    <td>
                        <button type="button" class="edit-movie-btn" data-id="<?php echo (int) $movie['id']; ?>">Edit</button>
                        <a href="admin_movies.php?delete=<?php echo (int) $movie['id']; ?>" onclick="return confirm('Delete this movie? This also removes its showtimes.');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <script>
        document.querySelectorAll('.edit-movie-btn').forEach(function (button) {
            button.addEventListener('click', function () {
                fetch('get_movie.php?id=' + encodeURIComponent(button.dataset.id))
                    .then(function (r) { return r.json(); })
                    .then(function (movie) {
                        if (!movie || !movie.id) return;
                        document.getElementById('movie-id').value = movie.id;
                        document.getElementById('title').value = movie.title;
                        document.getElementById('genre').value = movie.genre;
                        document.getElementById('language').value = movie.language;
                        document.getElementById('runtime_minutes').value = movie.runtime_minutes;
                        document.getElementById('content_rating').value = movie.content_rating;
                        document.getElementById('imdb_rating').value = movie.imdb_rating || '';
                        document.getElementById('release_date').value = movie.release_date;
                        document.getElementById('status').value = movie.status;
                        document.getElementById('poster_url').value = movie.poster_url;
                        document.getElementById('backdrop_url').value = movie.backdrop_url;
                        document.getElementById('description').value = movie.description;

                        document.getElementById('submit-add').hidden = true;
                        document.getElementById('submit-update').hidden = false;
                        document.getElementById('cancel-edit').hidden = false;
                        document.getElementById('movie-form').scrollIntoView({ behavior: 'smooth' });
                    });
            });
        });

        document.getElementById('cancel-edit').addEventListener('click', function () {
            document.getElementById('movie-form').reset();
            document.getElementById('movie-id').value = '';
            document.getElementById('submit-add').hidden = false;
            document.getElementById('submit-update').hidden = true;
            this.hidden = true;
        });
    </script>

    <script type="text/javascript" src="script.js"></script>
</body>
</html>
