<?php
/**
 * One Post/Category Utility
 * 
 * Create a page in the tools section of the Administration Panels
 * that allows administrators to easily see which posts have been
 * assigned more than one category.
 * @author Michael Fields <michael@mfields.org>
 * @version 0.1
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package One Post Per Category
 */

/**
 * @global string $mfields_one_post_per_category_page_slug
 */
$mfields_one_post_per_category_page_slug = 'one-post-per-cat';

/**
 * @global string $mfields_one_post_per_category_page_url
 */
$mfields_one_post_per_category_page_url = admin_url() . 'tools.php?page=' . $mfields_one_post_per_category_page_slug;

if( !function_exists( 'mfields_one_post_per_category_admin_menu' ) ) {
	add_action( 'admin_menu', 'mfields_one_post_per_category_admin_menu' );
	/**
	* Register One Post/Category Utility with the administration menu.
	* @uses $mfields_one_post_per_category_page_slug
	* @uses add_submenu_page()
	* @return void
	*/
	function mfields_one_post_per_category_admin_menu() {
		global $mfields_one_post_per_category_page_slug;
		$mfields_one_post_per_category_page_hook = add_submenu_page( 'tools.php', '1 Post/Category', '1 Post/Category', 'level_10', $mfields_one_post_per_category_page_slug, 'mfields_one_post_per_category_page' );
	}
}
if( !function_exists( 'mfields_one_post_per_category_page' ) ) {
	/**
	* Print administration page for One Post/Category Utility.
	* @uses $wpdb
	* @uses get_results()
	* @uses admin_url()
	* @return void
	*/
	function mfields_one_post_per_category_page() {
		global $wpdb;
		$o = '';
		
		/* Query for all posts that have more than one category */
		$posts = $wpdb->get_results( "
			SELECT p.*, COUNT( p.`ID` ) AS 'category_count'
			FROM $wpdb->posts AS p, $wpdb->term_taxonomy as tt, $wpdb->term_relationships as tr
			WHERE tt.`taxonomy` = 'category'
			AND tt.`term_taxonomy_id` = tr.`term_taxonomy_id`
			AND tr.`object_id` = p.ID
			AND p.`post_type` = 'post'
			GROUP BY p.`ID`
			HAVING COUNT( p.`ID` ) > 1
			ORDER BY COUNT( p.`ID` ) DESC
			" );
		
		/* Define html table. */
		if( $posts ) {
			$o.= "\n\t" . '<table class="widefat post fixed" cellspacing="0">';
			$o.= "\n\t" . '<thead>';
			$o.= "\n\t" . '<tr>';
			$o.= "\n\t" . '<th scope="col" id="post_name" class="" >Post Title</th>';
			$o.= "\n\t" . '<th scope="col" id="categories" class="" >Categories</th>';
			$o.= "\n\t" . '</tr>';
			$o.= "\n\t" . '</thead>';
	 
			foreach( $posts as $post ) {			
				$href = admin_url() . 'post.php?post=' . $post->ID . '&amp;action=edit';
				$title = '<a href="' . $href . '">' . $post->post_title . '</a>';
				$o.= "\n\t\t" . '<tr>';
				$o.= "\n\t\t\t" . '<td>' . $title . '</td>';
				$o.= "\n\t\t\t" . '<td>' . $post->category_count . '</td>';
				$o.= "\n\t\t" . '</tr>';
			}
			$o.= "\n\t" . '</table>';
		}
		
		/* Output page to browser. */
		print "\n" . '<div class="wrap">';
		print "\n" . '<h2>1 Post/Category</h2>';
		if( count( $posts ) >= 1 ) {
			print "\n" . '<p>The table below shows all posts that have more than one category.</p>';
			print $o;
		}
		else {
			print "\n" . '<p>All of your posts are in exactly one category.</p>';
		}
		print "\n" . '</div>';
	}
}	
?>