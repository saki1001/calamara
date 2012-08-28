<?php
/**
 * The template for displaying posts in the Image Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Toolbox
 * @since Toolbox 1.2
 */
?>

<section id="content" class="blog-single" role="main">
    <h2 class="page-title">
        <?php
            the_title();
        ?>
    </h2>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
        <div id="text" class="text-container">
            <?php the_content(); ?>
        </div>
    
    </article><!-- #post-<?php the_ID(); ?> -->
</section>
