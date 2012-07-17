<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
    
    <?php if ( have_posts() ) : ?>
        
        <section id="content" role="main">
            
            <?php
                // Determine parent cat and current cat
                $categories = get_the_category();
                $parent_cat_num = $categories[0]->parent;
                $current_cat_name = $categories[0]->cat_name;
                
                // Declare sidebar variable
                $sidebar = '';
                
                while ( have_posts() ) : the_post();
                // PORTFOLIO for parent categories New Work and Archives
                    if ( in_category('new-work') || $parent_cat_num === '10' ) :
                        $sidebar = 'cat-posts';
                        get_template_part( 'content', 'portfolio-single' );
                        
                // BLOG for parent categories Blog and News
                    elseif ( in_category('blog') || in_category('news') ) :
                        $sidebar = 'blog';
                        get_template_part( 'content', 'blog-single' );
                        // // If comments are open or we have at least one comment, load up the comment template
                        // if ( comments_open() || '0' != get_comments_number() ) :
                        //     comments_template( '', true );
                        // endif;
                        
                    else :
                        get_template_part( 'content', 'page' );
                    endif;
                
                endwhile; // end of the loop.
            ?>
        </section>
        
    <?php else :
        
        include('content-not-found.php');
        
    endif; ?>
    
<?php get_sidebar($sidebar); ?>
<?php get_footer(); ?>