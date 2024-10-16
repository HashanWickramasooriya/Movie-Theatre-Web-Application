<?php
include 'connection.php';
session_start();

$admin_id = $_SESSION['admin_name'];
if (!isset($admin_id)) {
    header('location:login.php');
    exit;
}

if (isset($_POST['logout'])) {
    session_destroy();
    header('location:login.php');
    exit;
}

if (isset($_POST['add_movie'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $image_url = '';

    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    }

    $sql = "INSERT INTO movies (title, description, genre, release_date, image_url) VALUES ('$title', '$description', '$genre', '$release_date', '$image_url')";
    if (mysqli_query($conn, $sql)) {
        echo "New movie added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

if (isset($_POST['update_movie'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $genre = $_POST['genre'];
    $release_date = $_POST['release_date'];
    $image_url = '';

    if ($_FILES['image']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image_url = $target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "File is not an image.";
        }
    } else {
        $image_url = $_POST['existing_image_url'];
    }

    $sql = "UPDATE movies SET title='$title', description='$description', genre='$genre', release_date='$release_date', image_url='$image_url' WHERE id='$id'";
    if (mysqli_query($conn, $sql)) {
        echo "Movie updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Movies</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input, textarea, select {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        input[type="submit"] {
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Manage Movies</h1>
    <form action="admin_movies.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" required>
        
        <label for="description">Description:</label>
        <textarea name="description" id="description" required></textarea>
        
        <label for="genre">Genre:</label>
        <input type="text" name="genre" id="genre" required>
        
        <label for="release_date">Release Date:</label>
        <input type="date" name="release_date" id="release_date" required>
        
        <label for="image">Image:</label>
        <input type="file" name="image" id="image" accept="image/*">
        
        <input type="hidden" name="existing_image_url" value="">
        <input type="submit" name="add_movie" value="Add Movie">
        <input type="submit" name="update_movie" value="Update Movie">
    </form>
</body>
</html>
