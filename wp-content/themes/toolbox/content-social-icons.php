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
<ul class="social-icons">
    <li>Share with:</li>
    <li>
        <a onClick="window.open('http://pinterest.com/pin/create/button/?url=<?php echo $url; ?>&amp;media=<?php echo $image; ?>&amp;description=<?php echo $desc; ?>','sharer','toolbar=0,status=0,width=650,height=225');" href="javascript: void(0)" class="pintrest" title="Pin It" target="_blank"></a>
    </li>
    <li>
        <a onClick="window.open('http://twitter.com/intent/tweet?text=%23MaraGHaseltine%20<?php echo $title . ', ' . $url; ?>%20%40CalamaraG','sharer','toolbar=0,status=0,width=548,height=225');" href="javascript: void(0)" class="twitter" title="Tweet this" target="_blank"></a>
    </li>
    <li>
        <a onClick="window.open('http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title . ' | Mara G. Haseltine'; ?>&amp;p[summary]=<?php echo $desc + ' @MaraTheGreat';?>&amp;p[url]=<?php echo $url; ?>&amp;&amp;p[images][0]=<?php echo $image;?>','sharer','toolbar=0,status=0,width=548,height=325');" href="javascript: void(0)" class="facebook" title="Share on Facebook." target="_blank"></a>
    </li>
    <li>
        <a href="https://plus.google.com/share?url={<?php echo $url; ?>}" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="javascript: void(0)" class="google" title="Share on Google Plus." target="_blank"></a>
    </li>
</ul>