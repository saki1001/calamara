<?php
/**
 * The template for displaying thumbnails in New Work and Archive portfolio pages
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>

<div id="post-<?php the_ID(); ?>" class="thumb-container">
    <figcaption>
        <p><?php the_title(); ?></p>
    </figcaption>
    <?php
        
        $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
        
        // if (has_post_thumbnail($post->ID)) :
        //     $image_img_tag = get_the_post_thumbnail($post->ID, 'thumbnail');
        if ( $images ) :
            $total_images = count( $images );
            $image = array_shift( $images );
            $image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
            $image_img_tag_url = wp_get_attachment_image_src( $image->ID, 'thumbnail' );
    ?>
        <figure class="gallery-thumb">
            <a href="<?php the_permalink(); ?>" style="background: url('<?php echo $image_img_tag_url[0]; ?>') no-repeat center center;">
                <?php 
                    echo $image_img_tag;
                    // the_post_thumbnail('thumbnail');
                ?>
            </a>
        </figure>
    <?php endif; ?>
</div>
