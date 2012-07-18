<?php
/**
 * Class SlideshowPluginWidget allows showing one of your slideshows in your widget area.
 *
 * @author: Stefan Boonstra
 * @version: 03-07-12
 */
class SlideshowPluginWidget extends WP_Widget {

	/** Variables */
	static $widgetName = 'Slideshow Widget';

	/**
	 * Initializes the widget
	 */
	function SlideshowPluginWidget(){
		// Settings
		$options = array(
			'classname' => 'SlideshowWidget',
			'description' => __('Enables you to show your slideshows in the widget area of your website.', 'slideshow-plugin')
		);

		// Create the widget.
		$this->WP_Widget(
			'slideshowWidget',
			__('Slideshow Widget', 'slideshow-plugin'),
			$options
		);
	}

	/**
	 * The widget as shown to the user.
	 *
	 * @param mixed array $args
	 * @param mixed array $instance
	 */
	function widget($args, $instance){
		// Get slideshowId
		$slideshowId = '';
		if(isset($instance['slideshowId']))
			$slideshowId = $instance['slideshowId'];

		// Get title
		$title = self::$widgetName;
		if(isset($instance['title']))
			$title = $instance['title'];

		// Prepare slideshow for output to website.
		$output = SlideshowPlugin::prepare($slideshowId);

		// Include widget html
		include(SlideshowPluginMain::getPluginPath() . '/views/' . __CLASS__ . '/widget.php');
	}

	/**
	 * The form shown on the admins widget page. Here settings can be changed.
	 *
	 * @param mixed array $instance
	 */
	function form($instance){
		// Defaults
		$defaults = array(
			'title' => __(self::$widgetName, 'slideshow-plugin'),
			'slideshowId' => -1
		);

		// Merge database settings with defaults
		$instance = wp_parse_args((array) $instance, $defaults);

		// Get slideshows
		$slideshows = get_posts(array(
			'numberposts' => null,
			'post_type' => SlideshowPluginPostType::$postType
		));

		// Include form
		include(SlideshowPluginMain::getPluginPath() . '/views/' . __CLASS__ . '/form.php');
	}

	/**
	 * Updates widget's settings.
	 *
	 * @param mixed array $newInstance
	 * @param mixed array $instance
	 */
	function update($newInstance, $instance){
		// Update title
		if(isset($newInstance['title']) && !empty($newInstance['title']))
			$instance['title'] = $newInstance['title'];

		// Update slideshowId
		if(isset($newInstance['slideshowId']) && !empty($newInstance['slideshowId']))
			$instance['slideshowId'] = $newInstance['slideshowId'];

		// Save
		return $instance;
	}

	/**
	 * Registers this widget (should be called upon widget_init action hook)
	 */
	static function registerWidget(){
		register_widget(__CLASS__);
	}
}