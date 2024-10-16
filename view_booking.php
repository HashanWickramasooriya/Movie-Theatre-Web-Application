<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Reservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: url(images/Admin/contactuspage.png);
            background-size: cover;
        }

        h1 {
            color: #333;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 30px;
            border: 2px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f7f7f7;
            color: #333;
            font-weight: bold;
        }

        tr:hover {
            background-color: #ffe6cc;
        }

        .center {
            text-align: center;
        }

        button[type="button"] {
            background-color: #4CAF50;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="button"]:hover {
            background-color: #3e8e41;
        }

        a {
            text-decoration: none;
            color: #337ab7;
        }

        a:hover {
            color: #23527c;
        }
    </style>
    <link rel="stylesheet" href="footer.css">
</head>

<body>
    <div class="container">
        <h1>View Booking</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Movie Title</th>
                    <th>Show Time</th>
                    <th>Seat Type</th>
                    <th>Seat Number</th>
                    <th>Name</th>
                    <th>Booking Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "savoy_movie_theater";

               
                $conn = new mysqli($servername, $username, $password, $dbname);

               
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT id, movie_title, show_time, seat_type, seat_number, name, booking_time FROM bookings";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                   
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["movie_title"] . "</td>
                                <td>" . $row["show_time"] . "</td>
                                <td>" . $row["seat_type"] . "</td>
                                <td>" . $row["seat_number"] . "</td>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["booking_time"] . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No bookings found</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table><br>

        <a href="book.html"><button type="button">Book a Seat</button></a>
        <a href="index.html"><button type="button">Go to Home</button></a>
    </div>

   

</body>

</html>
