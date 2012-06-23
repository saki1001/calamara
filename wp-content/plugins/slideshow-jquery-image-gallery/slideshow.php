<?php
/*
 Plugin Name: Slideshow
 Plugin URI: http://stefanboonstra.com
 Description: This plugin offers a slideshow that is easily deployable in your website. Images can be assigned through the media page. Options are customizable for every single slideshow on your website.
 Version: 1.2.1
 Requires at least: 3.0
 Author: StefanBoonstra
 Author URI: http://stefanboonstra.com
 License: GPL
*/

/**
 * Class SlideshowMain fires up the application on plugin load and provides some
 * methods for the other classes to use like the auto-includer and the
 * base path/url returning method.
 *
 * @author Stefan Boonstra
 * @version 18-06-12
 */
class SlideshowMain {

	/**
	 * Bootstraps the application by assigning the right functions to
	 * the right action hooks.
	 */
	static function bootStrap(){
		self::autoInclude();

		// Initialize translation on init
		add_action('init', array(__CLASS__, 'translator'));

		// Deploy slide show on do_action('slideshow_deploy'); hook.
		add_action('slideshow_deploy', array('Slideshow', 'deploy'));

		// Add shortcode
		add_shortcode(SlideshowShortcode::$shortCode, array('SlideshowShortcode', 'slideshowDeploy'));

		// Register widget
		add_action('widgets_init', array('SlideshowWidget', 'registerWidget'));

		// Register slideshow post type
		add_action('init', array('SlideshowPostType', 'registerSlideshowPostType'));
		add_action('save_post', array('SlideshowPostType', 'save'));
	}

	/**
	 * Translates the plugin
	 */
	static function translator(){
		load_plugin_textdomain(
			'slideshow-plugin',
			false,
			dirname(plugin_basename(__FILE__)) . '/languages/'
		);
	}

	/**
	 * Returns url to the base directory of this plugin.
	 *
	 * @return string pluginUrl
	 */
	static function getPluginUrl(){
		return plugins_url('', __FILE__);
	}

	/**
	 * Returns path to the base directory of this plugin
	 *
	 * @return string pluginPath
	 */
	static function getPluginPath(){
		return dirname(__FILE__);
	}

	/**
	 * This function will load classes automatically on-call.
	 */
	function autoInclude(){
		if(!function_exists('spl_autoload_register'))
			return;

		function slideshowFileAutoloader($name) {
			$name = str_replace('\\', DIRECTORY_SEPARATOR, $name);
			$file = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . $name . '.php';

			if(is_file($file))
				require_once $file;
		}

		spl_autoload_register('slideshowFileAutoloader');
	}
}

/**
 * Activate plugin
 */
SlideShowMain::bootStrap();