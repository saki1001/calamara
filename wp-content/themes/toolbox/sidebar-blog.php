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
            $cat_num = $categories[0]->cat_ID;
            $cat_name = $categories[0]->cat_name;
        ?>
        <div id="secondary" class="widget-area" role="complementary">
            <h1>Blog sidebar</h1>
            <!-- <h2>Recent Posts</h2>
            <ul>
            <?php
                // // get recent posts from Blog category
                // $post_args = array( 'category' => $cat_num );
                // $recent_posts = wp_get_recent_posts($post_args);
                // foreach( $recent_posts as $recent ){
                //     echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
                // }
            ?>
            </ul> -->
            
            <h2>Filter</h2>
            <ul>
                <li><a href="<?php echo get_category_link($cat_num); ?>">View all</a></li>
            <?php
                // get tags for posts in Blog category
                $tag_args = array( 'categories' => $cat_num );
                $tags = get_category_tags($tag_args);
                foreach ($tags as $tag) {
                    echo "<li><a href=\"$tag->tag_link\">$tag->tag_name</a></li>";
                }
            ?>
            </ul>
        </div><!-- #secondary .widget-area -->
        <?php endif; ?>