<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_REQUEST['name']);
    $email = htmlspecialchars($_REQUEST['email']);
    $contact = htmlspecialchars($_REQUEST['contact']);
    $message = htmlspecialchars($_REQUEST['message']);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "savoy_movie_theater";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO `message` (name, email, contact, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $contact, $message);

    if ($stmt->execute()) {
        echo "Message Sent successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
