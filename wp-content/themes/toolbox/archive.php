<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
    
    <?php if ( have_posts() ) : ?>
        
        <h2 class="page-title">
            <?php
                if ( is_day() ) :
                    printf( __( 'Daily Archives: %s', 'toolbox' ), '<span>' . get_the_date() . '</span>' );
                elseif ( is_month() ) :
                    printf( __( 'Monthly Archives: %s', 'toolbox' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );
                elseif ( is_year() ) :
                    printf( __( 'Yearly Archives: %s', 'toolbox' ), '<span>' . get_the_date( 'Y' ) . '</span>' );
                else :
                    _e( 'Archives', 'toolbox' );
                endif;
            ?>
        </h2>
        
        <section id="content" role="main">
            
            <?php rewind_posts(); ?>
            
            <?php 
                while ( have_posts() ) : the_post();
                    
                    if ( in_category('blog') || in_category('news') ) :
                        // List Excerpts Template
                        get_template_part( 'content-blog-list', get_post_format() );
                    else :
                        // don't show'
                    endif;
                    
                endwhile;
            ?>
            
        </section>
        
    <?php else :
        // Content Not Found Template
        include('content-not-found.php');
        
    endif; ?>

<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>