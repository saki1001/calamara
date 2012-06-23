<?php
/**
 * Class Slideslow is called whenever a slideshow do_action tag is come across.
 * Responsible for outputting the slideshow's HTML, CSS and Javascript.
 *
 * @author: Stefan Boonstra
 * @version: 15-06-12
 */
class Slideshow {

	/** Variables */
	private static $stylesheet = '/style/style.css';
	private static $scriptfile = '/js/slideshow.js';
	private static $jQuery = '/js/jquery-min.js';
	private static $htmlfile = 'slideshow.html';

	/**
	 * Function deploy prints out the prepared html
	 *
	 * @param int $postId
	 */
	static function deploy($postId = ''){
		echo self::prepare($postId);
	}

	/**
	 * Function prepare returns the required html and enqueues
	 * the scripts and stylesheets necessary for displaying the slideshow
	 *
	 * Passing this function no parameter or passing it a negative one will
	 * result in a random pick of slideshow
	 *
	 * @param int $postId
	 * @return String $output
	 */
	static function prepare($postId = ''){
		// Check if defined which Slideshow to use
		if(empty($postId) || !is_numeric($postId) || $postId < 0){
			$post = get_posts(array(
				'numberposts' => 1,
				'orderby' => 'rand',
				'post_type' => SlideshowPostType::$postType
			));

			if(is_array($post))
				$post = $post[0];
		}else
			$post = wp_get_single_post($postId);

		// Exit function on error
		if(empty($post))
			return;

		// Store output for return
		$output = '';

		// Output basic html
		$output .= file_get_contents(SlideshowMain::getPluginUrl() . '/views/' . __CLASS__ . '/' . self::$htmlfile);

		// Get settings
		$settings = SlideshowPostType::$defaults;
		foreach($settings as $key => $value){
			$metaValue = get_post_meta($post->ID, $key, true);
			if(!empty($metaValue))
				$settings[$key] = $metaValue;
		}

		// Get images
		$imageObjects = get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => null,
			'post_parent' => $post->ID
		));

		// Load images into array
		$images = array();
		foreach($imageObjects as $key => $imageObject){
			$images[$key] = array(
				'img' => $imageObject->guid,
				'title' => $imageObject->post_title,
				'description' => $imageObject->post_content,
				'url' => $imageObject->guid
			);
		}

		// Output settings and images
		$output .= '
			<script type="text/javascript">
				var slideshow_images = ' . json_encode($images) . ';
				var slideshow_settings = ' . json_encode($settings) . ';
			</script>
		';

		// Enqueue jQuery
		wp_enqueue_script(
			'jQuery',
			SlideshowMain::getPluginUrl() . self::$jQuery,
			array(),
			'',
			true
		);

		// Enqueue slideshow script
		wp_enqueue_script(
			'slideshow_script',
			SlideshowMain::getPluginUrl() . self::$scriptfile,
			array('jQuery'),
			'',
			true
		);

		// Enqueue stylesheet
		wp_enqueue_style(
			'slideshow_style',
			SlideshowMain::getPluginUrl() . self::$stylesheet
		);

		// Return output
		return $output;
	}
}