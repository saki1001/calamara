<?php
/**
 * @package Toolbox
 */
?>
<?php
    // WITH AND WITHOUT THUMBNAIL TEMPLATES
    $featImg = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
    $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
       
    if ( has_post_thumbnail($post->ID) ) :
        // $image_img_tag = $featImg;
        $image_img_tag_url = $featImg;
        
        $thumbClass = "thumb";
        $maxExcerptLength = "150";
    elseif ( $images ) :
        $total_images = count( $images );
        $image = array_shift( $images );
        // $image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
        $image_img_tag_url = wp_get_attachment_image_src( $image->ID, 'thumbnail' );
        
        $thumbClass = "thumb";
        $maxExcerptLength = "150";
    else :
        $image_img_tag = "";
        
        $thumbClass = "text";
        $maxExcerptLength = "125";
    endif;
?>

<article id="post-<?php the_ID(); ?>" class="<?php echo $thumbClass; ?>">
        
    <?php if ( 'post' == get_post_type() ) : ?>
        
        <div class="entry-meta">
            <?php echo date('m. d', strtotime(get_the_date())); ?>
        </div>
        
    <?php endif; ?>
    
    <div class="entry">
        
            <h3 class="entry-title">
                <a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toolbox' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
                    <?php the_title(); ?>
                </a>
            </h3>
        
            <div class="entry-content">
                <?php the_excerpt_max_charlength($maxExcerptLength); ?>
            </div>
        
    </div>
    
    <?php if ($thumbClass == "thumb" ) : ?>
        
        <figure style="background: url('<?php echo $image_img_tag_url[0]; ?>') no-repeat center center;">
            <!-- background image -->
        </figure>
        
    <?php endif; ?>
    
</article><!-- #post-<?php the_ID(); ?> -->
