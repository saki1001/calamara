<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>




<section role="main">

    <?php
      while ( have_posts() ) : the_post();

        get_template_part( 'content', 'page' );

      endwhile; // end of the loop.
    ?>

    <br><br><br clear="both">

    <!-- Shop Loop -->
    <h6>Items For Sale</h6>

    <div class="shop-grid">
      <?php $loop = new WP_Query(array( 'post_type' => 'shop')); ?>
      <?php if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post(); ?>

        <h4 title="For Sale: <?php the_title(); ?>" class="shop-grid-item">
          <a href="<?php the_permalink(); ?>" title="For Sale: <?php the_title(); ?>">
            <span><?php the_post_thumbnail('thumbnail', array('class' => 'shop-grid-item-img')); ?></span>
            <strong><?php the_title(); ?></strong>
          </a>
        </h4>

      <?php endwhile; ?>

      <?php else: ?>


        <h1 class="shop-grid-item">
          <strong>Uh Oh!</strong><br>
          <em>Nothing in the Shop right now.</em>
        </h1>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>

    </div><!-- Shop Grid -->

</section>



<?php get_footer(); ?>
