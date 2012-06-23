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
            
            Sidebar Cat Posts
            <?php
                foreach( ( get_the_category() ) as $category ) {
                $the_query = new WP_Query('category_name=' . $category->category_nicename);
                while ($the_query->have_posts()) : $the_query->the_post();
            ?>
                    <li>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </li>
            <?php endwhile; ?>
            <?php
            }
            ?>
            </ul>
            
            <?php if ( ! dynamic_sidebar( 'sidebar-1' ) ) : ?>



            <?php endif; // end sidebar widget area ?>
        </div><!-- #secondary .widget-area -->