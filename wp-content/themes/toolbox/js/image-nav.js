var $j = jQuery.noConflict();

$j(document).ready(function() {
    
    var imagesArray = [];
    
    var fullWidth = 0;
    
    // get full width
    $j('#scroll .image-container').each(function() {
        
        w = $j(this).outerWidth();
        // adding w + 1 because width is rounded
        fullWidth += w + 1;
    });
    
    // set correct width
    $j('#scroll').css('width', fullWidth);
    
    // now determine positions
    $j('#scroll .image-container').each(function(i) {
        
        var position = $j(this).position();
        
        $j(this).attr('data-index', i);
        
        imagesArray.push({
            pos: position.left,
            w: $j(this).width(),
            h: $j(this).height()
        });
        
    });
    
    // add pager elements
    $j.each(imagesArray, function(i) {
        
        var imgPosition = this.pos;
        $j('#pager').append('<a href="#" class="page" data-index="' + i + '" data-position="' + imgPosition + '"></a>');
        
    });
    
    // get pager width
    var pagerWidth = 0;
    $j('#pager a').each(function() {
        var pw = $j(this).outerWidth(true);
        pagerWidth += pw;
    });
    
    // now set width of pager element
    // for centering pager links
    $j('#pager').css('width', pagerWidth);
    
    // find first and last image
    $j('.image-container').each(function(i) {
        
        var imageIndex = $j(this).attr('data-index');
        var lastIndex = String(imagesArray.length - 1);
        
        if(imageIndex === '0') {
            $j(this).addClass('firstSlide');
        }
        
        if(imageIndex === lastIndex) {
            $j(this).addClass('lastSlide');
        }
    });
    
    var labelCurrentPosition = function(num) {
        
        // remove old previousSlide class
        $j('.previousSlide').removeClass('previousSlide');
        
        // add new previousSlide
        $j('.currentSlide').addClass('previousSlide');
        
        // remove old currentSlide
        $j('.currentSlide').removeClass('currentSlide');
        
        // add new currentSlide
        $j('.page[data-index="' + num +'"]').addClass('currentSlide');
        $j('#scroll .image-container[data-index="' + num +'"]').addClass('currentSlide');
        
        // clear any inactive classes
        $j('.nav.inactive').removeClass('inactive');
        
        // make arrows inactive for first/last slide
        if ($j('.currentSlide').hasClass('firstSlide')) {
            $j('.nav.prev').addClass('inactive');
        }
        
        if ($j('.currentSlide').hasClass('lastSlide')) {
            $j('.nav.next').addClass('inactive');
        }
        
    };
    
    // for document load
    labelCurrentPosition(0);
    
    var animateScroll = function(pos, time) {
        
        $j('#scroll .image-container.previousSlide').animate({
            opacity: 0.25
        }, 200 , function() {
            // animation complete
        });
        
        $j('#scroll').animate({
            left: pos
        }, time , function() {
            // animation complete
            $j('#scroll .image-container.currentSlide').animate({
                opacity: 1
            }, 200 , function() {
                // animation complete
            });
            
        });
        
    };
    
    var showImage = function() {
        
        var curDataIndex = $j(this).attr('data-index');
        labelCurrentPosition(curDataIndex);
        
        var positionLeft = '-' + $j(this).attr('data-position') + 'px';
        var curPosition = $j('#scroll').position();
        
        // base animation time on ratio of how far you're moving
        var animTime = Math.abs(Math.abs(parseInt(curPosition.left)) - Math.abs(parseInt(positionLeft)))/2;
        
        animateScroll(positionLeft, animTime);
        
        return false;
    };
    
    var showPrevNext = function() {
        
        var curDataIndex = parseInt($j('.page.currentSlide').attr('data-index'));
        
        if ($j(this).hasClass('prev')) {
            var newDataIndex = curDataIndex - 1;
        } else {
            var newDataIndex = curDataIndex + 1;
        }
        
        var showImage = '.page[data-index="' + newDataIndex + '"]';
        
        if ( $j(showImage)[0] != null ) {
            
            var positionLeft = '-' + $j(showImage).attr('data-position') + 'px';
            
            labelCurrentPosition(newDataIndex);
            animateScroll(positionLeft, 300);
            
        }
        
        return false;
    };
    
    var showNav = function() {
        $j('.nav').fadeIn(100);
    };
    
    var hideNav = function() {
        $j('.nav').fadeOut(100);
    };
    
    $j('#pager .page').bind('click', showImage);
    $j('.border .arrow').bind('click', showPrevNext);
    $j('#media').bind({
        'mouseenter': showNav,
        'mouseleave': hideNav
    });
});