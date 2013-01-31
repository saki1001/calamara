<?php
/**
 *
 * @package podcast.de WordPress plugin
 * @author  Fabio Bacigalupo <wordpress-plugin@podcast.de>
 * @copyright Fabio Bacigalupo 2012
 * @version $Revision: 0.1 $
 * @since PHP5.2.12
 */
class PodcastDePlugin extends PodcastDePluginStandards {

    /**
     * Name of plugin
     *
     */
    const PLUGIN_NAME               = 'podcastde-wordpress-plugin';

    /**
     * Simple version number
     * Raise this if e.g. stylesheet has changed
     *
     * @var float
     * @see loadScripts()
     */
    const PLUGIN_VERSION            = 0.1;

    /**
     * Internally used name
     *
     */
    const IDENTIFIER                = 'podcastde';

    const ACTION_USER_ADD           = '1001';
    const ACTION_USER_DELETE        = '1002';

    /**
     * List of available services
     *
     * @var array
     */
    protected static $aServices     = array(
    );

    /**
     * Constructor
     *
     */
    protected function __construct() {

        parent::__construct();
    }

    /**
     * Load templates and JavaScript files
     *
     */
    public function loadScripts() {

        $stylesheet = $this->getPluginPath() . '/css/' . self::getIdentifier() . '-admin.css';
		wp_enqueue_style(self::getIdentifier() . '-admin', $stylesheet, false, self::getVersion(), 'all');
		parent::loadScripts();
    }

    /**
     *
     *
     * @return array
     */
    public function getServices() {

        return self::$aServices;
    }

    /**
     *
     *
     * @return string
     */
    public function getIdentifier() {

        return self::IDENTIFIER;
    }

    /**
     *
     *
     * @return string
     */
    public function getName() {

        return self::PLUGIN_NAME;
    }

    /**
     *
     *
     * @return float
     */
    public function getVersion() {

        return self::PLUGIN_VERSION;
    }

    /**
     *
     *
     * @return string
     */
    public function getCapabilityPrefix() {

        return self::PREFIX_CAPABILITY;
    }

    /**
     * Set the name the option list is saved as in WordPress internally
     */
    function setOptionsName() {

        $this->optionsName .= self::IDENTIFIER;
    }

/******************************************************************************************************************************************************************************
 * HTML forms
 ******************************************************************************************************************************************************************************/

    public function getHeader($display = false) {

        parent::getHeader(plugins_url(self::getName() . '/images/podcast_logo.gif'), $display);
    }
}
?>