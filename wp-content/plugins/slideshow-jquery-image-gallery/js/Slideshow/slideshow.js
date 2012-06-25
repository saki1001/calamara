/**
 * Slideshow class contains all functions for animating
 * and controlling the slideshow.
 *
 * @author Stefan Boonstra
 * @version 25-05-12
 */
var Slideshow = {
	
	/** Functional variables (non-customizable) */
	slideshowContainer: '.slideshow_container',
	sliderBox: '.slideshow',
	descriptionBox: '.slideshow_container .descriptionbox',
	buttons: '.slideshow_container .button',
	buttonNext: '.slideshow_container .next',
	buttonPrevious: '.slideshow_container .previous',
	divName: 'slideshow_div',
	currentSlide: 0,
	interval: '',
	buttonsActive: false,
	
	/** Customizable variables */
	images: new Array(),
	slideSpeed: 1000,
	descriptionSpeed: 300,
	intervalSpeed: 6000,
	width: 0,
	height: 100,
	stretch: false,
	controllable: true,
	urlsActive: false,
	showText: true,

	/**
	 * Initializes the app by loading all images into divs.
	 */
	initialize: function(){
		// Prevent reference errors
		var slideShow = this;

		// Get settings from page
		slideShow.getSettings();

		// Set height and width
		jQuery(slideShow.slideshowContainer + ' ' + slideShow.sliderBox).css({
			'height': slideShow.height,
			'width': slideShow.width
		});

		// Empty slideshow
		jQuery(slideShow.sliderBox).empty();

		// Enable controls
		slideShow.buttonsActive = true;

		// Only add img height element when images should not be stretched
		var addDimensions = '';
		if(slideShow.stretch)
			addDimensions = ' width="' + slideShow.width + '" height="' + slideShow.height + '" '
		else
			addDimensions = ' height="' + slideShow.height + '" ';

		// Add all divs
		jQuery.each(slideShow.images, function(index, image){
			var url = '';
			if(image['url'].length > 0 && slideShow.urlsActive)
				url = 'href="' + image['url'] + '"';

			jQuery(slideShow.sliderBox).append(
				'<div class="' + slideShow.divName + ' ' + slideShow.divName + index + '">'+
					'<a ' + url + '>' +
					'<img '+
					'alt="' + image['title'] + '" ' +
					'src="' + image['img'] + '" ' +
					addDimensions +
					'/>' +
					'</a>' +
					'</div>'
			);
		});

		slideShow.slideIn(slideShow.currentSlide);
	},

	/**
	 * Gets and assigns settings from page variable slideshow_settings
	 */
	getSettings: function(){
		// Prevent reference errors
		var slideShow = this;

		var settings = slideshow_settings;

		// Set speeds
		if(settings['slideSpeed'] != '')
			slideShow.slideSpeed = settings['slideSpeed'] * 1000;
		if(settings['descriptionSpeed'] != '')
			slideShow.descriptionSpeed = settings['descriptionSpeed'] * 1000;
		if(settings['intervalSpeed'] != '')
			slideShow.intervalSpeed = settings['intervalSpeed'] * 1000;

		// Set dimensions
		if(settings['width'] != '')
			slideShow.width = settings['width'];
		if(settings['height'] != '' && settings['height'] >= 100)
			slideShow.height = settings['height'];

		// Adjust width if width is 0
		if(slideShow.width <= 0)
			slideShow.width = parseInt(jQuery(slideShow.slideshowContainer).parent().css('width'), 10);

		// Adjust button positioning
		var positioning = ((slideShow.height - 100) / 2) + 100;
		jQuery(slideShow.buttons).css({ 'margin-top': '-' + positioning + 'px' });

		// Miscellaneous settings
		if(settings['stretch'] != '')
			if(settings['stretch'] == 'true')
				slideShow.stretch = true;
			else
				slideShow.stretch = false;
		if(settings['controllable'] != '')
			if(settings['controllable'] == 'true')
				slideShow.controllable = true;
			else
				slideShow.controllable = false;
		if(settings['urlsActive'] != '')
			if(settings['urlsActive'] == 'true')
				slideShow.urlsActive = true;
			else
				slideShow.urlsActive = false;
		if(settings['showText'] != '')
			if(settings['showText'] == 'true')
				slideShow.showText = true;
			else
				slideShow.showText = false;
	},

	/**
	 * Slides in next slide.
	 */
	nextSlide: function(){
		// Prevent reference errors
		var slideShow = this;

		slideShow.slideOut(slideShow.currentSlide);

		slideShow.currentSlide++;
		if(slideShow.currentSlide > slideShow.images.length - 1)
			slideShow.currentSlide = 0;

		setTimeout(
			function(){ slideShow.slideIn(slideShow.currentSlide); },
			slideShow.slideSpeed
		);
	},

	/**
	 * Slides in previous slide.
	 */
	previousSlide: function(){
		// Prevent reference errors
		var slideShow = this;

		slideShow.slideOut(slideShow.currentSlide);

		slideShow.currentSlide--;
		if(slideShow.currentSlide < 0)
			slideShow.currentSlide = slideShow.images.length - 1;

		setTimeout(
			function(){ slideShow.slideIn(slideShow.currentSlide) },
			slideShow.slideSpeed
		);
	},

	/**
	 * Animates the image with imageId and the descriptionbox sliding into view.
	 * Also fills up descriptionbox with corresponding title/text.
	 *
	 * @param imageId
	 */
	slideIn: function(imageId){
		// Prevent reference errors
		var slideShow = this;

		// Slide in imagediv
		jQuery('.' + slideShow.divName + imageId).animate({
			'width': slideShow.width + 'px'},
			slideShow.slideSpeed
		);

		// Set text of descriptionbox and slide it up, if showText is true
		if(slideShow.showText){
			jQuery(slideShow.descriptionBox).html(
				'<h2>' + slideShow.images[imageId]['title'] + '</h2><p>' + slideShow.images[imageId]['description'] + '</p>'
			);
			jQuery(slideShow.descriptionBox).animate({
					'height': slideShow.height + 'px',
					'margin-top': '-' + slideShow.height + 'px'},
				slideShow.descriptionSpeed
			);
		}
		
		// Deactivate buttons for a while so the user can't mess up the app
		slideShow.buttonsActive = false;
		setTimeout(function(){ slideShow.buttonsActive = true; }, slideShow.slideSpeed);
	},

	/**
	 * Animates the image with imageId and the descriptionbox sliding out of sight.
	 *
	 * @param imageId
	 */
	slideOut: function(imageId){
		// Prevent reference errors
		var slideShow = this;

		// Slide down descriptionbox
		jQuery(slideShow.descriptionBox).animate({
			'height': '0px',
			'margin-top': '0px'},
			slideShow.descriptionSpeed
		);

		// Slide out imagediv
		jQuery('.' + slideShow.divName + imageId).animate({
			'width': '0px'},
			slideShow.slideSpeed
		);

		// Deactivate buttons for a while so the user can't mess up the app
		slideShow.buttonsActive = false;
		setTimeout(function(){ slideShow.buttonsActive = true; }, slideShow.slideSpeed);
	},
	
	/**
	 * Sets css visibility of the buttons, if controls active are set to true.
	 */
	showButtons: function(show){
		// Prevent reference errors
		var slideShow = this;
		
		var visibility = 'visible';
		if(!show || !slideShow.controllable)
			visibility = 'hidden';
		
		// Set visibility
		jQuery(slideShow.buttons).css({'visibility': visibility});
	},

	/**
	 * Resets interval and starts counting from zero again.
	 */
	resetInterval: function(){
		// Prevent reference errors
		var slideShow = this;

		clearInterval(slideShow.interval);
		slideShow.interval = setInterval(function(){ slideShow.nextSlide(); }, slideShow.intervalSpeed);
	}
};

/**
 * When clicked on next button, next slide is shown.
 */
jQuery(Slideshow.buttonNext).click(function(){
	if(!Slideshow.buttonsActive)
		return;

	Slideshow.resetInterval();
	Slideshow.nextSlide();
});

/**
 * When clicked on previous button, previous slide is shown.
 */
jQuery(Slideshow.buttonPrevious).click(function(){
	if(!Slideshow.buttonsActive)
		return;

	Slideshow.resetInterval();
	Slideshow.previousSlide();
});

jQuery(document).ready(function(){
	if(slideshow_images != '')
		Slideshow.images = slideshow_images;

	if(Slideshow.images.length == 0)
		return;

	Slideshow.initialize();

	if(Slideshow.images.length > 1){
		Slideshow.showButtons(true);
		Slideshow.interval = setInterval(function(){ Slideshow.nextSlide(); }, Slideshow.intervalSpeed);
	}
});