var $j = jQuery.noConflict();

$j(window).on('load', function() {

    $j('#share-link').on('click', function(e) {
        e.preventDefault();
        $j(this).next('.social-icons').toggleClass('active');
    });

});
