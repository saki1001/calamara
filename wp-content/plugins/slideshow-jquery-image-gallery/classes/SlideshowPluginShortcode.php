<?php
/**
 * Class SlideshowPluginShortcode is called on use of shortcode anywhere on the website.
 *
 * @author: Stefan Boonstra
 * @version: 03-07-12
 */
class SlideshowPluginShortcode {

	/** Variables */
	static $shortCode = 'slideshow_deploy';

	/**
	 * Function slideshowDeploy uses the prepare method of class Slideshow
	 * to deploy the slideshow on location of the [slideshow] shortcode.
	 *
	 * @param mixed $atts
	 * @return String $output
	 */
	static function slideshowDeploy($atts){
		$postId = '';
		if(isset($atts['id']))
			$postId = $atts['id'];

		return SlideshowPlugin::prepare($postId);
	}
}