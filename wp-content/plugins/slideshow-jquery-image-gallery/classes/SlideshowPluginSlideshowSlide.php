<?php
/**
 * SlideshowPluginSlideshowSlide provides functions for outputting a slide for back-end as well as front-end display.
 * It also provides all slide templates.
 *
 * Every slide needs to pass their slide type in the properties array. A slide type can be one of the following:
 * - text
 * - attachment
 * - video
 *
 * A text slide can consist of one of these properties:
 * - title
 * - description
 * - textColor
 * - color
 * - url
 * - urlTarget
 *
 * An attachment slide can consist of one of these properties:
 * - title
 * - description
 * - url
 * - urlTarget
 * - postId (required)
 *
 * A video slide can consist of one these properties:
 * - videoId (required)
 *
 * @since 2.2.0
 * @author Stefan Boonstra
 * @version 01-02-2013
 */
class SlideshowPluginSlideshowSlide {

	/** Properties */
	private $properties;

	/**
	 * Creates a slide object with the parsed properties. For information on how to build the properties array, view
	 * the class' description.
	 *
	 * @since 2.2.0
	 * @param array $properties
	 */
	function __construct($properties){

		if(is_array($properties))
			$this->properties = $properties;
	}

	/**
	 * Build slide for front-end use.
	 *
	 * Returns when $return is true, prints when $return is false.
	 *
	 * @since 2.2.0
	 * @param boolean $return (optional, defaults to true)
	 * @return String $frontEndHTML
	 */
	function toFrontEndHTML($return = true){

		// Exit when no slide type has been set or is empty
		if(!isset($this->properties['type']) || empty($this->properties['type']))
			return '';

		$properties = $this->properties;

		// Build file path
		$file = SlideshowPluginMain::getPluginPath() . DIRECTORY_SEPARATOR .
			'views' . DIRECTORY_SEPARATOR .
			__CLASS__ . DIRECTORY_SEPARATOR .
			'frontend_' . $this->properties['type'] . '.php';

		// Include file path
		if(!file_exists($file))
			return '';

		// Start output buffering if output needs to be returned
		if($return)
			ob_start();

		include $file;

		// Return output
		if($return)
			return ob_get_clean();

		return '';
	}

	/**
	 * Build slide for back-end use.
	 *
	 * Returns when $return is true, prints when $return is false.
	 *
	 * @since 2.2.0
	 * @param boolean $return (optional, defaults to true)
	 * @return String $backEndHTML
	 */
	function toBackEndHTML($return = true){

		// Exit when no slide type has been set or is empty
		if(!isset($this->properties['type']) || empty($this->properties['type']))
			return '';

		// Make properties array available to included file
		$properties = $this->properties;

		// The name is used to prefix a setting name with. Although the ID's are set on load, set a random one to be sure
		$name = SlideshowPluginSlideshowSettingsHandler::$slidesKey . '[' . rand() . ']';

		// Build file path
		$file = SlideshowPluginMain::getPluginPath() . DIRECTORY_SEPARATOR .
			'views' . DIRECTORY_SEPARATOR .
			__CLASS__ . DIRECTORY_SEPARATOR .
			'backend_' . $this->properties['type'] . '.php';

		// Include file path
		if(!file_exists($file))
			return '';

		// Start output buffering if output needs to be returned
		if($return)
			ob_start();

		include $file;

		// Return output
		if($return)
			return ob_get_clean();

		return '';
	}

	/**
	 * Build templates for back-end slides.
	 *
	 * Returns when $return is true, prints when $return is false.
	 *
	 * @since 2.2.0
	 * @param boolean $return (optional, defaults to true)
	 * @return String $backEndTemplates
	 */
	static function getBackEndTemplates($return = true){

		// Start output buffering if output needs to be returned
		if($return)
			ob_start();

		include SlideshowPluginMain::getPluginPath() . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . 'backend_templates.php';

		// Return output
		if($return)
			return ob_get_clean();

		return '';
	}
}