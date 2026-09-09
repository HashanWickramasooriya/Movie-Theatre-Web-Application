-- Savoy Cinema database
-- Import with phpMyAdmin or `mysql -u root savoy_movie_theater < savoy_movie_theater.sql`
--
-- IMPORTANT (security): the seeded `users` rows below intentionally do NOT ship with
-- working password hashes, because a hash cannot be produced or verified without a
-- real PHP runtime. Right after importing this file, run Database/seed_passwords.php
-- once (see README.md) to set real password_hash() values for the demo accounts.

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Table: movies
-- --------------------------------------------------------

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `genre` varchar(150) NOT NULL,
  `language` varchar(50) NOT NULL DEFAULT 'English',
  `runtime_minutes` smallint(5) UNSIGNED NOT NULL,
  `content_rating` varchar(10) NOT NULL,
  `imdb_rating` decimal(3,1) DEFAULT NULL,
  `release_date` date NOT NULL,
  `status` enum('now_showing','coming_soon') NOT NULL DEFAULT 'coming_soon',
  `poster_url` varchar(500) NOT NULL,
  `backdrop_url` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `movies` (`id`, `title`, `description`, `genre`, `language`, `runtime_minutes`, `content_rating`, `imdb_rating`, `release_date`, `status`, `poster_url`, `backdrop_url`) VALUES
(1, 'Tron: Ares', 'A highly advanced program named Ares is sent from the digital world into our reality on a dangerous mission, marking the first true encounter between humankind and artificial intelligence.', 'Action, Adventure, Science Fiction', 'English', 119, 'PG-13', 6.2, '2025-10-10', 'now_showing', 'https://image.tmdb.org/t/p/w500/chpWmskl3aKm1aTZqUHRCtviwPy.jpg', 'https://image.tmdb.org/t/p/w1280/chpWmskl3aKm1aTZqUHRCtviwPy.jpg'),
(2, 'The Conjuring: Last Rites', 'Paranormal investigators Ed and Lorraine Warren take on one final, deeply personal case that pushes their faith and their family to the breaking point.', 'Horror, Mystery, Thriller', 'English', 135, 'R', 6.2, '2025-09-05', 'now_showing', 'https://image.tmdb.org/t/p/w500/byWgphT74ClOVa8EOGzYDkl8DVL.jpg', 'https://image.tmdb.org/t/p/w1280/byWgphT74ClOVa8EOGzYDkl8DVL.jpg'),
(3, 'Zootopia 2', 'Rookie officers Judy Hopps and Nick Wilde chase a mystery that pulls them into unexpected corners of Zootopia, testing their partnership like never before.', 'Animation, Adventure, Comedy, Family', 'English', 108, 'PG', 7.4, '2025-11-26', 'now_showing', 'https://image.tmdb.org/t/p/w500/oJ7g2CifqpStmoYQyaLQgEU32qO.jpg', 'https://image.tmdb.org/t/p/w1280/oJ7g2CifqpStmoYQyaLQgEU32qO.jpg'),
(4, 'Predator: Badlands', 'Cast out from his clan, a young Predator forms an unlikely alliance with a damaged android and sets out on a brutal hunt for the ultimate adversary.', 'Action, Science Fiction, Thriller', 'English', 107, 'PG-13', 7.3, '2025-11-07', 'now_showing', 'https://image.tmdb.org/t/p/w500/pHpq9yNUIo6aDoCXEBzjSolywgz.jpg', 'https://image.tmdb.org/t/p/w1280/pHpq9yNUIo6aDoCXEBzjSolywgz.jpg'),
(5, 'Now You See Me: Now You Don\'t', 'The original Four Horsemen reunite with a new generation of illusionists to take on a powerful heiress running a criminal empire built on money laundering.', 'Action, Comedy, Crime, Mystery', 'English', 112, 'PG-13', 6.8, '2025-11-14', 'now_showing', 'https://image.tmdb.org/t/p/w500/oD3Eey4e4Z259XLm3eD3WGcoJAh.jpg', 'https://image.tmdb.org/t/p/w1280/oD3Eey4e4Z259XLm3eD3WGcoJAh.jpg'),
(6, 'One Battle After Another', 'A washed-up revolutionary living off-grid with his teenage daughter is thrown back into chaos when an old enemy resurfaces and she suddenly goes missing.', 'Action, Crime, Drama', 'English', 162, 'R', 7.7, '2025-09-26', 'now_showing', 'https://image.tmdb.org/t/p/w500/lbBWwxBht4JFP5PsuJ5onpMqugW.jpg', 'https://image.tmdb.org/t/p/w1280/lbBWwxBht4JFP5PsuJ5onpMqugW.jpg'),
(7, 'Practical Magic 2', 'A family of witches, bound by a generations-old curse against love, confronts old secrets and makes new sacrifices to finally break the spell.', 'Comedy, Drama, Fantasy', 'English', 130, 'PG-13', NULL, '2026-09-10', 'coming_soon', 'https://image.tmdb.org/t/p/w500/7jCV8dkRFK3DGJ4dTqlvrMTFPwZ.jpg', 'https://image.tmdb.org/t/p/w1280/7jCV8dkRFK3DGJ4dTqlvrMTFPwZ.jpg'),
(8, 'Resident Evil', 'A medical courier is pulled into a single, horrifying night of chaos as a viral outbreak turns the streets of Raccoon City into a hunting ground.', 'Horror, Science Fiction', 'English', 94, 'R', NULL, '2026-09-18', 'coming_soon', 'https://image.tmdb.org/t/p/w500/qku2uWSoJ9amQV5MWo1Eek29iji.jpg', 'https://image.tmdb.org/t/p/w1280/qku2uWSoJ9amQV5MWo1Eek29iji.jpg'),
(9, 'Street Fighter', 'Estranged fighters Ryu and Ken are pulled back into combat when the mysterious Chun-Li recruits them for a brutal new World Warrior tournament.', 'Action, Adventure', 'English', 119, 'PG-13', NULL, '2026-10-16', 'coming_soon', 'https://image.tmdb.org/t/p/w500/dJPT2Uf12X6aFg8i5N67NTpIZ9t.jpg', 'https://image.tmdb.org/t/p/w1280/dJPT2Uf12X6aFg8i5N67NTpIZ9t.jpg'),
(10, 'The Hunger Games: Sunrise on the Reaping', 'Decades before Katniss Everdeen, a new tribute faces the horror of the Second Quarter Quell on the morning of the fiftieth Hunger Games.', 'Action, Adventure, Fantasy', 'English', 150, 'PG-13', NULL, '2026-11-20', 'coming_soon', 'https://image.tmdb.org/t/p/w500/ffJaYMtB6v1TrvkyhCOqwqCKm0o.jpg', 'https://image.tmdb.org/t/p/w1280/ffJaYMtB6v1TrvkyhCOqwqCKm0o.jpg');

