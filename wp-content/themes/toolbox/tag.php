<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>

<?php
    // BLOG for parent categories Blog and News
    if ( in_category('blog') || in_category('news') ) :
       $blogContent = true;
    else :
        $blogContent = false;
    endif;
    
    if ( have_posts() ) :
        
        if ( $blogContent === true ) :
            // Blog Template
            include('content-blog.php');
        else :
            // Hide any other tags with
            // Content Not Found Template
            include('content-not-found.php');
        endif;
        
    else :
        // Content Not Found Template
        include('content-not-found.php');
        
    endif;
?>

<?php get_footer(); ?>