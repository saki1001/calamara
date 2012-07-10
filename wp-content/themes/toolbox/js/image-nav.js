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
    

    
    $j.each(imagesArray, function(i) {
        
        var imgPosition = this.pos;
        $j('#thumbs #pager').append('<a href="#" class="thumb" data-index="' + i + '" data-position="' + imgPosition + '"></a>');
    });
    
    var labelCurrentPosition = function(num) {
        
        // remove old previous class
        $j('.previous').removeClass('previous');
        
        // add new previous
        $j('.current').addClass('previous');
        
        // remove old current
        $j('.current').removeClass('current');
        
        // add new current
        $j('.thumb[data-index="' + num +'"]').addClass('current');
        $j('#scroll .image-container[data-index="' + num +'"]').addClass('current');
        
    };
    
    // for document load
    labelCurrentPosition(0);
    
    var animateScroll = function(pos, time) {
        
        $j('#scroll .image-container.previous').animate({
            opacity: 0.25
        }, 200 , function() {
            // animation complete
        });
        
        $j('#scroll').animate({
            left: pos
        }, time , function() {
            // animation complete
            $j('#scroll .image-container.current').animate({
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
        var animTime = Math.abs(Math.abs(parseInt(curPosition.left)) - Math.abs(parseInt(positionLeft)));
        
        animateScroll(positionLeft, animTime);
        
    };
    
    var showPrevNext = function() {
        
        var curDataIndex = parseInt($j('.thumb.current').attr('data-index'));
        
        if ($j(this).hasClass('prev')) {
            var newDataIndex = curDataIndex - 1;
        } else {
            var newDataIndex = curDataIndex + 1;
        }
        
        var showImage = '.thumb[data-index="' + newDataIndex + '"]';
        console.log($j(showImage));
        
        if ( $j(showImage)[0] != null ) {
            
            var positionLeft = '-' + $j(showImage).attr('data-position') + 'px';
            
            labelCurrentPosition(newDataIndex);
            animateScroll(positionLeft, 300);
            
        }
        
    };
    
    
    $j('#thumbs .thumb').bind('click', showImage);
    $j('#thumbs .nav').bind('click', showPrevNext);
    
});