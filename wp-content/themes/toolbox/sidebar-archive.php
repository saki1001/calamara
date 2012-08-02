<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>

<?php if ( have_posts() ) : ?>
    
    <div id="sidebar">
        <?php the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>" ); ?>
    </div><!-- #sidebar -->
<?php
    endif;
?>