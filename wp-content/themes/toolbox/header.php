<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Toolbox
 * @since Toolbox 0.1
 */
?>
<!DOCTYPE html>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width" />
        <title>
            <?php
            /*
             * Print the <title> tag based on what is being viewed.
             */
            global $page, $paged;
            
            wp_title( '|', true, 'right' );
            
            // Add the blog name.
            bloginfo( 'name' );
            
            // Add the blog description for the home/front page.
            $site_description = get_bloginfo( 'description', 'display' );
            if ( $site_description && ( is_home() || is_front_page() ) )
                echo " | $site_description";
            
            // Add a page number if necessary:
            if ( $paged >= 2 || $page >= 2 )
                echo ' | ' . sprintf( __( 'Page %s', 'toolbox' ), max( $paged, $page ) );
            
            ?>
        </title>
        
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri(); ?>/style.css" />
        <?php if ( is_singular() && get_option( 'thread_comments' ) ) wp_enqueue_script( 'comment-reply' ); ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        
        <!--[if lt IE 9]>
            <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        
        <?php wp_enqueue_script("jquery"); ?>
        
        <?php wp_head(); ?>
        
        <?php /*Custom JS Files*/ ?>
        <?php if ( is_single() && has_post_format('gallery') ) : ?>
            
            <script src="<?php echo get_template_directory_uri(); ?>/js/image-nav.js" type="text/javascript"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/center-images.js" type="text/javascript"></script>
            <script src="<?php echo get_template_directory_uri(); ?>/js/toggle-image-text.js" type="text/javascript"></script>
            
        <?php elseif ( is_single() && has_post_format('video') ) : ?>
            
            <script src="<?php echo get_template_directory_uri(); ?>/js/toggle-image-text.js" type="text/javascript"></script>
            
        <? endif; ?>
        
    </head>
    
    <body <?php body_class();?>>
    
    <div id="page">
        <header id="branding" role="banner">
            <div id="logo">
                <h1 id="site-title">
                    <a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                </h1>
            </div>
            
            <nav id="main-menu" role="navigation">
                <?php
                    // default menu
                    wp_nav_menu( array( 'theme_location' => 'primary' ) );
                ?>
            </nav>
        </header>
        
        <div id="main">