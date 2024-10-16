<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "savoy_movie_theater";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM movies";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movies</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #000;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            
            background-size: cover;
            background-attachment: fixed;
        }

        nav {
            background-color: #333; 
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            left: 0;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); 
        }

        nav img {
            max-width: 100px; 
            height: auto;
            margin-left: 20px; 
        }

        nav a {
            color: #fff;
            text-decoration: none;
            padding: 10px 35px;
            font-weight: bold;
            transition: all 0.3s ease;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #FFC000; 
        }

        .container {
            max-width: 1000px;
            width: 100%;
            padding: 20px;
            margin-top: 100px;
            border-radius: 10px;
            background-color: #ffffffd9;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 15px;
            text-align: center;
        }

        th {
            background-color: #810000;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        button {
            background-image: linear-gradient(to right, #1D4350 0%, #A43931  51%, #1D4350  100%);
          
            padding: 15px 45px;
            text-align: center;
            text-transform: uppercase;
            transition: 0.5s;
            background-size: 200% auto;
            color: white;            
            box-shadow: 0 0 20px #eee;
            border-radius: 10px;
            display: block;
          }

          button:hover {
            background-position: right center; 
            color: #fff;
            text-decoration: none;
          }
         

        #editForm {
            display: none;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"], input[type="date"], textarea, input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="file"] {
            padding: 3px;
        }

        button[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
            }

            nav a {
                margin: 5px 0;
            }
        }
    </style>
</head>
<body>
<nav>
        <img src="images/logo1.png" alt="Logo">
        <a href="admin_panel.php">Home</a>
        <a href="admin_booking.php">Bookings</a>
        <a href="admin.html">Movies</a>
        <a href="admin_user.php">Users</a>
        <a href="admin_message.php">Messages</a>
        <a href="index.html">Theater</a>
    </nav>
    <div class="container">
        <h1>List of Movies</h1>
        <table>
            <tr>
                <th>Title</th>
                <th>Genre</th>
                <th>Release Date</th>
                <th>Image</th>
                <th>Edit</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["genre"] . "</td>";
                    echo "<td>" . $row["release_date"] . "</td>";
                    echo "<td><img src='" . $row["image_url"] . "' alt='" . $row["title"] . "'></td>";
                    echo "<td><button onclick=\"editMovie(" . $row['id'] . ")\">Edit</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No movies found</td></tr>";
            }
            ?>
        </table>

      
        <div id="editForm">
            <h2>Edit Movie</h2>
            <form action="manage_movie.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <label for="editTitle">Title:</label>
                <input type="text" name="title" id="editTitle" required>
                <label for="editDescription">Description:</label>
                <textarea name="description" id="editDescription" rows="5" required></textarea>
                <label for="editGenre">Genre:</label>
                <input type="text" name="genre" id="editGenre" required>
                <label for="editReleaseDate">Release Date:</label>
                <input type="date" name="release_date" id="editReleaseDate" required>
                <label for="editImage">Image:</label>
                <input type="file" name="image" id="editImage" accept="image/*">
                <button type="submit" name="action" value="update">Update Movie</button>
            </form>
        </div>
    </div>

    <script>
        function editMovie(id) {
           
            fetch('get_movie.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editTitle').value = data.title;
                    document.getElementById('editDescription').value = data.description;
                    document.getElementById('editGenre').value = data.genre;
                    document.getElementById('editReleaseDate').value = data.release_date;
                  
                    document.getElementById('editForm').style.display = 'block';
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
