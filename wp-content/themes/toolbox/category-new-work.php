<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
    
<?php
    if ( have_posts() ) :
        // Portfolio Template
        include('content-portfolio.php');
        
    else :
        // Content Not Found Template
        include('content-not-found.php');
        
    endif;
?>
    
<?php get_sidebar('cat-posts'); ?>
<?php get_footer(); ?>