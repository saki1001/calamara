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
        // get attached image
        $image = get_first_attachment();
        $noImageClass = '';
        
        if($image === '') {
            $noImageClass = 'no-image';
        }
    ?>
    
    <div class="entry-content <?php echo $noImageClass; ?>">
        <?php
            // get text
            the_content();
            
        ?>
    </div>
    
    <?php
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

<?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>