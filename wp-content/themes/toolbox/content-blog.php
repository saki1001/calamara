<?php
/**
 * @package Toolbox
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php if ( 'post' == get_post_type() ) : ?>
	<div class="entry-meta">
		<?php echo date('M d', strtotime(get_the_date())); ?>
	</div><!-- .entry-meta -->
	<?php endif; ?>
	
	<h3>
		<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'toolbox' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
	</h3><!-- h3 -->

	<div class="entry-content">
        <?php
            $images = get_children( array( 'post_parent' => $post->ID, 'post_type' => 'attachment', 'post_mime_type' => 'image', 'orderby' => 'menu_order', 'order' => 'ASC' ) );
            if ( $images ) :
                $total_images = count( $images );
                $image = array_shift( $images );
                $image_img_tag = wp_get_attachment_image( $image->ID, 'thumbnail' );
        ?>
            <!-- WITH THUMBNAIL -->
            <?php the_excerpt_max_charlength(100); ?>
            <figure class="gallery-thumb">
                <a href="<?php the_permalink(); ?>">
                    <?php echo $image_img_tag; ?>
                </a>
            </figure><!-- .gallery-thumb -->
        <?php else : ?>
            <!-- WITHOUT THUMBNAIL -->
            <?php the_excerpt_max_charlength(200); ?>
        <?php endif; ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
