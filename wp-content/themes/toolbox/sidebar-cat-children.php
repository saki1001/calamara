<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>
        <div id="secondary" class="widget-area" role="complementary">
            <?php do_action( 'before_sidebar' ); ?>
            
            Sidebar Cat Children
            
            <ul>
            <?php
                // archives id=10
                $cat = 10;
                $categories = get_categories('child_of=' . $cat . '&hide_empty=1');
                // print_r($categories);
                
                foreach( $categories as $category ) {
                    // echo $category->category_nicename;
                    echo '<li><a href="'.get_category_link($category->cat_ID).'">';
                    echo $category->cat_name . '</a>';
                    echo '</li>';
                }
                
                // wp_list_categories('child_of=' . $category->category_parent);
                
                // foreach( ( get_the_category() ) as $category ) {
                //     $the_query = new WP_Query('category_name=' . $category->category_parent);
                //     print_r($the_query);
                // }
            ?>
            </ul>
            <?php
            // global $ancestor;
            // $childcats = get_categories('child_of=' . $cat . '&hide_empty=1');
            // foreach ($childcats as $childcat) {
            //   if (cat_is_ancestor_of($ancestor, $childcat->cat_ID) == false){
            //     echo '<li><h2><a href="'.get_category_link($childcat->cat_ID).'">';
            //     echo $childcat->cat_name . '</a></h2>';
            //     echo '<p>'.$childcat->category_description.'</p>';
            //     echo '</li>';
            //     $ancestor = $childcat->cat_ID;
            //   }
            // }
            ?>
            </ul>
            
            <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>



            <?php endif; // end sidebar widget area ?>
        </div><!-- #secondary .widget-area -->