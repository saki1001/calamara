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
        <a href="#" class="nav prev">Prev</a>
        <a href="#" class="nav next">Next</a>
    </div>
    <div id="pager">
    <!-- filled dynamically -->
    </div>
</div>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
    <div id="scroll" class="entry-content">
        <?php if ( post_password_required() ) : ?>
            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'toolbox' ) ); ?>
            
            <?php else : ?>
                <?php
                    $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
                    if ( $images ) :
                        $total_images = count( $images );
                        foreach($images as $image) {
                            // medium images set to be max 750 wide 500 tall
                            $image_img_tag = wp_get_attachment_image( $image->ID, 'medium' );
                ?>
                    <div class="image-container">
                        <figure class="gallery-thumb">
                            <?php echo $image_img_tag; ?>
                        </figure><!-- .gallery-thumb -->
                        <figcaption>
                            <?php the_title(); ?>
                        </figcaption>
                    </div>
                <?php
                        }
                    endif;
                ?>

        <?php endif; ?>
    </div>
    
</article><!-- #post-<?php the_ID(); ?> -->
<div id="text" class="text-container">
    <?php the_content(); ?>
</div>
<footer class="entry-meta">
    <?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>
</footer><!-- #entry-meta -->
