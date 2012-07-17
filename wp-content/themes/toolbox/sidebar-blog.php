<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>

<?php
    if ( have_posts() ) :
        
        $categories = get_the_category();
        $current_cat_id = $categories[0]->term_id;
        
        $current_post_id = '';
        $current_tag_title = '';
        $class = '';
        
        // if not single, put current class on View All
        if (is_single($post) === true) :
            $current_post_id = $post->ID;
        elseif (is_tag() === true) :
            $current_tag_title = single_tag_title("", false);
        elseif (is_single($post) === false) :
            $class = "class='current'";
        endif;
    ?>
    
    <div id="sidebar">
        <ul>
            <li <?php echo $class; ?>>
                <a href="<?php echo get_category_link($current_cat_id); ?>">View all</a>
            </li>
            
            <?php
                // get tags for posts in current category
                $tag_args = array( 'categories' => $current_cat_id );
                $tags = get_category_tags($tag_args);
                
                foreach ($tags as $tag) {
                    $class = '';
                    $title = $tag->tag_name;
                    
                    if ($current_tag_title === $title) :
                        $class = "class='current'";
                    endif;
            ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php echo $tag->tag_link; ?>"><?php echo $tag->tag_name; ?></a>
                    </li>
            <?php
                }
            ?>
            
        </ul>
    </div><!-- #sidebar -->
<?php
    endif;
?>