ALTER TABLE `movies` ADD PRIMARY KEY (`id`);
ALTER TABLE `movies` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

-- --------------------------------------------------------
-- Table: theatres
-- --------------------------------------------------------

CREATE TABLE `theatres` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `hall_code` varchar(10) NOT NULL,
  `projection_type` varchar(50) NOT NULL,
  `total_rows` tinyint(3) UNSIGNED NOT NULL DEFAULT 8,
  `seats_per_row` tinyint(3) UNSIGNED NOT NULL DEFAULT 12,
  `premium_rows` varchar(20) NOT NULL DEFAULT 'G,H'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `theatres` (`id`, `name`, `hall_code`, `projection_type`, `total_rows`, `seats_per_row`, `premium_rows`) VALUES
(1, 'Savoy Hall One', 'C1', '4K Digital Projection, Dolby Atmos', 8, 12, 'G,H'),
(2, 'Savoy Hall Two', 'C2', '2K Laser Projection', 8, 12, 'G,H'),
(3, 'Savoy Hall Three', 'C3', '2K Laser Projection', 8, 12, 'G,H');

ALTER TABLE `theatres` ADD PRIMARY KEY (`id`);
ALTER TABLE `theatres` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------
-- Table: showtimes
-- --------------------------------------------------------

CREATE TABLE `showtimes` (
  `id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `theatre_id` int(11) NOT NULL,
  `show_date` date NOT NULL,
  `show_time` time NOT NULL,
  `standard_price` decimal(8,2) NOT NULL,
  `premium_price` decimal(8,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `showtimes` (`id`, `movie_id`, `theatre_id`, `show_date`, `show_time`, `standard_price`, `premium_price`) VALUES
(1, 1, 1, '2026-09-09', '11:00:00', 1200.00, 1600.00),
(2, 3, 2, '2026-09-09', '11:00:00', 950.00, 1300.00),
(3, 5, 3, '2026-09-09', '11:00:00', 950.00, 1300.00),
(4, 2, 1, '2026-09-09', '15:30:00', 1200.00, 1600.00),
(5, 4, 2, '2026-09-09', '15:30:00', 950.00, 1300.00),
(6, 6, 3, '2026-09-09', '15:30:00', 950.00, 1300.00),
(7, 1, 1, '2026-09-09', '19:30:00', 1200.00, 1600.00),
(8, 3, 2, '2026-09-09', '19:30:00', 950.00, 1300.00),
(9, 5, 3, '2026-09-09', '19:30:00', 950.00, 1300.00),
(10, 1, 1, '2026-09-10', '11:00:00', 1200.00, 1600.00),
(11, 3, 2, '2026-09-10', '11:00:00', 950.00, 1300.00),
(12, 5, 3, '2026-09-10', '11:00:00', 950.00, 1300.00),
(13, 2, 1, '2026-09-10', '15:30:00', 1200.00, 1600.00),
(14, 4, 2, '2026-09-10', '15:30:00', 950.00, 1300.00),
(15, 6, 3, '2026-09-10', '15:30:00', 950.00, 1300.00),
(16, 1, 1, '2026-09-10', '19:30:00', 1200.00, 1600.00),
(17, 3, 2, '2026-09-10', '19:30:00', 950.00, 1300.00),
(18, 5, 3, '2026-09-10', '19:30:00', 950.00, 1300.00),
(19, 1, 1, '2026-09-11', '11:00:00', 1200.00, 1600.00),
(20, 3, 2, '2026-09-11', '11:00:00', 950.00, 1300.00),
(21, 5, 3, '2026-09-11', '11:00:00', 950.00, 1300.00),
(22, 2, 1, '2026-09-11', '15:30:00', 1200.00, 1600.00),
(23, 4, 2, '2026-09-11', '15:30:00', 950.00, 1300.00),
(24, 6, 3, '2026-09-11', '15:30:00', 950.00, 1300.00),
(25, 1, 1, '2026-09-11', '19:30:00', 1200.00, 1600.00),
(26, 3, 2, '2026-09-11', '19:30:00', 950.00, 1300.00),
(27, 5, 3, '2026-09-11', '19:30:00', 950.00, 1300.00);

ALTER TABLE `showtimes` ADD PRIMARY KEY (`id`), ADD KEY `movie_id` (`movie_id`), ADD KEY `theatre_id` (`theatre_id`);
ALTER TABLE `showtimes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_movie_fk` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `showtimes_theatre_fk` FOREIGN KEY (`theatre_id`) REFERENCES `theatres` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------
-- Table: bookings
-- --------------------------------------------------------

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `showtime_id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(255) NOT NULL,
  `seat_codes` text NOT NULL COMMENT 'JSON array of seat codes, e.g. ["A5","A6"]',
  `ticket_count` tinyint(3) UNSIGNED NOT NULL,
  `total_price` decimal(8,2) NOT NULL,
  `booking_reference` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `bookings` (`id`, `showtime_id`, `customer_name`, `customer_email`, `seat_codes`, `ticket_count`, `total_price`, `booking_reference`, `created_at`) VALUES
(1, 1, 'John Silva', 'john.silva@example.com', '["A5","A6"]', 2, 2400.00, 'SVY-8F2K1A', '2026-09-07 10:12:00'),
(2, 9, 'Amaya Perera', 'amaya.perera@example.com', '["G3"]', 1, 1300.00, 'SVY-3Q7N9C', '2026-09-08 14:45:00'),
(3, 15, 'Dilshan Fernando', 'dilshan.f@example.com', '["B10","B11","B12"]', 3, 2850.00, 'SVY-5D1M4Z', '2026-09-08 19:02:00');

ALTER TABLE `bookings` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `booking_reference` (`booking_reference`), ADD KEY `showtime_id` (`showtime_id`);
ALTER TABLE `bookings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_showtime_fk` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`id`) ON DELETE CASCADE;

-- --------------------------------------------------------
-- Table: message
-- --------------------------------------------------------

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `message` (`id`, `name`, `email`, `contact`, `message`, `created_at`) VALUES
(1, 'Janith Kumara', 'janith@example.com', '0712345678', 'Do you offer discounted tickets for students on weekday matinees?', '2026-09-05 09:30:00');

ALTER TABLE `message` ADD PRIMARY KEY (`id`);
ALTER TABLE `message` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Passwords below are placeholders that will never match password_verify().
-- Run Database/seed_passwords.php once after import to set working demo passwords
-- (see README.md "Demo accounts" section for the plaintext passwords they map to).
INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(1, 'Hashan Wickramasooriya', 'hashan@gmail.com', 'PENDING_RUN_SEED_PASSWORDS_SCRIPT', 'admin'),
(2, 'Janith', 'janith@gmail.com', 'PENDING_RUN_SEED_PASSWORDS_SCRIPT', 'user'),
(3, 'Roshan', 'roshan@gmail.com', 'PENDING_RUN_SEED_PASSWORDS_SCRIPT', 'user'),
(4, 'Kasun', 'kasun@gmail.com', 'PENDING_RUN_SEED_PASSWORDS_SCRIPT', 'user');

ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

COMMIT;
