<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
    
    <?php
        // Determine parent cat and current cat
        $categories = get_the_category();
        $parent_cat_num = $categories[0]->parent;
        
        // Declare variables
        $sidebar = '';
        
        if ( have_posts() ) :
            while ( have_posts() ) : the_post();
            // PORTFOLIO for parent categories New Work and Archives
                if ( in_category('new-work') || $parent_cat_num === '10' ) :
                    $sidebar = 'cat-posts';
                    
                    $format = get_post_format( $post->ID );
                    if ( $format === 'gallery' ) :
                        get_template_part('content', 'portfolio-gallery' );
                    else :
                        get_template_part('content', 'portfolio-standard' );
                    endif;
            // BLOG for parent categories Blog and News
                elseif ( in_category('blog') || in_category('news') ) :
                    $sidebar = 'blog';
                    get_template_part('content', 'blog-single' );
                    
                else :
                    // Standard Template
                    get_template_part('content', 'page' );
                    
                endif;
                
            endwhile; // end of the loop.
    ?>
        
    <?php else :
        // Content Not Found Template
        include('content-not-found.php');
        
    endif; ?>
    
<?php get_sidebar($sidebar); ?>
<?php get_footer(); ?>