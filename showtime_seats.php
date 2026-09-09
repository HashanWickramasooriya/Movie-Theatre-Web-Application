<?php
require_once 'connection.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$showtime_id = filter_input(INPUT_GET, 'showtime_id', FILTER_VALIDATE_INT);
if (!$showtime_id) {
    http_response_code(400);
    echo json_encode(['error' => 'A valid showtime_id is required.']);
    exit;
}

$stmt = $conn->prepare(
    'SELECT s.id, s.show_date, s.show_time, s.standard_price, s.premium_price,
            t.hall_code, t.total_rows, t.seats_per_row, t.premium_rows,
            m.id AS movie_id, m.title AS movie_title, m.poster_url
     FROM showtimes s
     JOIN theatres t ON t.id = s.theatre_id
     JOIN movies m ON m.id = s.movie_id
     WHERE s.id = ?'
);
$stmt->bind_param('i', $showtime_id);
$stmt->execute();
$showtime = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$showtime) {
    http_response_code(404);
    echo json_encode(['error' => 'That showtime could not be found.']);
    exit;
}

$stmt = $conn->prepare('SELECT seat_codes FROM bookings WHERE showtime_id = ?');
$stmt->bind_param('i', $showtime_id);
$stmt->execute();
$rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$booked = [];
foreach ($rows as $row) {
    $seats = json_decode($row['seat_codes'], true);
    if (is_array($seats)) {
        $booked = array_merge($booked, $seats);
    }
}
$booked = array_values(array_unique($booked));

echo json_encode([
    'showtime_id' => (int) $showtime['id'],
    'movie_id' => (int) $showtime['movie_id'],
    'movie_title' => $showtime['movie_title'],
    'poster_url' => $showtime['poster_url'],
    'hall_code' => $showtime['hall_code'],
    'show_date' => $showtime['show_date'],
    'show_date_label' => friendly_date($showtime['show_date']),
    'show_time' => $showtime['show_time'],
    'show_time_label' => friendly_time($showtime['show_time']),
    'standard_price' => (float) $showtime['standard_price'],
    'premium_price' => (float) $showtime['premium_price'],
    'layout' => build_seat_layout($showtime['total_rows'], $showtime['seats_per_row'], $showtime['premium_rows']),
    'booked_seats' => $booked,
]);
