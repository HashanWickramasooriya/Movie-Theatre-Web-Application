<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

set_exception_handler(function (Throwable $e) {
    error_log($e->getMessage());
    http_response_code(500);
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=utf-8');
    }
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Something went wrong</title></head>'
        . '<body style="font-family:Arial,sans-serif;text-align:center;padding:80px 20px;background:#141414;color:#d1d0cf;">'
        . '<h1>Something went wrong</h1><p>Please try again in a moment.</p>'
        . '<p><a href="index.php" style="color:#e50914;">Return to Savoy Cinema</a></p></body></html>';
});

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "savoy_movie_theater";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    die('The site is temporarily unavailable. Please try again shortly.');
}
