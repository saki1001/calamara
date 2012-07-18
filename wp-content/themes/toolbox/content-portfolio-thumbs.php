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
        <p>
            <?php the_title(); ?>
        </p>
    </figcaption>
    <?php
        // Define args to get attachments
        $args = array(
            'post_parent' => $post->ID,
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'orderby' => 'menu_order',
            'order' => 'ASC'
        );
        
        // Get image attachments
        $attachments = get_children( $args );
        
        if ( $attachments ) :
            
            // Use only first value in array
            $attachment = array_shift( $attachments );
            
            // Get thumbnail and its URL
            $image = wp_get_attachment_image( $attachment->ID, 'thumbnail' );
            $imageUrl = wp_get_attachment_image_src( $attachment->ID, 'thumbnail' );
            
    ?>
        <figure class="gallery-thumb">
            <a href="<?php the_permalink(); ?>" style="background: url('<?php echo $imageUrl[0]; ?>') no-repeat center center;">
                <?php 
                    echo $image;
                ?>
            </a>
        </figure>
    <?php
        endif;
    ?>
</div>
