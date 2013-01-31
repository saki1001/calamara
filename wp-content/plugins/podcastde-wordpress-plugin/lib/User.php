<?php
require_once(dirname( __FILE__ ) . '/Plugin.php');

class PodcastDeUser extends PodcastDePlugin {

    CONST TYPE_ID           = 'user';

    CONST FIELD_TYPE_USER   = 'user';

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
     * This acts as controller
     *
     */
    public function process() {

        $user = trim(strip_tags(stripslashes($_REQUEST['user'])));
        if (!$user) {
            throw new PodcastDeUserInsertException(__('Mandatory parameter is missing', $this->getIdentifier()));
        }

        switch ( $_REQUEST['_wpnonce'] ) {
            case wp_create_nonce(self::ACTION_USER_ADD) :
                $this->add($_REQUEST['user']);
                break;
            case wp_create_nonce(self::ACTION_USER_DELETE) :
                $this->delete($_REQUEST['user']);
                break;
            default :
                throw new PodcastDeUserException(__('Action not found', $this->getIdentifier()));
        }
    }

    /**
     *
     *
     * @param string $user
     */
    private function add($user) {

        $aUser = (array)$this->getOption(self::TYPE_ID);
        // Check if user exists
        $feedUrl    = "http://www.podcast.de/rss/podcasts/abonnierte/benutzer/$user.xml";
        $rss        = fetch_feed($feedUrl);
        if ( is_wp_error( $rss ) || $rss->feed_url != $feedUrl) {
            throw new PodcastDeUserInsertException(__('Could not add user', $this->getIdentifier()));
        }
        $aUser[strtolower($user)] = array('username' => $user);
        $this->setOption(self::TYPE_ID, $aUser);
    }

    /**
     *
     *
     * @param string $user
     */
    private function delete($user) {

        $aUser = (array)$this->getOption(self::TYPE_ID);
        unset($aUser[strtolower($user)]);

        $this->setOption(self::TYPE_ID, $aUser);
    }

    /**
     *
     *
     * @return array
     */
    public function getAll() {

        $aUser = (array)$this->getOption(self::TYPE_ID);

        ksort($aUser);

        return $aUser;
    }

    /**
     *
     *
     * @return array
     */
    public function getFormattedUsername($user) {

        $aUser = (array)$this->getOption(self::TYPE_ID);

        return $aUser[$user]['username'];
    }

    /**
     *
     *
     * @return bool
     */
    public function hasUsers() {

        return count((array)$this->getOption(self::TYPE_ID)) > 0;
    }

    /**
     *
     *
     * @param array $aUsers
     * @param bool $display
     * @return string | void
     */
    public function show(array $aUsers, $display = false) {

        $str    = '
            <h4>%s</h4>
            <form action="%s" method="post">
                <select name="%s">%s</select> <input type="submit" value="%s" />
            </form>
            <div class="clear"></div>
        ';

        $_str   = '';
        foreach ($aUsers as $user => $aUser) {
            $_str .= '<option value="' . $user . '">' . $aUser['username'] . '</option>';
        }

        $args   = array(
            __('Users', self::getIdentifier()),
            attribute_escape(add_query_arg('_wpnonce', wp_create_nonce(self::ACTION_USER_DELETE))),
            self::FIELD_TYPE_USER,
            $_str,
            __('Delete user', self::getIdentifier()),
        );

        if ($display) {
            vprintf($str, $args);
        } else {
            return vsprintf($str, $args);
        }
    }

    /**
     *
     *
     * @return string
     */
    public function showAddForm($display = false) {

        $str = '
            <form action="%s" method="post">
                <input type="text" name="%s" value="" />
                <input type="submit" value="%s" />
            </form>
            <div class="clear"></div>
        ';

        $args   = array(
            attribute_escape(add_query_arg('_wpnonce', wp_create_nonce(self::ACTION_USER_ADD))),
            self::FIELD_TYPE_USER,
            __('Add user', self::getIdentifier())
        );

        if ($display) {
            vprintf($str, $args);
        } else {
            return vsprintf($str, $args);
        }
    }

    /**
     *
     *
     * @param int $cUser User count
     * @param bool $display
     * @return string
     */
    public function showDescription($cUser, $display = false) {

        $str = '
            <p>%s</p>
            <div class="clear"></div>
        ';

        $args   = array(
            (($cUser < 1) ? __('Add an user to enable widgets', self::getIdentifier()) : sprintf(__('Add a widget of type %s', self::getIdentifier()), admin_url('widgets.php'), __('Subscriptions', self::getIdentifier())))
        );

        if ($display) {
            vprintf($str, $args);
        } else {
            return vsprintf($str, $args);
        }
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
class PodcastDeUserException extends PodcastDeWordPressPluginException {

    public function __construct($message, $code = 0) {

        parent::__construct($message, $code);
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
class PodcastDeUserInsertException extends PodcastDeUserException {

    public function __construct($message, $code = 0) {

        parent::__construct($message, $code);
    }
}
?>