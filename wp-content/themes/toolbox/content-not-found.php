<?php
/**
 * The template used for displaying when no posts are found.
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>
    
    <article id="post-0" class="post no-results not-found">
        <header class="entry-header">
            <h1 class="entry-title"><?php _e( 'Nothing Found', 'toolbox' ); ?></h1>
        </header><!-- .entry-header -->
        
        <div class="entry-content">
            <p><?php _e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'toolbox' ); ?></p>
            <?php get_search_form(); ?>
        </div><!-- .entry-content -->
    </article><!-- #post-0 -->