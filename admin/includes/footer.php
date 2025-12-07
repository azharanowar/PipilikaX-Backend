<?php
/**
 * Admin Footer Template
 * PipilikaX Admin Panel
 */
?>
</main>
</div>
</div>

<!-- Custom Admin JS -->
<script src="<?php echo ADMIN_URL; ?>/assets/js/admin-script.js"></script>

<?php if (isset($additional_js)): ?>
    <?php echo $additional_js; ?>
<?php endif; ?>
</body>

</html>