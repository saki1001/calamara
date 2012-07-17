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

<footer class="entry-meta">
    <?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- #entry-meta -->
