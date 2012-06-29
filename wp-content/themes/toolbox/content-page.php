<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="entry-content">
        <h1 class="entry-title"><?php the_title(); ?></h1>
        <?php
            // get text
            the_content();
            
        ?>
        <?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>
    </div><!-- .entry-content -->
    
    <?php
        // get attached image
        $image = get_first_attachment();
        $imageUrl = '';
        
        // insert image
        if($image != '') {
    ?>
        <figure>
            <img src="<?php echo $image; ?>" width="350" />
        </figure>
    <?php
        } else {
            // do nothing
        }
    ?>
    </figure>
    
</article><!-- #post-<?php the_ID(); ?> -->
