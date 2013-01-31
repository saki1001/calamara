<?php
require_once(dirname( __FILE__ ) . '/lib/User.php');

/**
 * podcast.de subscriptions widget class
 *
 * @since 2.8.0
 */
class WP_Widget_PodcastDeSubscriptions extends WP_Widget {

    private $_oPlugin;

	function __construct() {
	    $this->_oPlugin = PodcastDeWordPressPlugin::singleton();
        load_plugin_textdomain($this->_oPlugin->getIdentifier(), false, $this->_oPlugin->getName() . '/languages');
		$widget_ops = array('classname' => 'widget_podcastdesubscriptions', 'description' => __('Subscriptions widget', $this->_oPlugin->getIdentifier()));
		parent::__construct('podcastdesubscriptions', __('Subscriptions', $this->_oPlugin->getIdentifier()), $widget_ops);

		if ( is_active_widget(false, false, $this->id_base) )
			add_action( 'wp_head', array(&$this, 'podcastdesubscriptions_style') );
	}

    function podcastdesubscriptions_style() {
		if ( ! current_theme_supports( 'widgets' ) // Temp hack #14876
			|| ! apply_filters( 'show_recent_comments_widget_style', true, $this->id_base ) )
			return;
    	?>
    <style type="text/css">
    .widget_podcastdesubscriptions ul{list-style:none;}
    </style>
    	<?php
    }

	function widget( $args, $instance ) {
		extract($args);

        $icon       = includes_url('images/rss.png');
        $rss        = fetch_feed("http://www.podcast.de/rss/podcasts/abonnierte/benutzer/" . $instance['user'] . ".xml");
        // Figure out how many total items there are, but limit it if user wishes so
        $maxItems   = $rss->get_item_quantity($instance['maxitems']);
        // Build an array of all the items, starting with element 0 (first element).
        $oItems     = $rss->get_items(0, $maxItems);
        $content    = '<ul>';
        foreach ( $oItems as $item ) {
            $content        .= '<li>';
            if ($instance['includerss']) {
                $content    .= '<a href="' . esc_url( $item->get_link() ) . '"><img src="' . $icon . '" alt="RSS" title="' . esc_html( $item->get_title() ) . ' abonnieren" style="border: 0pt none;" /> ';
            }
            $content        .= '<a href="' . esc_url( $item->get_id() ) . '">' . esc_html( $item->get_title() ) . '</a>';
            $content        .= '</li>';
        }
        $content    .= '</ul>';
        if ($instance['includelink']) {
            if ( empty($instance['moretitle']) ) {
                $instance['moretitle'] = sprintf( __('%s´s subscriptions'), $instance['user'] );
            }
            if ( empty($instance['morelink']) ) {
                $instance['morelink'] = 'http://www.podcast.de/benutzer/' . $instance['user'] . '/';
            }
            $content        .= '<p><a href="' . $instance['morelink'] . '" target="_blank">' . $instance['moretitle'] . '</a></p>';
        }
        vprintf(
            '%s %s %s %s %s',
            array(
                $before_widget,
                $before_title,
                $instance['title'],
                $after_title,
                $content,
                $after_widget,
            )
        );
	}

	function update( array $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['user'] = strip_tags($new_instance['user']);
		$instance['maxitems'] = strip_tags($new_instance['maxitems']);
		$instance['includerss'] = strip_tags($new_instance['includerss']);
		$instance['includelink'] = strip_tags($new_instance['includelink']);
		$instance['moretitle'] = strip_tags($new_instance['moretitle']);
		$instance['morelink'] = strip_tags($new_instance['morelink']);

		if ($instance['includelink'] == 1) {
    		if ( empty($instance['moretitle']) ) $instance['moretitle'] = sprintf( __('%s´s subscriptions', $this->_oPlugin->getIdentifier()), PodcastDeUser::singleton()->getFormattedUsername($instance['user']) );
            if ( empty($instance['morelink']) ) $instance['morelink'] = 'http://www.podcast.de/benutzer/' . $instance['user'] . '/';
		}

		return $instance;
	}

