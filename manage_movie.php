<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "savoy_movie_theater";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_input($data) {
    global $conn;
    return mysqli_real_escape_string($conn, htmlspecialchars(trim($data)));
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = sanitize_input($_POST["action"]);

  
    if ($action == "insert") {
        $title = sanitize_input($_POST["title"]);
        $description = sanitize_input($_POST["description"]);
        $genre = sanitize_input($_POST["genre"]);
        $release_date = sanitize_input($_POST["release_date"]);

        
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "Sorry, file is not an image.";
            $uploadOk = 0;
        }

        
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

      
        if ($_FILES["image"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

      
        $allowed_formats = array("jpg", "jpeg", "png", "gif");
        if (!in_array($imageFileType, $allowed_formats)) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

       
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
       
        } else {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

       
        if ($uploadOk == 1) {
            $image_url = $target_file; 
            $sql = "INSERT INTO movies (title, description, genre, release_date, image_url)
                    VALUES ('$title', '$description', '$genre', '$release_date', '$image_url')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }


    elseif ($action == "update") {
        $id = sanitize_input($_POST["id"]);
        $title = sanitize_input($_POST["title"]);
        $description = sanitize_input($_POST["description"]);
        $genre = sanitize_input($_POST["genre"]);
        $release_date = sanitize_input($_POST["release_date"]);

       
        if ($_FILES["image"]["size"] > 0) {
           
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            
            if (file_exists($target_file)) {
                echo "Sorry, file already exists.";
                $uploadOk = 0;
            }

         
            if ($_FILES["image"]["size"] > 500000) {
                echo "Sorry, your file is too large.";
                $uploadOk = 0;
            }

            $allowed_formats = array("jpg", "jpeg", "png", "gif");
            if (!in_array($imageFileType, $allowed_formats)) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])). " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }

            $image_url = $target_file; 
            $sql = "UPDATE movies SET title='$title', description='$description', genre='$genre',
                    release_date='$release_date', image_url='$image_url' WHERE id=$id";
        } else {
            
            $sql = "UPDATE movies SET title='$title', description='$description', genre='$genre',
                    release_date='$release_date' WHERE id=$id";
        }

        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
}


$conn->close();
?>
