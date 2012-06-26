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
            $parent_cat_num = $categories[0]->parent;
            $primary_cat_name = $categories[0]->cat_name;
        ?>
        <div id="secondary" class="widget-area" role="complementary">
            <h2>Cat Children</h2>
            <ul>
            <?php
                $child_categories = get_categories('child_of=' . $parent_cat_num . '&hide_empty=1');
                foreach( $child_categories as $category ) {
                    $class = '';
                    if ($primary_cat_name === $category->cat_name) :
                        // $class = "class='current'";
                        $class = "style='color: red;'";
                    endif;
                ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php echo get_category_link($category->cat_ID); ?>"><?php echo $category->cat_name; ?></a>
                    </li>
            <?php
                }
            ?>
            </ul>
        </div><!-- #secondary .widget-area -->
        <?php endif; ?>