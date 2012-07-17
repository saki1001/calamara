<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
    
    <?php if ( have_posts() ) : ?>
        
        <h2 class="page-title">
            <?php printf( single_cat_title( '', false ) ); ?>
        </h2>
        
        <section id="content" class="blog-list" role="main">
            
            <?php while ( have_posts() ) : the_post(); ?>
                
                <?php
                    // List Blog Excerpts Template
                    get_template_part( 'content-blog-list', get_post_format() );
                ?>
                
            <?php endwhile; ?>
            
        </section>
        
    <?php else :
        // Content Not Found Template
        include('content-not-found.php');
        
    endif; ?>
    
<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>