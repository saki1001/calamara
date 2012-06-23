<?php
/**
 * @package Toolbox
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
    
    <?php 
        
        // do_action('slideshow_deploy', '17');
        wp_cycle();
    ?>
    
    </div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
