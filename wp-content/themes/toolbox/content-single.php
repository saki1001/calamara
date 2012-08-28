<?php
/**
 * @package Toolbox
 */
?>
<section id="content" class="blog-single" role="main">
    <h2 class="page-title">
        <?php
            the_title();
        ?>
    </h2>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    
        <div id="text" class="text-container">
            <?php the_content(); ?>
        </div>
    
    </article><!-- #post-<?php the_ID(); ?> -->

    <div class="post-footer">
        <div class="post-nav">
            <?php previous_post_link('%link', '&larr;', TRUE); ?>
            <span class="post-date">
                <?php the_date(); ?>
            </span>
            <?php next_post_link('%link', '&rarr;', TRUE); ?>
        </div>
    
        <?php
            /* translators: used between list items, there is a space after the comma */
            $tags_list = get_the_tag_list( '', __( ', ', 'toolbox' ) );
            if ( $tags_list ) :
        ?>
            <div class="post-tags">
                <?php printf( __( 'Tags: %1$s', 'toolbox' ), $tags_list ); ?>
            </div>
        <?php endif; // End if $tags_list ?>
    </div>
</section>