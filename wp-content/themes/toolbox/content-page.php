<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <h2 class="entry-title">
        <?php the_title(); ?>
    </h2>
    
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
        // $attachments = get_posts( $args );
        if ( has_post_thumbnail() ) :
            $noImageClass = '';
        else :
            $noImageClass = 'no-image';
        endif;
    ?>
    
    <div class="entry-content <?php echo $noImageClass; ?>">
        <?php the_content(); ?>
    </div>
    
    <?php
        // Insert images as background-image (to show rounded corners)
        // And as a regular image element (which is hidden)
        
        if ( has_post_thumbnail() ) :
            $image = get_the_post_thumbnail();
            $imageUrl = get_the_post_thumbnail_url();
            $caption = get_the_post_thumbnail_caption();
    ?>
            <figure style="background-image: url('<?php echo $imageUrl; ?>');">
                <?php
                    echo $image;
                ?>
            </figure>
            <figcaption>
                <?php
                    // Insert image description
                    echo $caption;
                ?>
            </figcaption>
    <?php
        else :
            // do nothing
        endif;
    ?>
    
</article><!-- #post-<?php the_ID(); ?> -->