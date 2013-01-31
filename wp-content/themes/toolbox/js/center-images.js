var $j = jQuery.noConflict();

$j(document).ready(function() {

    // Set Image Width and Margin
    // for centering images in portfolio pages
    $j('#media .image-container figure').each(function(i) {
        
        var width = $j(this).children('img').width();
        var height = $j(this).children('img').height();
        var marginTop = (500 - height)/2 + 'px';
        
        // add marginTop and negative marginTop to bottom
        // to prevent container from becoming too large
        $j(this).css({
            'width': width,
            'margin': marginTop + ' auto -' + marginTop
        });
    });

});