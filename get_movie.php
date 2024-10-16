<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "savoy_movie_theater";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM movies WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $movie = $result->fetch_assoc();
    echo json_encode($movie);
} else {
    echo json_encode([]);
}

$conn->close();
?>
