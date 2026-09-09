<?php
require_once 'connection.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request.']);
    exit;
}

$showtime_id = filter_var($input['showtime_id'] ?? null, FILTER_VALIDATE_INT);
$seats = is_array($input['seats'] ?? null) ? array_values(array_unique($input['seats'])) : [];
$customer_name = trim((string) ($input['customer_name'] ?? ''));
$customer_email = trim((string) ($input['customer_email'] ?? ''));

$errors = [];
if (!$showtime_id) {
    $errors[] = 'A showtime is required.';
}
if (!$seats || count($seats) > 10) {
    $errors[] = 'Select between 1 and 10 seats.';
}
foreach ($seats as $seat) {
    if (!preg_match('/^[A-Z]{1,2}\d{1,3}$/', (string) $seat)) {
        $errors[] = 'One of the selected seats is invalid.';
        break;
    }
}
if ($customer_name === '' || mb_strlen($customer_name) > 100) {
    $errors[] = 'Please enter your full name.';
}
if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($errors) {
    http_response_code(422);
    echo json_encode(['error' => implode(' ', $errors)]);
    exit;
}

$conn->begin_transaction();
try {
    $stmt = $conn->prepare(
        'SELECT s.id, s.standard_price, s.premium_price, t.premium_rows
         FROM showtimes s JOIN theatres t ON t.id = s.theatre_id
         WHERE s.id = ? FOR UPDATE'
    );
    $stmt->bind_param('i', $showtime_id);
    $stmt->execute();
    $showtime = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$showtime) {
        throw new RuntimeException('That showtime no longer exists.');
    }

    $stmt = $conn->prepare('SELECT seat_codes FROM bookings WHERE showtime_id = ?');
    $stmt->bind_param('i', $showtime_id);
    $stmt->execute();
    $existing_rows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $already_booked = [];
    foreach ($existing_rows as $row) {
        $decoded = json_decode($row['seat_codes'], true);
        if (is_array($decoded)) {
            $already_booked = array_merge($already_booked, $decoded);
        }
    }

    $conflict = array_intersect($seats, $already_booked);
    if ($conflict) {
        throw new RuntimeException('Sorry, seat ' . implode(', ', $conflict) . ' was just taken by someone else. Please choose another seat.');
    }

    $total_price = 0;
    foreach ($seats as $seat) {
        $total_price += seat_is_premium($seat, $showtime['premium_rows'])
            ? (float) $showtime['premium_price']
            : (float) $showtime['standard_price'];
    }

    do {
        $reference = generate_booking_reference();
        $check = $conn->prepare('SELECT id FROM bookings WHERE booking_reference = ?');
        $check->bind_param('s', $reference);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        $check->close();
    } while ($exists);

    $seat_codes_json = json_encode($seats);
    $ticket_count = count($seats);

    $stmt = $conn->prepare(
        'INSERT INTO bookings (showtime_id, customer_name, customer_email, seat_codes, ticket_count, total_price, booking_reference)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('isssids', $showtime_id, $customer_name, $customer_email, $seat_codes_json, $ticket_count, $total_price, $reference);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    echo json_encode([
        'success' => true,
        'booking_reference' => $reference,
        'seats' => $seats,
        'total_price' => $total_price,
    ]);
} catch (RuntimeException $e) {
    $conn->rollback();
    http_response_code(409);
    echo json_encode(['error' => $e->getMessage()]);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['error' => 'Something went wrong while booking your seats. Please try again.']);
}
