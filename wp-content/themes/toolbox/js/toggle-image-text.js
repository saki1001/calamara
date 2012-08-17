var $j = jQuery.noConflict();

$j(document).ready(function() {

    // show or hide images and text
    var toggleImageText = function() {
        if ($j('#content').hasClass('show-images')) {
            $j('#content').removeClass('show-images');
            $j('#content').addClass('show-text');
        } else if ($j('#content').hasClass('show-text')) {
            $j('#content').removeClass('show-text');
            $j('#content').addClass('show-images');
        } else {
            // do nothing
        }
    
        return false;
    };

    $j('.toggle-link a').bind('click', toggleImageText);

});