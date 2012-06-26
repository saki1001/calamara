<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>

        <div id="primary">
            <div id="content" role="main">

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
                        get_template_part( 'content', 'portfolio' );
                // BLOG for parent categories Blog and News
                    elseif ( in_category('blog') ) :
                        $sidebar = 'blog';
                        get_template_part( 'content', 'single' );
                        // If comments are open or we have at least one comment, load up the comment template
                        if ( comments_open() || '0' != get_comments_number() ) :
                            comments_template( '', true );
                        endif;
                    else :
                        get_template_part( 'content', 'page' );
                    endif;
                    
                endwhile; // end of the loop.
            ?>
            
            </div><!-- #content -->
        </div><!-- #primary -->

<?php get_sidebar($sidebar); ?>
<?php get_footer(); ?>