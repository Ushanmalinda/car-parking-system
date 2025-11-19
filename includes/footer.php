    <footer style="background: #2c3e50; color: white; text-align: center; padding: 20px; margin-top: 40px;">
        <p>&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
        <p>
            <a href="<?php echo SITE_URL; ?>contact-support.php" style="color: #3498db; text-decoration: none;">
                🛠️ Contact Support
            </a>
        </p>
    </footer>
    
    <script src="<?php echo SITE_URL; ?>assets/js/main.js"></script>
    <?php if (isset($extra_js)) echo $extra_js; ?>
</body>
</html>
