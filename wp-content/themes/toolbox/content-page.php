<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <h1 class="entry-title"><?php the_title(); ?></h1>
    </header><!-- .entry-header -->

    <div class="entry-content">
        <?php
            // get text
            the_content();
            
            // get attached image
            $image = get_first_attachment();
            
            if($image != '') {
                echo "<img src='" . $image . "' width='350' />";
            } else {
                // do nothing
            }
        ?>
        <?php edit_post_link( __( 'Edit', 'toolbox' ), '<span class="edit-link">', '</span>' ); ?>
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->
