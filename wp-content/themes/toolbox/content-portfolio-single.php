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
<h2 class="page-title">
    <?php
        the_title();
    ?>
</h2>

<div id="thumbs">
    <div class="text">
        <a href="#text">View Story</a>
    </div>
    <div class="arrows">
        <a href="#" class="nav prev">&larr;</a>
        <a href="#" class="nav next">&rarr;</a>
    </div>
    <div id="pager">
    <!-- filled dynamically -->
    </div>
</div>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <div class="border-radius"></div>
    <div class="background"></div>
    
    <div id="scroll" class="entry-content">
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
                
                foreach($attachments as $attachment) {
                    // medium images set to be max 500px tall
                    $image = wp_get_attachment_image( $attachment->ID, 'medium' );
        ?>
            <div class="image-container">
                <figure>
                    <?php
                        // Insert image description
                        echo $image;
                    ?>
                </figure>
                <figcaption>
                    <?php
                        // Insert image description
                        echo $attachment->post_content;
                    ?>
                </figcaption>
            </div>
        <?php
                }
            endif;
        ?>
        
    </div>
    
</article><!-- #post-<?php the_ID(); ?> -->

<div id="text" class="text-container">
    <?php the_content(); ?>
</div>
<footer class="entry-meta">
    <?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- #entry-meta -->
