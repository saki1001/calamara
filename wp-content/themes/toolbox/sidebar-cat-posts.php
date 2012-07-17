<?php
/**
 * Listing posts in a single category.
 * Variables assume there is only one category being shown.
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
        $class = '';
        
        // if not single, put current class on View All
        if (is_single($post) === true) :
            $current_post_id = $post->ID;
        elseif (is_single($post) === false) :
            $class = "class='current'";
        endif;
?>
        <div id="sidebar" class="widget-area">
            <ul>
                <li <?php echo $class; ?>><a href="<?php echo get_category_link($current_cat_id); ?>">View All</a></li>
            
            <?php
                foreach( ( $categories ) as $category ) {
                    $the_query = new WP_Query('category_name=' . $category->category_nicename);
                    
                    while ($the_query->have_posts()) : $the_query->the_post();
                        $class = '';
                        $id = $post->ID;
                        
                        if ($current_post_id === $id) :
                            $class = "class='current'";
                        endif;
            ?>
                    <li <?php echo $class; ?>>
                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                    </li>
            <?php
                    endwhile;
                }
            ?>
            </ul>
            
        </div><!-- #sidebar -->
<?php
    endif;
?>