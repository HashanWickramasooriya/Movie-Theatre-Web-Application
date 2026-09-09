# Savoy Cinema

A movie theatre website for a fictional Colombo cinema: browse now showing and coming soon movies, view showtimes, and book seats from a real interactive seat map. Built as a classic server-rendered PHP site (no build step, no framework) on top of MySQL/MariaDB.

## Features

- **Home page** with a hero slider and Now Showing / Coming Soon rails, all driven by the database.
- **Movie catalog** (`movies.php`) with live search and genre/status filtering.
- **Movie detail pages** with poster, backdrop, genre, runtime, rating, language, description, and upcoming showtimes grouped by date.
- **Booking flow** (`book.php`): choose a movie, then a date and showtime, then seats on a real per-hall seat map (available / selected / occupied / premium), then your details, then a confirmation screen with a booking reference. Seat availability is checked live and re-checked on submit to avoid double-booking.
- **Booking lookup** (`view_booking.php`): find a past booking by reference number and email.
- **Contact form** with server-side validation and a proper success/error state (no raw dumped PHP output).
- **Admin panel**: dashboard, movie management (add/edit/delete with poster/backdrop URLs, genre, runtime, rating, language, status), bookings, registered users, and contact messages.
- **Accounts**: registration and login with hashed passwords, plus an admin role.

## Tech stack

- PHP 8 with `mysqli` (prepared statements throughout)
- MySQL / MariaDB
- Bootstrap 4, jQuery, Slick and Owl Carousel (bundled locally under `css/` and `js/`)
- Vanilla JavaScript for the booking flow and catalog filtering (`js/booking.js`, inline scripts)
- No build tooling: everything runs directly from PHP + static assets, no npm install required

## Important limitations (this is a demo)

- **No payment gateway.** The booking flow simulates a real cinema booking (seat locking, pricing, a reference number) but does not process any payment.
- **No real movie API.** Movie data lives in the local `movies` table, seeded with real, verified titles and posters, but it is a static local dataset, not a live TMDB/IMDb integration. Poster and backdrop images are hotlinked from TMDB's public image CDN (`image.tmdb.org`) for demo purposes.
- **No CSRF protection or rate limiting** on forms. Acceptable for a local demo; add both before any real deployment.

## Project structure

```
├── Database/
│   ├── savoy_movie_theater.sql   # schema + seed data
│   └── seed_passwords.php        # one-time script to hash demo account passwords
├── includes/
│   ├── functions.php             # h(), money(), date/time/seat helpers
│   ├── header.php                # shared <head> + nav for customer-facing pages
│   └── footer.php                # shared footer + scripts
├── css/, js/, images/            # third-party assets + images
├── index.php, movies.php, movie.php, cinema.php, contact.php, book.php, view_booking.php
├── showtime_seats.php            # JSON: seat map + availability for a showtime
├── book_seat.php                 # JSON: creates a booking (server-side seat lock)
├── admin_*.php                   # admin panel (dashboard, movies, bookings, users, messages)
└── login.php, register.php, logout.php
```

## Setup (local development)

1. Install a local PHP + MySQL stack (XAMPP, WAMP, MAMP, or `php` + `mysql` directly). PHP 8.0+ is required.
2. Copy this project into your server's document root, e.g. `htdocs/Movie-Theatre-Web-Application`.
3. Create the database and import the schema:
   ```sh
   mysql -u root -e "CREATE DATABASE savoy_movie_theater"
   mysql -u root savoy_movie_theater < Database/savoy_movie_theater.sql
   ```
4. Set real password hashes for the seeded demo accounts (bcrypt hashes can't be committed as plain SQL, they're generated at runtime):
   - Visit `http://localhost/Movie-Theatre-Web-Application/Database/seed_passwords.php` once in your browser (or run it with the PHP CLI).
   - Delete `Database/seed_passwords.php` afterwards, it should never be reachable on a real server.
5. Visit `http://localhost/Movie-Theatre-Web-Application/index.php`.

If your MySQL root user has a password, or you're not using `localhost`, update the credentials in `connection.php`.

### Demo accounts

After running `seed_passwords.php`:

| Role  | Email               | Password    |
|-------|---------------------|-------------|
| Admin | hashan@gmail.com    | hashan123   |
| User  | janith@gmail.com    | janith123   |
| User  | roshan@gmail.com    | roshan123   |
| User  | kasun@gmail.com     | kasun123    |

## Deployment notes

This is a plain PHP application: deploy it to any host that serves PHP 8+ with a MySQL-compatible database (shared hosting, a VPS with PHP-FPM + nginx/Apache, etc.). There is no build step. Before deploying publicly:

- Set real database credentials via environment variables instead of the hardcoded values in `connection.php`.
- Turn off `display_errors` in `php.ini` (the app already returns a friendly error page for uncaught exceptions).
- Delete `Database/seed_passwords.php`.
- Add CSRF tokens to the booking, contact, login, and register forms.
- Serve the site over HTTPS and set the session cookie to `Secure`/`HttpOnly`.
