<?php
/**
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        
    </header><!-- .entry-header -->
    <div class="entry-content">
        <?php if ( post_password_required() ) : ?>
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'toolbox' ) ); ?>
            
            <?php else : ?>
                <?php
                    $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
                    if ( $images ) :
                        $total_images = count( $images );
                        foreach($images as $image) {
                        $image_img_tag = wp_get_attachment_image( $image->ID, 'full' );
                ?>
                    <figure class="gallery-thumb">
                        <?php echo $image_img_tag; ?>
                    </figure><!-- .gallery-thumb -->
                <?php
                        }
                    endif;
                ?>
            <?php the_content(); ?>
        <?php endif; ?>
    </div><!-- .entry-content -->
    
    <footer class="entry-meta">
        <?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>
    </footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
