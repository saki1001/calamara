<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
        
        <h2 class="page-title">
            <?php printf( single_cat_title( '', false ) ); ?>
        </h2>
        
        <section id="content" role="main">
        
        <?php if ( have_posts() ) : ?>
            
            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
                
                <?php
                    /* Include the Post-Format-specific template for the content.
                     * If you want to overload this in a child theme then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part( 'content-thumbs', get_post_format() );
                ?>
                
            <?php endwhile; ?>
            
        <?php else :
            
            include('content-not-found.php');
            
        endif; ?>

        </section><!-- #content -->

<?php get_sidebar('cat-children'); ?>
<?php get_footer(); ?>