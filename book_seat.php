<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_title = htmlspecialchars($_REQUEST['movie_title']);
    $show_time = htmlspecialchars($_REQUEST['show_time']);
    $seat_type = htmlspecialchars($_REQUEST['seat_type']);
    $seat_number = htmlspecialchars($_REQUEST['seat_number']);
    $name = htmlspecialchars($_REQUEST['name']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "savoy_movie_theater";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO `bookings` (movie_title, show_time, seat_type, seat_number, name) VALUES ('$movie_title', '$show_time', '$seat_type', '$seat_number', '$name')";

    if ($conn->query($sql) === TRUE) {
        echo "Seat booked successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>