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
        
        // Query Category Object
        $cat_obj = $wp_query->get_queried_object();
        $primary_cat_name = $cat_obj->cat_name;
        $class = '';
        
        // For Child Categories
        if( $cat_obj->parent ) :
            $parent_cat_id = $cat_obj->parent;
            $parent_cat = get_category($parent_cat_id);
            $parent_cat_name = $parent_cat->cat_name;
            
        // For the Parent Category
        else :
            $parent_cat_id =  $cat_obj->term_id;
            $parent_cat_name = $cat_obj->name;
            $class = "class='current'";
        endif;
?>
        <div id="sidebar">
            <ul>
                <li <?php echo $class; ?>><a href="<?php echo get_category_link($parent_cat_id); ?>">All <?php echo $parent_cat_name; ?></a></li>
                
                <?php
                    $child_categories = get_categories('child_of=' . $parent_cat_id . '&hide_empty=1');
                    foreach( $child_categories as $category ) {
                        $class = '';
                        if ($primary_cat_name === $category->cat_name) :
                            $class = "class='current'";
                        endif;
                    ?>
                        <li <?php echo $class; ?>>
                            <a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->cat_name; ?></a>
                        </li>
                <?php
                    }
                ?>
            </ul>
        </div><!-- #sidebar -->
<?php
    endif;
?>