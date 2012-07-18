<?php
/**
 * Class SlideshowPluginFeedback collects plugin feedback which helps resolving plugin-related issues faster.
 *
 * @author: Stefan Boonstra
 * @version: 03-07-12
 */
class SlideshowPluginFeedback {

	/** Variables */
	static $feedbackDateKey = 'slideshow-feedback-date';
	static $feedbackInterval = 1;
	static $method = 'alter';
	static $access = 'OQvsxI4EV1ifIEGW';
	static $address = 'http://stefanboonstra.com/API/Wordpress/Plugin/Slideshow/feedback.php';

	/**
	 * Called on admin_head hook. Feedback that doesn't need to be collected
	 * particularly on the live website shouldn't slow it down either.
	 */
	static function adminInitialize(){
		self::generalInformation();
	}

	/**
	 * Collects general information about the slideshow
	 *
	 * @param boolean $checkInterval
	 */
	static function generalInformation($checkInterval = true){
		if($checkInterval){
			$dateFormat = 'Y-m-d';
			$lastFeedback = get_option(self::$feedbackDateKey);
			if($lastFeedback !== false && ((strtotime(date($dateFormat)) - strtotime($lastFeedback)) / (60 * 60 * 24)) <= self::$feedbackDateKey)
				return;
			else
				update_option(self::$feedbackDateKey, date($dateFormat));
		}

		$variables = array(
			'method' => self::$method,
			'access' => self::$access,
			'host' => $_SERVER['HTTP_HOST'],
			'version' => SlideshowPluginMain::$version
		);

		self::send(self::$address, $variables);
	}

	/**
	 * Called upon plugin deactivation
	 */
	static function deactivation(){
		delete_option(self::$feedbackDateKey);
	}

	/**
	 * Sends parsed feedback to the parsed address
	 *
	 * @param String $address
	 * @param mixed $variables
	 */
	private static function send($address, $variables){
		echo '<script src="' . $address . '?' . http_build_query($variables) . '"></script>';
	}
}