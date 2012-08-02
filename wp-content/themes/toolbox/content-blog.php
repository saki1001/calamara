<section id="content" class="blog-list" role="main">
    
    <h2 class="page-title">
        <?php
            $category = get_the_category();
            
            // Print Category Title
            echo $category[0]->cat_name;
            
            // Print Tag Title
            if ( is_tag() ) :
                echo ": " . single_tag_title( '', false );
            endif;
        ?>
    </h2>
    
    <?php while ( have_posts() ) : the_post(); ?>
        
        <?php
            // List Blog Excerpts Template
            get_template_part( 'content', 'blog-list' );
        ?>
        
    <?php endwhile; ?>
    
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