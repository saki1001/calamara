<?php
/**
 * Slideshow post type creates a post type specifically designed for
 * slideshows and their individual settings
 *
 * @author: Stefan Boonstra
 * @version: 26-06-12
 */
class SlideshowPostType {

	/** Variables */
	private static $adminIcon = 'images/adminIcon.png';
	static $postType = 'slideshow';
	static $settings = null;
	static $settingsMetaKey = 'settings';
	static $defaultSettings = array(
		'slideSpeed' => 1,
		'descriptionSpeed' => 0.3,
		'intervalSpeed' => 5,
		'width' => 0,
		'height' => 200,
		'stretch' => 'false',
		'controllable' => 'true',
		'urlsActive' => 'false',
		'showText' => 'true'
	);
	static $defaultStyleSettings = array(
		'style' => 'style-dark.css',
		'custom-style' => ''
	);

	/**
	 * Initialize Slideshow post type.
	 * Called on load of plugin
	 */
	static function initialize(){
		add_action('init', array(__CLASS__, 'registerSlideshowPostType'));
		add_action('save_post', array(__CLASS__, 'save'));
	}

	/**
	 * Registers new posttype slideshow
	 */
	static function registerSlideshowPostType(){
		register_post_type(
			self::$postType,
			array(
				'labels' => array(
					'name' => __('Slideshows', 'slideshow-plugin'),
					'singlular_name' => __('Slideshow', 'slideshow-plugin'),
					'add_new_item' => __('Add New Slideshow', 'slideshow-plugin'),
					'edit_item' => __('Edit slideshow', 'slideshow-plugin'),
					'new_item' => __('New slideshow', 'slideshow-plugin'),
					'view_item' => __('View slideshow', 'slideshow-plugin'),
					'search_items' => __('Search slideshows', 'slideshow-plugin'),
					'not_found' => __('No slideshows found', 'slideshow-plugin'),
					'not_found_in_trash' => __('No slideshows found', 'slideshow-plugin')
				),
				'public' => false,
				'publicly_queryable' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'query_var' => true,
				'rewrite' => true,
				'capability_type' => 'post',
				'has_archive' => true,
				'hierarchical' => false,
				'menu_position' => null,
				'menu_icon' => SlideshowMain::getPluginUrl() . '/' . self::$adminIcon,
				'supports' => array('title'),
				'register_meta_box_cb' => array(__CLASS__, 'registerMetaBoxes')
			)
		);
	}

	/**
	 * Adds custom meta boxes to slideshow post type.
	 */
	static function registerMetaBoxes(){
		add_meta_box(
			'information',
			__('Information', 'slideshow-plugin'),
			array(__CLASS__, 'informationMetaBox'),
			self::$postType,
			'normal',
			'high'
		);

		add_meta_box(
			'slides-list',
			__('Slides List', 'slideshow-plugin'),
			array(__CLASS__, 'slidesMetaBox'),
			self::$postType,
			'side',
			'default'
		);

		add_meta_box(
			'style',
			__('Slideshow Style', 'slideshow-plugin'),
			array(__CLASS__, 'styleMetaBox'),
			self::$postType,
			'normal',
			'low'
		);

		add_meta_box(
			'settings',
			__('Slideshow Settings', 'slideshow-plugin'),
			array(__CLASS__, 'settingsMetaBox'),
			self::$postType,
			'normal',
			'low'
		);
	}

	/**
	 * Shows some information about this slideshow
	 */
	static function informationMetaBox(){
		global $post;

		$snippet = htmlentities(sprintf('<?php do_action(\'slideshow_deploy\', \'%s\'); ?>', $post->ID));
		$shortCode = htmlentities(sprintf('[' . SlideshowShortcode::$shortCode . ' id=%s]', $post->ID));

		include(SlideshowMain::getPluginPath() . '/views/' . __CLASS__ . '/information.php');
	}

	/**
	 * Shows slides currently in slideshow
	 */
	static function slidesMetaBox(){
		global $post;

		// Media upload button
		$uploadButton = SlideshowUpload::getUploadButton();

		// Get slideshow attachments
		$attachments = self::getAttachments($post->ID);

		// Set url from which a substitute icon can be fetched
		$noPreviewIcon = SlideshowMain::getPluginUrl() . '/images/no-img.png';

		// Include slides preview file
		include(SlideshowMain::getPluginPath() . '/views/' . __CLASS__ . '/slides.php');
	}

