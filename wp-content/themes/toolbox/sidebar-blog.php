<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>
        <div id="secondary" class="widget-area" role="complementary">
            
            <h2>Recent Posts</h2>
            <ul>
            <?php
                // get recent posts from Blog category
                $args = array( 'category' => '5' );
                $recent_posts = wp_get_recent_posts($args);
                foreach( $recent_posts as $recent ){
                    echo '<li><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" >' .   $recent["post_title"].'</a> </li> ';
                }
            ?>
            </ul>
            
            <h2>Filter</h2>
            <?php
            // get tags for posts in Blog category
                $args = array( 'categories' => '5' );
                $tags = get_category_tags($args);
                $content = "<ul>";
                foreach ($tags as $tag) {
                	$content .= "<li><a href=\"$tag->tag_link\">$tag->tag_name</a></li>";
                }
                $content .= "</ul>";
                echo $content;
            ?>
        </div><!-- #secondary .widget-area -->