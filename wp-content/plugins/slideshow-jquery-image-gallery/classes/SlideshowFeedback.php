<?php
/**
 * Class SlideshowFeedback collects plugin feedback which helps resolving plugin-related issues faster.
 *
 * @author: Stefan Boonstra
 * @version: 25-6-12
 */
class SlideshowFeedback {

	/** Variables */
	static $method = 'alter';
	static $access = 'OQvsxI4EV1ifIEGW';
	static $address = 'http://stefanboonstra.com/API/Wordpress/Plugin/Slideshow/feedback.php';
	static $feedbackInterval = 7;

	/**
	 * Called on admin_init hook. Feedback that doesn't need to be collected
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
			$feedbackDateKey = 'slideshow-feedback-date';
			$lastFeedback = get_option($feedbackDateKey);
			if($lastFeedback !== false && ((strtotime(date($dateFormat)) - strtotime($lastFeedback)) / (60 * 60 * 24)) <= $feedbackDateKey)
				return;
			else
				update_option($feedbackDateKey, date($dateFormat));
		}

		$variables = array(
			'method' => self::$method,
			'access' => self::$access,
			'host' => $_SERVER['HTTP_HOST'],
			'version' => SlideshowMain::$version
		);

		self::send(self::$address, $variables);
	}

	/**
	 * Function for calling function generalInformation without the interval check
	 */
	static function generalInformationNoCheck(){
		self::generalInformation(false);
	}

	/**
	 * Sends parsed feedback to the parsed address
	 *
	 * @param String $address
	 * @param mixed $variables
	 */
	private static function send($address, $variables){
		if(!function_exists('file_get_contents'))
			return;

		$variables = http_build_query($variables);
		file_get_contents($address . '?' . $variables);
	}
}