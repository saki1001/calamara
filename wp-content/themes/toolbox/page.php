<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
        
        <section id="content" role="main">
            
            <?php
                while ( have_posts() ) : the_post();
                    
                    // Home Page
                    if( is_home() || is_front_page() ) :
                        get_template_part( 'content', 'home' );
                        
                    // All Other Pages
                    else:
                        get_template_part( 'content', 'page' );
                    endif;
                    
                endwhile; // end of the loop.
            ?>
            
        </section>
        
<?php get_footer(); ?>