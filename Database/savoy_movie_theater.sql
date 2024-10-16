-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2024 at 05:55 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `savoy_movie_theater`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `movie_title` varchar(255) NOT NULL,
  `show_time` varchar(20) NOT NULL,
  `seat_type` varchar(20) NOT NULL,
  `seat_number` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `booking_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `movie_title`, `show_time`, `seat_type`, `seat_number`, `name`, `booking_time`) VALUES
(3, 'Bad Boys: Ride or Die', '7:00 PM', 'C1', 2, 'Janith', '2024-07-02 07:01:54'),
(4, 'The Garfield Movie (3D)', '10:00 AM', 'C2', 4, 'Roshan', '2024-07-02 07:16:17'),
(5, 'Visal Adare', '4:00 PM', 'C3', 7, 'Kasun', '2024-07-02 07:16:39'),
(6, 'கல்கி | Kalki 2898-AD (2D)', '10:00 AM', 'C2', 7, 'Kasun', '2024-07-02 07:16:45');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `email`, `contact`, `message`) VALUES
(1, 'janith', 'janith@gmail.com', '0712345678', 'hello');

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `genre` varchar(100) NOT NULL,
  `release_date` date NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `title`, `description`, `genre`, `release_date`, `image_url`) VALUES
(13, 'විසල් ආදරේ | Visal Adare', 'Visal Adare promises an enchanting and emotional movie experience, exploring the depths of love, friendship and forgiveness. Be captivated by the mesmerizing story of Thaadi, Shavin, Sarah and Anud; as their destinies intertwine in a tale that will stay with you long after the credits roll.', 'Drama , Music , Romance', '2024-05-03', 'uploads/f8.jpg'),
(14, 'Bad Boys: Ride or Die', 'After their late former Captain is framed, Lowrey and Burnett try to clear his name, only to end up on the run themselves.', 'Action , Thriller , Crime', '2024-06-07', 'uploads/f9.jpg'),
(15, 'கல்கி | Kalki 2898-AD (2D)', 'Bhairava, tired of the oppressive confines of his homeland and the perilous life of a bounty hunter, yearns for a more comfortable existence in the Complex. His quest for a new life inadvertently entangles him with a group of rebels dedicated to freeing humanity from the grip of malevolent forces.', 'Action , Adventure , Fantasy , Science Fiction', '2024-06-27', 'uploads/image-400x490.jpg'),
(16, 'The Exorcism', 'A troubled actor begins to unravel while shooting a supernatural horror film, leading his estranged daughter to wonder if he&#039;s slipping back into his past addictions or if there&#039;s something more sinister at play.', 'Horror  , Thriller', '2024-06-21', 'uploads/f2.jpg'),
(17, 'A Quiet Place: Day One', 'As New York City is invaded by alien creatures who hunt by sound, a woman named Sammy fights to survive.', 'Horror , Science Fiction , Thriller', '2024-06-28', 'uploads/f3.jpg'),
(18, 'GODZILLA X KONG: THE NEW EMPIRE', 'Two ancient titans, Godzilla and Kong, clash in an epic battle as humans unravel their intertwined origins and connection to Skull Island&#039;s mysteries.', 'ACTION , ADVENTURE ,SCI-FI', '2024-05-10', 'uploads/f4.jpg'),
(19, 'IF (IMAGINARY FRIENDS)', 'A young girl who goes through a difficult experience begins to see everyone&#039;s imaginary friends who have been left behind as their real-life friends have grown up.', 'COMED , YDRAMA , FAMILY', '2024-06-13', 'uploads/f5.jpg'),
(20, 'The Garfield Movie (3D)', 'Garfield, the world-famous, Monday-hating, lasagna-loving indoor cat, is about to have a wild outdoor adventure! After an unexpected reunion with his long-lost father – scruffy street cat Vic – Garfield and his canine friend Odie are forced from their perfectly pampered life into joining Vic in a hilarious, high-stakes heist.', 'Animation , Comedy , Family', '2024-05-24', 'uploads/f11.jpg'),
(21, 'THE STRANGERS CHAPTER 1', 'After their car breaks down in an eerie small town, a young couple is forced to spend the night in a remote cabin. Panic ensues as they are terrorized by three masked strangers who strike with no mercy and seemingly no motive.', 'Horror', '2024-05-31', 'uploads/f6.jpg'),
(22, 'மகாராஜா | Maharaja', 'A barber seeks vengeance after his home is burglarized, cryptically telling police his &quot;lakshmi&quot; has been taken, leaving them uncertain if it&#039;s a person or object. His quest to recover the elusive &quot;lakshmi&quot; unfolds.', 'Action , Thriller', '2024-06-14', 'uploads/f10.jpg'),
(23, 'Kingdom of the Planet of the Apes', 'Several generations in the future following Caesar&#039;s reign, apes are now the dominant species and live harmoniously while humans have been reduced to living in the shadows. As a new tyrannical ape leader builds his empire, one young ape undertakes a harrowing journey that will cause him to question all that he has known about the past and to make choices that will define a future for apes and humans alike.', 'Science Fiction , Adventure , Action', '2024-05-31', 'uploads/f12.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(2, 'hashan', 'hashan@gmail.com', 'hashan', 'admin'),
(3, 'Janith', 'janith@gmail.com', 'janith', 'user'),
(22, 'Roshan', 'roshan@gmail.com', 'roshan', 'user'),
(23, 'Kasun', 'kasun@gmail.com', 'kasun', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
