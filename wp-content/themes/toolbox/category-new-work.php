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
                    // Thumbnail Template
                    get_template_part( 'content-thumbs', get_post_format() );
                ?>
                
            <?php endwhile; ?>
            
        <?php else :
            // Content Not Found Template
            include('content-not-found.php');
        endif; ?>
        
    </section>
        
<?php get_sidebar('cat-posts'); ?>
<?php get_footer(); ?>