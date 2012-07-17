<?php
/**
 * The template used to display Tag Archive pages
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>

<?php
    // BLOG for parent categories Blog and News
    if ( in_category('blog') || in_category('news') ) :
       $contentClass = "class='blog-list'";
       $postFormat = "content-blog-list";
    else :
        $mainClass = "";
        $postFormat = "content";
    endif;
?>
    
<?php if ( have_posts() ) : ?>
    
    <h2 class="page-title">
        <?php
            $parent_category = get_the_category();
            echo $parent_category[0]->cat_name . ": " . single_tag_title( '', false )   ;
        ?>
    </h2>
    
    <section id="content" <?php echo $contentClass; ?> role="main">
        
        <?php rewind_posts(); ?>
            
            <?php
                while ( have_posts() ) : the_post(); 
                
                    get_template_part( $postFormat, get_post_format() );
                
                endwhile;
            ?>
        
    <?php else :
        
        include('content-not-found.php');
        
    endif; ?>
    
</section>

<?php get_sidebar('blog'); ?>
<?php get_footer(); ?>