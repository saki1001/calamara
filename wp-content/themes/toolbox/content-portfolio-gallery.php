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
<section id="content" class="portfolio-single show-images" role="main">
    <h2 class="page-title">
        <?php
            the_title();
        ?>
    </h2>
    
    <div class="toggle-link">
        <a href="#" class="text-link">View Text</a>
        <a href="#" class="image-link">View Images</a>
    </div>
    
    <div class="scroll-container">
        
        <div class="border left">
            <a href="#" class="nav arrow prev"></a>
        </div>
        <div class="border right">
            <a href="#" class="nav arrow next"></a>
        </div>
        
        <div id="thumbs" class="nav">
            <div id="pager">
            <!-- filled dynamically -->
            </div>
        </div>
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
    
    </div>

    <div id="text" class="text-container">
        <?php the_content(); ?>
    </div>
</section>