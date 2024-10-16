<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "savoy_movie_theater";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
 die("Connection failed: " . $conn->connect_error);
}
?>
