    </main> <!-- end #main-content -->

    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-contact">
                    <h4 style="color:white">Get In Touch</h4>
                    <address>Inclusion Hub,<br>Off Sokenu Road, Abeokuta,<br>Ogun state</address>
                    <p>Email: <a href="mailto:inclusion@dadefoundation.com">inclusion@dadefoundation.com </a></p>
                    <p>Phone: <a href="tel:09057299917">09057299917</a></p>
                </div>
                <div class="footer-links">
                    <h4 style="color: white;">Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo $base; ?>/about.php">About DADE</a></li>
                        <li><a href="<?php echo $base; ?>/courses.php">All Courses</a></li>
                        <li><a href="<?php echo $base; ?>/become_instructor.php">Become an Instructor</a></li>
                        
                        <!-- THIS IS THE NEW LINK -->
                        <li><a href="<?php echo $base; ?>/verify_certificate.php">Verify Certificate</a></li>

                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Accessibility Statement</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Dam and Deb Youth Empowerment and People with disability Foundation.</p>
                <p>Empowering Youth & Persons with Disabilities through Innovation and Inclusion.</p>
            </div>
        </div>
    </footer>

    <!-- All widgets like the AI Chatbot are here -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <!-- AI Chat Toggle Button -->
    <button id="ai-assistant-toggle" class="ai-chat-button" aria-label="Open AI Assistant">
        <span class="ai-icon">🤖</span>
        <span class="ai-button-text">AI Help</span>
    </button>
    <div id="ai-chat-widget" class="ai-chat-widget" role="dialog" aria-hidden="true">
        <div class="ai-chat-header">
            <h3>DADE AI Assistant</h3>
            <button id="ai-chat-close" class="ai-chat-close" aria-label="Close chat">&times;</button>
        </div>
        <div id="ai-chat-messages" class="ai-chat-messages" role="log">
            <div class="message ai-message"><p>Hello! I am the DADE AI Assistant. How can I help you today?</p></div>
        </div>
        <div class="ai-chat-input-area">
            <form id="ai-chat-form">
                <input type="text" id="ai-chat-input" placeholder="Type your question here..." autocomplete="off" aria-label="Your question">
                <button type="submit" aria-label="Send message">Send</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="<?php echo $base; ?>/assets/js/main.js?v=<?php echo time(); ?>"></script>
</body>
</html>
<?php
// Close the database connection if it was opened
if (isset($db) && $db instanceof mysqli) {
    $db->close();
}
?>