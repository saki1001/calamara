var $j = jQuery.noConflict();

$j(window).on('load', function() {

    // Set Image Width and Margin
    // for centering images in portfolio pages
    $j('#media .image-container figure').each(function(i) {
        
        var img = $j(this).children('img');
        var width = img.width();
        var height = img.height();
        var marginTop = (500 - height)/2 + 'px';
        
        // If image shorter than container
        // add marginTop and negative marginTop to bottom
        // to prevent container from becoming too large
        if( height < img.parent().outerHeight() ) {

            img.css({
                'width': width,
                'margin': marginTop + ' auto -' + marginTop
            });
        }
    });

});
