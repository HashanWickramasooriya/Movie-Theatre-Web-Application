<?php
/**
 * Shared site head + header/nav, included by every customer-facing page.
 * Callers should set these variables before including this file:
 *   $page_title        (string, required)   e.g. "Movies | Savoy Cinema"
 *   $page_description  (string, optional)   meta description / og:description
 *   $og_image           (string, optional)  absolute image URL for social sharing
 *   $active_page        (string, optional)  one of: home, movies, cinema, book, contact
 *   $extra_head          (string, optional) raw HTML appended before </head>, e.g. extra <link>/<style>
 */

require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? 'Savoy Cinema';
$page_description = $page_description ?? 'Savoy Cinema in Colombo: now showing and coming soon movies, showtimes, and online seat booking.';
$active_page = $active_page ?? '';
$nav_links = [
    'home' => ['index.php', 'Home'],
    'movies' => ['movies.php', 'Movies'],
    'cinema' => ['cinema.php', 'Cinema'],
    'book' => ['book.php', 'Book Seat'],
    'contact' => ['contact.php', 'Contact Us'],
];

$logged_in_user = $_SESSION['user_name'] ?? null;
$logged_in_admin = $_SESSION['admin_name'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo h($page_title); ?></title>
    <meta name="description" content="<?php echo h($page_description); ?>" />
    <meta property="og:title" content="<?php echo h($page_title); ?>" />
    <meta property="og:description" content="<?php echo h($page_description); ?>" />
    <meta property="og:type" content="website" />
    <?php if (!empty($og_image)): ?>
    <meta property="og:image" content="<?php echo h($og_image); ?>" />
    <?php endif; ?>
    <link rel="icon" href="images/logo1.png" />

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/slick.css" />
    <link rel="stylesheet" href="css/slick-theme.css" />
    <link rel="stylesheet" href="css/owl.carousel.min.css" />
    <link rel="stylesheet" href="css/animate.min.css" />
    <link rel="stylesheet" href="css/magnific-popup.css" />
    <link rel="stylesheet" href="css/select2.min.css" />
    <link rel="stylesheet" href="css/select2-bootstrap4.min.css" />
    <link rel="stylesheet" href="css/slick-animation.css" />
    <link rel="stylesheet" href="style.css" />
    <?php echo $extra_head ?? ''; ?>
</head>

<body>
    <a class="skip-link" href="#main-content">Skip to main content</a>
    <header id="main-header">
        <div class="main-header">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">
                        <nav class="navbar navbar-expand-lg navbar-light p-0">
                            <a href="#" class="navbar-toggler c-toggler" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <div class="navbar-toggler-icon" data-toggle="collapse">
                                    <span class="navbar-menu-icon navbar-menu-icon--top"></span>
                                    <span class="navbar-menu-icon navbar-menu-icon--middle"></span>
                                    <span class="navbar-menu-icon navbar-menu-icon--bottom"></span>
                                </div>
                            </a>
                            <a href="index.php" class="navbar-brand">
                                <img src="images/logo1.png" class="img-fluid logo" alt="Savoy Cinema home" />
                            </a>
                            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                <div class="menu-main-menu-container">
                                    <ul id="top-menu" class="navbar-nav ml-auto">
                                        <?php foreach ($nav_links as $key => [$href, $label]): ?>
                                        <li class="menu-item"><a href="<?php echo h($href); ?>" <?php echo $active_page === $key ? 'aria-current="page" class="active"' : ''; ?>><?php echo h($label); ?></a></li>
                                        <?php endforeach; ?>
                                        <?php if ($logged_in_admin): ?>
                                        <li class="menu-item"><a href="admin_panel.php">Admin Panel</a></li>
                                        <?php elseif ($logged_in_user): ?>
                                        <li class="menu-item"><a href="logout.php">Log Out (<?php echo h($logged_in_user); ?>)</a></li>
                                        <?php else: ?>
                                        <li class="menu-item"><a href="login.php">Login</a></li>
                                        <li class="menu-item"><a href="register.php">Register</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                        <div class="nav-overlay"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main id="main-content">
