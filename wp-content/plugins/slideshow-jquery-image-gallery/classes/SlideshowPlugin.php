<?php
/**
 * Class SlideslowPlugin is called whenever a slideshow do_action tag is come across.
 * Responsible for outputting the slideshow's HTML, CSS and Javascript.
 *
 * @author: Stefan Boonstra
 * @version: 03-07-12
 */
class SlideshowPlugin {

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
				'post_type' => SlideshowPluginPostType::$postType
			));

			if(is_array($post))
				$post = $post[0];
		}else
			$post = wp_get_single_post($postId);

		// Exit function on error
		if(empty($post))
			return '';

		// Get settings
		$settings = SlideshowPluginPostType::getSettings($post->ID);

		// Load images into array
		$images = array();
		$imageObjects = SlideshowPluginPostType::getAttachments($post->ID);
		foreach($imageObjects as $key => $imageObject){
			$images[$key] = array(
				'img' => $imageObject->guid,
				'title' => $imageObject->post_title,
				'description' => $imageObject->post_content,
				'url' => $imageObject->guid
			);
		}

		// Check in what way the stylesheet needs to be loaded, .css can be enqueued, custom styles need to be printed.
		$printStyle = '';
		if($settings['style'] == 'custom-style') // Enqueue stylesheet
			$printStyle = $settings['custom-style'];
		else // Custom style, print it.
			wp_enqueue_style(
				'slideshow_style',
				SlideshowPluginMain::getPluginUrl() . '/style/' . __CLASS__ . '/' . $settings['style']
			);

		// Include output file that stores output in $output.
		$output = '';
		ob_start();
		include(SlideshowPluginMain::getPluginPath() . '/views/' . __CLASS__ . '/slideshow.php');
		$output .= ob_get_clean();

		// Enqueue slideshow script
		wp_enqueue_script(
			'slideshow_script',
			SlideshowPluginMain::getPluginUrl() . '/js/' . __CLASS__ . '/slideshow.js',
			array('jquery'),
			false,
			true
		);

		// Return output
		return $output;
	}
}