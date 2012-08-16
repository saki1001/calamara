<?php
/**
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>
<section id="content" class="portfolio-single show-images" role="main">
    <h2 class="page-title">
        <?php
            the_title();
        ?>
    </h2>
    
    <div id="text" class="text-container">
        <?php the_content(); ?>
    </div>
</section>