	function form( $instance ) {
        $instance       = wp_parse_args( (array) $instance, array( 'title' => __('My subscriptions', $this->_oPlugin->getIdentifier()), 'maxitems' => 5,  'includerss' => 1, 'includelink' => 1 ) );
        $title          = htmlspecialchars($instance['title'], ENT_QUOTES);
        $aUsers         = '';
        $selected       = null;
        foreach (PodcastDeUser::singleton()->getAll() as $user => $aUser) {
            $selected   = ($user == $instance['user'] ? ' selected="selected"' : null);
            $aUsers     .= '<option value="' . $user . '"' . $selected . '>' . $aUser["username"] . '</option>';
        }

        $rss        = fetch_feed("http://www.podcast.de/rss/podcasts/abonnierte/benutzer/" . $instance['user'] . ".xml");
        $maxItems   = $rss->get_item_quantity();
        $aCounter   = '';
        foreach (range(1, $maxItems, 1) as $counter) {
            $selected   = ($counter == $instance['maxitems'] ? ' selected="selected"' : null);
            $aCounter   .= '<option value="' . $counter . '"' . $selected . '>' . $counter . '</option>';
        }

		vprintf('
            <p><label for="%s">%s <input id="%s" name="%s" type="text" value="%s" /></label></p>
            <p><label for="%s">%s <select id="%s" name="%s">%s</select></label></p>
            <p><label for="%s">%s <select id="%s" name="%s">%s</select></label></p>
            <p><label for="%s"><input type="checkbox" id="%s" name="%s" value="1" %s> %s</label></p>
            <p><label for="%s"><input type="checkbox" id="%s" name="%s" value="1" %s> %s</label></p>
            <div id="podcastdesubscriptionswidget-showmore">
                <p><label for="%s">%s <input type="text" id="%s" name="%s" value="%s"></label></p>
                <p><label for="%s">%s <input type="text" id="%s" name="%s" value="%s"></label></p>
            </div>
            ',
            array(
                $this->get_field_id('title'),
                __('Title:'),
                $this->get_field_id('title'),
                $this->get_field_name('title'),
                $title,
                $this->get_field_id('user'),
                __('User', $this->_oPlugin->getIdentifier()),
                $this->get_field_id('user'),
                $this->get_field_name('user'),
                $aUsers,
                $this->get_field_id('maxitems'),
                __('Max items', $this->_oPlugin->getIdentifier()),
                $this->get_field_id('maxitems'),
                $this->get_field_name('maxitems'),
                $aCounter,
                $this->get_field_id('includerss'),
                $this->get_field_id('includerss'),
                $this->get_field_name('includerss'),
                $instance['includerss'] == 1 ? ' checked="checked"' : null,
                __('Include rss', $this->_oPlugin->getIdentifier()),
                $this->get_field_id('includelink'),
                $this->get_field_id('includelink'),
                $this->get_field_name('includelink'),
                $instance['includelink'] == 1 ? ' checked="checked"' : null,
                __('Include link', $this->_oPlugin->getIdentifier()),
                $this->get_field_id('moretitle'),
                __('Link title', $this->_oPlugin->getIdentifier()),
                $this->get_field_id('moretitle'),
                $this->get_field_name('moretitle'),
                $instance['moretitle'],
                $this->get_field_id('morelink'),
                __('Link url', $this->_oPlugin->getIdentifier()),
                $this->get_field_id('morelink'),
                $this->get_field_name('morelink'),
                $instance['morelink'],
            )
		);
	}
}

if(PodcastDeUser::singleton()->hasUsers()) {

    function podcastde_widgets_init() {
    	if ( !is_blog_installed() )
    		return;

    	register_widget('WP_Widget_PodcastDeSubscriptions');
    }

    add_action('init', 'podcastde_widgets_init', 1);
}


/**
        <script language="javascript">
        var pod_includelink = $("#podcastdesubscriptionswidget-includelink");
        if(pod_includelink.attr("checked") != "undefined" && pod_includelink.attr("checked") == "checked") {
            $("#podcastdesubscriptionswidget-showmore").show();
        }
        </script>
 */