	/**
	 * Shows style used for slideshow
	 */
	static function styleMetaBox(){
		global $post;

		// Get settings
		$defaultSettings = self::$defaultStyleSettings;
		$settings = self::getSettings($post->ID);

		// Get styles from style folder
		$styles = array();
		$cssExtension = '.css';
		if($handle = opendir(SlideshowMain::getPluginPath() . '/style/Slideshow/'))
			while(($file = readdir($handle)) !== false)
				if(strlen($file) >= strlen($cssExtension) && substr($file, strlen($file) - strlen($cssExtension)) === $cssExtension)
					// Converts the css file's name (style-mystyle.css) and converts it to a user readable name by
					// cutting the style- prefix off, replacing hyphens with spaces and getting rid of the .css.
					// Then it capitalizes every word and saves it to the $styles array under the original $file name.
					$styles[$file] = ucwords(str_replace(
						'-',
						' ',
						preg_replace(
							'/style-/',
							'',
							substr(
								$file,
								0,
								'-' . strlen($cssExtension)),
							1
					)));

		// Fill custom style with default css if empty
		if(empty($settings['custom-style'])){
			ob_start();
			include(SlideshowMain::getPluginPath() . '/style/Slideshow/style-dark.css');
			$settings['custom-style'] = ob_get_clean();
		}

		// Enqueue associating script
		wp_enqueue_script(
			'style-settings',
			SlideshowMain::getPluginUrl() . '/js/' . __CLASS__ . '/style-settings.js',
			array('jquery'),
			false,
			true
		);

		// Include style settings file
		include(SlideshowMain::getPluginPath() . '/views/' . __CLASS__ . '/style-settings.php');
	}

	/**
	 * Shows settings for particular slideshow
	 */
	static function settingsMetaBox(){
		global $post;

		// Get settings
		$defaultSettings = self::$defaultSettings;
		$settings = self::getSettings($post->ID);

		// Include
		include(SlideshowMain::getPluginPath() . '/views/' . __CLASS__ . '/settings.php');
	}

	/**
	 * Called for saving metaboxes
	 *
	 * @param int $postId
	 * @return int $postId On failure
	 */
	static function save($postId){
		// Verify nonce, check if user has sufficient rights and return on auto-save.
		if((isset($_POST['nonce']) && !wp_verify_nonce($_POST['nonce'], plugin_basename(__FILE__))) ||
			!current_user_can('edit_post', $postId) ||
			defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $postId;

		// Get old settings
		$oldSettings = get_post_meta($postId, self::$settingsMetaKey, true);
		if(!is_array($oldSettings))
			$oldSettings = array();

		// Filter post results, otherwise we'd save all post variables like post_id and ping_status.
		$settings = array();
		$defaultSettings = array_merge(
			self::$defaultSettings,
			self::$defaultStyleSettings);
		foreach($_POST as $key => $value)
			if(isset($defaultSettings[$key]))
				$settings[$key] = $value;

		// Save settings
		update_post_meta(
			$postId,
			self::$settingsMetaKey,
			array_merge(
				self::$defaultSettings,
				self::$defaultStyleSettings,
				$oldSettings,
				$settings
		));
	}

	/**
	 * Gets settings for the slideshow with the settings meta key
	 *
	 * @return mixed $settings
	 */
	static function getSettings($postId){
		if(!isset(self::$settings)){
			// Get settings
			$currentSettings = get_post_meta(
				$postId,
				self::$settingsMetaKey,
				true
			);

			if(empty($currentSettings))
				$currentSettings = array();

			// Merge settings
			self::$settings = $settings = array_merge(
				self::$defaultSettings,
				self::$defaultStyleSettings,
				$currentSettings
			);
		}else
			$settings = self::$settings;

		return $settings;
	}

	/**
	 * Get all attachments attached to the parsed postId
	 *
	 * @param int $postId
	 * @return mixed $attachments
	 */
	static function getAttachments($postId){
		if(!is_numeric($postId))
			return array();

		return get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $postId
		));
	}
}