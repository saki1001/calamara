<section id="content" class="blog-list" role="main">
    
    <h2 class="page-title">
        <?php
            $category = get_the_category();
            $category_slug = $category[0]->slug;
            
            // Print Category Title
            echo $category[0]->cat_name;
            
            // Print Tag Title
            if ( is_tag() ) :
                echo ": " . single_tag_title( '', false );
            endif;
        ?>
    </h2>
    
    <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Loop for Sticky Posts
        // (function in functions.php)
        $sticky = get_option( 'sticky_posts' );
        $sticky_args = array(
            'posts_per_page' => 20,
            'post__in'  => $sticky,
            'ignore_sticky_posts' => 1
        );
        $sticky_query = new WP_Query( $sticky_args );
        
        if ( !empty($sticky) && $paged === 1 ) {
            while ( $sticky_query->have_posts() ) : $sticky_query->the_post();
                get_template_part( 'content', 'blog-list' );
            endwhile;
            wp_reset_postdata();
        }
        
        // Loop for all posts in this category
        // Except stickies
        $args = array(
            'category_name' => $category_slug,
            'paged' => $paged,
            'post__not_in'  => get_option( 'sticky_posts' ),
            'ignore_sticky_posts' => 1
        );
        $the_query = new WP_Query( $args );
        
        // Loop for regular posts
        while ( $the_query->have_posts() ) : $the_query->the_post();
            get_template_part( 'content', 'blog-list' );
        endwhile;
        wp_reset_postdata();
    ?>
    
    <div class="list-footer">
    <?php
        global $wp_query;
        
        $big = 999999999; // need an unlikely integer
        $args = array(
            'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format' => '?paged=%#%',
            'current' => max( 1, get_query_var('paged') ),
            'total' => $wp_query->max_num_pages,
            'prev_text'    => __('&larr; Previous'),
            'next_text'    => __('Next &rarr;')
        );
        
        // Output Pagination Links
        echo paginate_links($args);
    ?>
    </div>
</section>

<?php get_sidebar('blog'); ?>