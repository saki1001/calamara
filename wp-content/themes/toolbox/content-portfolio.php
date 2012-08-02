<section id="content" role="main">
    
    <h2 class="page-title">
        <?php printf( single_cat_title( '', false ) ); ?>
    </h2>
    
    <?php while ( have_posts() ) : the_post(); ?>
        
        <?php
            // Show Thumbnails of Posts Template
            get_template_part( 'content', 'portfolio-thumbs' );
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