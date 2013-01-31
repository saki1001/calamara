<?php
/**
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 * Learn more: http://codex.wordpress.org/Post_Formats
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>
<section id="content" class="portfolio-single show-images" role="main">
    <h2 class="page-title">
        <?php
            the_title();
        ?>
    </h2>
    
    <div class="toggle-link">
        <a href="#" class="text-link">View Text</a>
        <a href="#" class="image-link">View Video</a>
    </div>
    
    <div id="media">
        <?php
            include('php/simple_html_dom.php');
            
            // Create a DOM object
            $html = new simple_html_dom();
            
            // Load HTML from a string
            $html->load(apply_filters('the_content', get_the_content()));
            
            // Find all iframes
            $iframe = $html->find('iframe');
            foreach( $iframe as $video) {
               // Insert iframe
               echo $video;
               
               // Remove iframe after inserting 
               $video->outertext = '';
           }
        ?>
    </div>
    
    <div id="text" class="text-container">
        <?php
            echo $html;
        ?>
    </div>
</section>