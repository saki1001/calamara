<?php
/**
 * The template for displaying Author Archive pages.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>
    
    <?php if ( have_posts() ) : ?>
        
        <?php
            /* Queue the first post, that way we know
             * what author we're dealing with (if that is the case).
             *
             * We reset this later so we can run the loop
             * properly with a call to rewind_posts().
             */
            the_post();
        ?>
        <h2 class="page-title author">
            <?php printf( __( 'Author Archives: %s', 'toolbox' ), '<span class="vcard"><a class="url fn n" href="' . get_author_posts_url( get_the_author_meta( "ID" ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?>
        </h2>
        
        <section id="content" role="main">
            
            <?php
                /* Since we called the_post() above, we need to
                 * rewind the loop back to the beginning that way
                 * we can run the loop properly, in full.
                 */
                rewind_posts();
            ?>
            
            <?php while ( have_posts() ) : the_post(); ?>
                
                <?php
                    /* Include the Post-Format-specific template for the content.
                     * If you want to overload this in a child theme then include a file
                     * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                     */
                    get_template_part( 'content', get_post_format() );
                ?>
                
            <?php endwhile; ?>
            
        </section>
    <?php else :
        // Content Not Found Template
        include('content-not-found.php');
        
    endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>