    </main>

    <footer>
        <div class="footer-content">
            <div class="logo">
                <img src="images/logo1.png" alt="Savoy Cinema logo">
            </div>
            <div class="footer-links">
                <h3>Quick Links</h3>
                <a href="index.php">Home</a>
                <a href="movies.php">Movies</a>
                <a href="cinema.php">Cinema</a>
                <a href="book.php">Book Seat</a>
                <a href="contact.php">Contact Us</a>
            </div>
            <div class="newsletter">
                <h3>Newsletter</h3>
                <form action="contact.php" method="POST">
                    <label for="newsletter-email" class="sr-only">Email address</label>
                    <input type="email" id="newsletter-email" name="email" placeholder="Your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
            </div>
            <div class="contact-info">
                <h3>Contact Us</h3>
                <p>123 Galle Road, Colombo 03, Sri Lanka</p>
                <p><a href="mailto:info@savoycinema.lk">info@savoycinema.lk</a></p>
                <p><a href="tel:+94112345678">+94 11 234 5678</a></p>
            </div>
            <div class="social-media">
                <h3>Follow Us</h3>
                <a href="#" aria-label="Savoy Cinema on Facebook"><i class='bx bxl-facebook' aria-hidden="true"></i></a>
                <a href="#" aria-label="Savoy Cinema on Twitter"><i class='bx bxl-twitter' aria-hidden="true"></i></a>
                <a href="#" aria-label="Savoy Cinema on Instagram"><i class='bx bxl-instagram' aria-hidden="true"></i></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Savoy Cinema. All rights reserved.</p>
            <p class="dev-credit">Developed by <a href="https://hashanjanithwickramasooriya.netlify.app/" target="_blank" rel="noopener noreferrer">Hashan Wickramasooriya</a></p>
        </div>
    </footer>

    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <?php if (!empty($include_carousel_js)): ?>
    <script src="js/slick.min.js"></script>
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/slick-animation.min.js"></script>
    <?php endif; ?>
    <script src="main.js"></script>
    <?php echo $extra_scripts ?? ''; ?>
</body>

</html>
