<?php
/**
 * @package Toolbox
 */
?>

<div id="slideshow" class="<?php post_class(); ?>">
    
    <?php 
        // do_action('slideshow_deploy', '17');
        wp_cycle();
    ?>
    
</div><!-- #slideshow -->