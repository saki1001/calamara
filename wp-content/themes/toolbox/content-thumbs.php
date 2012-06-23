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
        <?php
            $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
            if ( $images ) :
                $total_images = count( $images );
                $image = array_shift( $images );
                $image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
        ?>
            <figure class="gallery-thumb">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $image_img_tag; ?>
                </a>
            </figure><!-- .gallery-thumb -->
            <figcaption>
                <?php the_title(); ?>
            </figcaption>
        <?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
