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
    
    // Inserting attachments into HTML
    function insert_attachments( $attachments ) {
        global $post;
        
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
    }
?>

<section id="content" class="portfolio-single" role="main">
    
    <div class="page-header">
        <h2 class="page-title">
            <?php
                the_title();
            ?>
        </h2>
        
        <?php include('content-social-icons.php'); ?>
        
    </div>
    
    <div id="media">
        <?php
            // For Many Images (Slideshow)
            if ( count($attachments) > 1 ) :
        ?>
        
        <div class="border left">
            <a href="#" class="nav arrow prev"></a>
        </div>
        <div class="border right">
            <a href="#" class="nav arrow next"></a>
        </div>
        
        <div class="pager-container nav">
            <div id="pager">
            <!-- filled dynamically -->
            </div>
        </div>
        
        <div class="scroll-container">
            <div id="scroll" class="entry-content">
                
                <?php insert_attachments( $attachments ); ?>
                
            </div>
        </div>
        
        <?php
            // For Single Image
            elseif ( count($attachments) === 1 ) :
        ?>
        
        <div class="border left"></div>
        <div class="border right"></div>
        
        <?php insert_attachments( $attachments ); ?>
        
        <?php
        endif;
        ?>
    </div>
    
    <div id="text" class="text-container">
        <?php the_content(); ?>
    </div>
</section>