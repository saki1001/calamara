<?php
/**
 * The Template for displaying all single posts.
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */

get_header(); ?>

<!-- Load that shit! -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$(".alert-close").click(function(){
			$(".alert-payment").hide();
		});
	});
</script>

<?php

// Declare variables
$sidebar = '';

/* Start the Loop */
while ( have_posts() ) :
  the_post(); ?>

<h1 class="shop-item-title"><?php the_title(); ?></h1>

<h4><em>Price</em> $<?php the_field('shop-item-price'); ?></h4>


<section id="content">

<!-- The Content -->
<div class="entry-content">
  <?php the_content(); ?>
</div><!-- .entry-content -->

</section>





<div id="sidebar" class="widget-area">

  <div class="sale-item-card">
    <h4><?php the_title(); ?></h4>
    <br>

    <h5><em>Price</em> $<?php the_field('shop-item-price'); ?></h5>

    <p>Shipping and Tax will be applied during checkout.</p>

    <br>

    <!-- The PayPal Button -->
    <!-- The PayPal Button -->
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>

    <div id="paypal-button-container"></div>

    <script>

        // Render the PayPal button

        paypal.Button.render({

            // Set your environment

            env: 'sandbox', // sandbox | production

            // Specify the style of the button

            style: {
                label: 'checkout',
                size:  'responsive',    // small | medium | large | responsive
                shape: 'rect',     // pill | rect
                color: 'blue',      // gold | blue | silver | black
                tagline: 'false',
            },

            // PayPal Client IDs - replace with your own
            // Create a PayPal app: https://developer.paypal.com/developer/applications/create

            client: {
                sandbox:    'Aa0jzKEpX1xa_gkOwqXLQt4qZQ5Utb3eccSansq_trL1lrSI5Hf0t69vQxAJwnx_03MqOL__UTPeBSVN'
                production: 'Aa0jzKEpX1xa_gkOwqXLQt4qZQ5Utb3eccSansq_trL1lrSI5Hf0t69vQxAJwnx_03MqOL__UTPeBSVN'
            },

            payment: function(data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: {
                                    total: '<?php the_field("sale-item-price"); ?>',
                                    currency: 'USD' }
                            }
                        ]
                    }
                });
            },

            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function() {
                    //window.alert('Payment Complete!');
                    $(".alert-payment").addClass("active");
                });
            }

        }, '#paypal-button-container');

    </script>


  </div><!-- Sale Item Card -->

  <?php endwhile; // End of the loop.?>

  <!-- Normal Sidebar Stuff -->
  <?php dynamic_sidebar( 'shop-sidebar' ); ?>

</div><!-- Sidebar -->


<!-- Payment Complete Alert Popup Model Dialog Container -->
<div class="alert-payment">
  <div class="alert-content">
    <h1>Payment Complete</h1>
    <h3>Thank You!</h3>
    <button type="button" class="alert-close">Close</button>
  </div>
  <div class="alert-bg"></div>
</div>

<?php get_footer(); ?>
