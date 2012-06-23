<?php
/*
Plugin Name: Elevate Parent Category Template
Description: Elevates parent category template to top of Wordpress Loop.
Version: 1.0.4
Author: Jeremy Foo
Author URI: http://thirdly.org/
Plugin URI: http://wordpress.ornyx.net

    Copyright 2008  Jeremy Foo  (email : jeremyfoo@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

function get_category_title($node) {
	global $wpdb;
	$test = $wpdb->get_var("SELECT name FROM $wpdb->terms WHERE term_id=$node");
	return $test;
}

function get_category_child() {
	global $wp_query;
	return $wp_query->query_vars['cat_child'];
}

function is_parent() {
	global $wp_query;
	if ((get_category_parent($wp_query->query_vars['cat']) == 0) && (empty($wp_query->query_vars['cat_child']))) {
		return true;
	} else {
		return false;
	}
}

function get_category_parent($node) {
	$path = get_category_path($node);

	if (empty($path)) {
		return 0;
	} else {
		return $path[0];
	}
}

function get_category_path($node) {
	global $wpdb;

	$parent = $wpdb->get_var("SELECT parent FROM $wpdb->term_taxonomy WHERE term_id=$node");
	$path = array();
	
	if ($parent != 0) {
		$path[] = $parent;
		
		$path = array_merge(get_category_path($parent), $path);
	} 
	
	return $path;

}

function epct_redirect() {
	global $wp_query, $wp_version;

	if (is_category()) {
		$childcat = $wp_query->query_vars['cat']; 
		$parent = get_category_parent($childcat);
		
		$category = get_category($childcat);
		
		if ($parent != 0) {
			$wp_query->query_vars['cat_child'] = $childcat;
			
			// fix from marty@halfempty to deal with custom template.
			if (!file_exists(STYLESHEETPATH . '/category-' . $category->slug . '.php')) {
				if (version_compare($wp_version, '3.1', '>=')) {
					//fix for WP 3.1
					$category = get_queried_object();
					$category->term_id = $parent;
					$category->slug = get_category($parent)->slug;
				} else {
					$wp_query->query_vars['cat'] = $parent; 
					
				}
				
			}
		}
	
	}
//	print_r($wp_query->get_queried_object());
//	print_r($wp_query->query_vars);
}


add_action('template_redirect', 'epct_redirect');

?>