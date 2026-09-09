<?php
require_once 'includes/functions.php';

$sent = isset($_GET['sent']);
$error = isset($_GET['error']);

$page_title = 'Contact Us | Savoy Cinema';
$page_description = 'Get in touch with Savoy Cinema about bookings, partnerships, or feedback.';
$active_page = 'contact';
require 'includes/header.php';
?>

<section class="page-header">
    <div class="container-fluid">
        <h1>Contact Us</h1>
        <p>Whether you're a movie distributor, an aspiring producer, looking to advertise your brand at our cinemas, or simply want to share feedback on your visit, we'd love to hear from you.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container-fluid">
        <?php if ($sent): ?>
        <div class="form-status form-status--success" role="status">Thanks, your message has been sent. We'll get back to you soon.</div>
        <?php elseif ($error): ?>
        <div class="form-status form-status--error" role="alert">Something went wrong sending your message. Please try again.</div>
        <?php endif; ?>

        <form action="contact_submit.php" method="post" class="contact-form">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Your name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Your email address" required>

            <label for="contact">Contact number</label>
            <input type="tel" id="contact" name="contact" placeholder="e.g. 0712345678" pattern="[0-9+ ]{7,15}" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" placeholder="Write your message" rows="5" required></textarea>

            <button type="submit" class="btn-book">Submit</button>
        </form>
    </div>
</section>

<?php require 'includes/footer.php'; ?>
