<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>

    </div><!-- #main -->

    <footer id="footer" role="contentinfo">
        <div>
            Mara G. Haseltine &copy;
        </div>
    </footer><!-- #footer -->
</div><!-- #page -->

<?php wp_footer(); ?>

<?php
    
    // CUSTOM JS
    // Gallery Posts
    if ( is_single() && has_post_format('gallery') ) :
?>
    <script src="<?php echo get_template_directory_uri(); ?>/js/image-nav.js" type="text/javascript"></script>

<? endif; ?>

</body>
</html>