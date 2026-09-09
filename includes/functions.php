<?php
/**
 * Small shared helpers used across the site. Kept dependency-free (no framework)
 * to match the rest of the codebase.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escape a value for safe HTML output. */
function h($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

/** Format a price in Sri Lankan rupees, e.g. money(1200) -> "Rs. 1,200.00". */
function money($amount)
{
    return 'Rs. ' . number_format((float) $amount, 2);
}

/** Format a DB date (Y-m-d) as "Wed, 09 Sep 2026". */
function friendly_date($date)
{
    $timestamp = strtotime($date);
    return $timestamp ? date('D, d M Y', $timestamp) : h($date);
}

/** Format a DB time (H:i:s) as "7:30 PM". */
function friendly_time($time)
{
    $timestamp = strtotime($time);
    return $timestamp ? ltrim(date('g:i A', $timestamp), '0') : h($time);
}

/** Format minutes as "2h 15min". */
function friendly_runtime($minutes)
{
    $minutes = (int) $minutes;
    $hours = intdiv($minutes, 60);
    $rest = $minutes % 60;
    return $hours > 0 ? "{$hours}h {$rest}min" : "{$rest}min";
}

/** Generate a short, human-friendly booking reference like SVY-7K2M9Q. */
function generate_booking_reference()
{
    $alphabet = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $alphabet[random_int(0, strlen($alphabet) - 1)];
    }
    return 'SVY-' . $code;
}

/**
 * Build the full seat grid for a theatre (row letters x seat numbers), marking
 * which rows are premium. Returns an array of ['row' => 'A', 'seats' => [1,2,...], 'premium' => bool].
 */
function build_seat_layout($total_rows, $seats_per_row, $premium_rows_csv)
{
    $premium_rows = array_map('trim', explode(',', strtoupper($premium_rows_csv)));
    $layout = [];
    for ($i = 0; $i < (int) $total_rows; $i++) {
        $row_letter = chr(ord('A') + $i);
        $layout[] = [
            'row' => $row_letter,
            'seats' => range(1, (int) $seats_per_row),
            'premium' => in_array($row_letter, $premium_rows, true),
        ];
    }
    return $layout;
}

/** Whether a seat code (e.g. "G3") belongs to a premium row. */
function seat_is_premium($seat_code, $premium_rows_csv)
{
    $premium_rows = array_map('trim', explode(',', strtoupper($premium_rows_csv)));
    $row_letter = strtoupper(substr($seat_code, 0, 1));
    return in_array($row_letter, $premium_rows, true);
}
