<?php
/**
This file is part of the podcast.de WordPress plugin.

The podcast.de WordPress plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

The podcast.de WordPress plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with the podcast.de WordPress plugin.  If not, see http://www.gnu.org/licenses/.
*/

require_once(dirname( __FILE__ ) . '/PodcastDePluginStandards.php');
require_once(dirname( __FILE__ ) . '/PodcastDePluginStandardsInterface.php');
require_once(dirname( __FILE__ ) . '/lib/Plugin.php');
require_once(dirname( __FILE__ ) . '/lib/User.php');

class PodcastDeWordPressPlugin extends PodcastDePlugin implements PodcastDePluginStandardsInterface {

    /**
     * Singleton
     *
     * @return obj
     */
    public static function &singleton() {

        static $instance;

        if (!isset($instance)) {
            $class      = __CLASS__;
            $instance   = new $class;
        }
        return $instance;
    }

    /**
     * Constructor
     *
     */
    protected function __construct() {

        parent::__construct();
    }

    /**
     * This acts as controller
     *
     */
    public function process() {

        $oUser  = PodcastDeUser::singleton();

        try {
            switch ( $_REQUEST['_wpnonce'] ) {
                case wp_create_nonce(self::ACTION_USER_ADD) :
                    $oUser->process();
                    $this->showMessage(__('User added successfully', $this->getIdentifier()), self::HINT_SUCCESS, true);
                    break;
                case wp_create_nonce(self::ACTION_USER_DELETE) :
                    $oUser->process();
                    $this->showMessage(__('User deleted successfully', $this->getIdentifier()), self::HINT_SUCCESS, true);
                    break;
            }
        } catch (Exception $e) {
            $this->showMessage($e->getMessage(), self::HINT_ERROR, true);
        }
        $aUsers = $oUser->getAll();
        if (count($aUsers) > 0) {
            $oUser->show($aUsers, true);
        }
        #if (count($aUsers) < 5) {
            $oUser->showAddForm(true);
        #}
        $oUser->showDescription(count($aUsers), true);
    }
}

/**
 *
 * @package podcast.de WordPress Plugin
 * @author  Fabio Bacigalupo <wordpress-plugin@podcast.de>
 * @copyright Fabio Bacigalupo 2012
 * @version $Revision: 0.1 $
 * @since PHP 5.2
 */
class PodcastDeWordPressPluginException extends Exception {

    public function __construct($message, $code = 0) {

        parent::__construct($message, $code);
    }
}
?>