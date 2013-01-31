<?php
/**
Plugin Name: podcast.de WordPress Plugin
Plugin URI: http://blog.podcast.de/wordpress-plugin
Description: Show a list of podcasts as a widget in your blog´s sidebar. Our podcast widget is highly customizable and works automated with subscriptions made by users on www.podcast.de where you can add any podcast from the directory.
Version: 0.1
Author: podcast.de <wordpress-plugin@podcast.de>
Author URI: http://www.podcast.de
Text Domain: podcastde
License: GPLv2 or later

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

if (!class_exists("PodcastDeWordPressPlugin")) {

    define('PODCASTDE_PLUGIN_URL', plugin_dir_url( __FILE__ ));

    require_once( 'PodcastDeWordPressPlugin.php' );

    // Load WP-Config File If This File Is Called Directly
    if( !function_exists('add_action') ) {

        $wp_root = '../../..';

        if( file_exists($wp_root.'/wp-load.php') ) {
    		require_once($wp_root.'/wp-load.php');
    	} else {
    		require_once($wp_root.'/wp-config.php');
    	}
    }

    if ( class_exists('PodcastDeWordPressPlugin') ) {

        // Initialize constructor which loads scripts (CSS, JS)
        PodcastDeWordPressPlugin::singleton();
        include_once dirname( __FILE__ ) . '/widget.php';

    	if ( version_compare( get_bloginfo( 'version' ), '3.0', '<=' ) ) {
            die(__("Installed version of WordPress is too old", PodcastDeWordPressPlugin::singleton()->getIdentifier()));
    	}
    }

    /**
     * Load up everything needed to display the page
     */
    function podcastde_init() {

        $plugin = PodcastDeWordPressPlugin::singleton();

        if (version_compare(PHP_VERSION, '5.2.1', '<')) {
            die(__("Installed version of PHP is too old", $plugin->getIdentifier()));
        }

        $plugin->loadScripts();
        $plugin->getHeader(true);
        $plugin->process();
        $plugin->getFooter(true);
    }

    // Function: Plugin administration menu
    add_action( 'admin_menu', 'podcastde_menu' );
    function podcastde_menu() {

        $plugin = PodcastDeWordPressPlugin::singleton();

    	if( function_exists('add_menu_page') ) {
    		add_menu_page(__('podcast.de', $plugin->getIdentifier()), __('podcast.de', $plugin->getIdentifier()), 'manage_options', $plugin->getName() . '/podcastde.php', 'podcastde_init', plugins_url($plugin->getName() . '/images/podcastde_icon.png'));
    	}

    	if( function_exists('add_submenu_page') ) {
    	    add_submenu_page($plugin->getName() . '/podcastde.php', __('podcast.de', $plugin->getIdentifier()), __('Manage budgets', $plugin->getIdentifier()), 'manage_options', $plugin->getName() . '/podcastde.php', 'podcastde_init');
    	    foreach ( $plugin->getServices() as $service ) {
    	        $service       = ucfirst(strtolower($service));
    	        $capability    = $plugin->getCapabilityPrefix() . $service;
        	    $parent        = $plugin->getName() . '/podcastde.php';
        	    $page_title    = __('Submenu: ' . $service, $plugin->getIdentifier());
        	    $menu_title    = $page_title;
        	    $file          = $plugin->getName() . '/lib/PodcastDe' . $service . '.php';

        	    if ($_REQUEST['page'] == $file) {
        	    	$init = 'podcastde' . $service . '_init';
        	    }

        		add_submenu_page($parent, $page_title, $menu_title, $capability, $file, $init);
    	    }
    	}
    }
    function podcastde_plugin_action_links( $links, $file ) {
    	if ( $file == 'podcastde-wordpress-plugin/podcastde.php' ) {
    		$links[] = '<a href="admin.php?page=podcastde-wordpress-plugin/podcastde.php">'.__('Settings').'</a>';
    	}

    	return $links;
    }

    add_filter( 'plugin_action_links', 'podcastde_plugin_action_links', 10, 2 );
}
?>