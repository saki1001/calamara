<?php
/**
 * The template used for displaying social media icons
 *
 * @package Toolbox
 * @since Toolbox 1.0
 */
?>

<?php
    // Social Media Sharing
    $title = urlencode(get_the_title());
    $url = urlencode(get_permalink());
    $desc = urlencode(get_the_excerpt());
    $image = urlencode(get_thumbnail_custom($post->ID, 'post-thumbnail'));
?>
<div class="social-sharing">
    <a id="share-link" href="#">Share</a>
    <ul class="social-icons">
        <li>
            <a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-via="maraghaseltine" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        </li>
        <li>
              <!-- Load Facebook SDK for JavaScript -->
              <div id="fb-root"></div>
              <script>(function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s); js.id = id;
                js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
                fjs.parentNode.insertBefore(js, fjs);
              }(document, 'script', 'facebook-jssdk'));</script>

              <!-- Your share button code -->
              <div class="fb-share-button" 
                data-href="<?php echo get_permalink(); ?>" 
                data-layout="button_count">
        </li>
        <li>
            <script src="https://platform.linkedin.com/in.js" type="text/javascript">lang: en_US</script>
    <script type="IN/Share" data-url="<?php echo get_permalink(); ?>"></script>
        </li>
        <li>
            <script
                type="text/javascript"
                async defer
                src="//assets.pinterest.com/js/pinit.js"
            ></script>
            <a href="https://www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"></a>
        </li>
    </ul>
</div>