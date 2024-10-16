<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savoy Cinema - Movies</title>
    <link rel="stylesheet" href="movie.css">
</head>

<body>
    <header>
        <h1>SAVOY CINEMA</h1>
    </header>
    <nav>
        <a href="index.html">Home</a>
        <a href="Movies.php">Movie</a>
        <a href="Cinema.html">Cinema</a>
        <a href="book.html">Book Seat</a>
        <a href="contact.html">Contact Us</a>
    </nav>
    <div class="container">
        <h2>Now Showing</h2>
        <div class="movies-container">
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "savoy_movie_theater";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id, title, description, genre, release_date, image_url FROM movies";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='movie'>";
                    echo "<div class='movie-img'>";
                    echo "<img src='" . $row["image_url"] . "' alt='" . $row["title"] . "'>";
                    echo "</div>";
                    echo "<div class='movie-info'>";
                    echo "<h2>" . $row["title"] . "</h2>";
                    echo "<p><strong>Genre:</strong> " . $row["genre"] . "</p>";
                    echo "<p><strong>Release Date:</strong> " . $row["release_date"] . "</p>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "<a href='book.html' class='btn-book'>Book Ticket</a>"; // Book Ticket button
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<p>No movies found</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</body>

</html>
