<?php
/*
Plugin Name: Radio Button Categories
Plugin URI: http://wordpress.org/extend/plugins/category-radio-buttons/
Description: Change category interface for posts from checkboxes to radio buttons.
Version: 0.2
Author: Michael Fields
Author URI: http://mfields.org/
License: GPLv2

Copyright 2010  Michael Fields  michael@mfields.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/

/**
 * Radio Button Categories Plugin.
 * Use radio buttons instead of check boxes for the category meta box while editing single posts.
 * @author Michael Fields <michael@mfields.org>
 * @version 0.2
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @package Radio Button Categories Plugin
 */

 
/**
 * One Post Per Category Utility
 */
include_once( 'one-post-per-category.php' );

/**
 * @global string $mfields_radio_category_ajax_url
 */
$mfields_radio_category_ajax_url = admin_url() . 'admin-ajax.php?action=wp_ajax_mfields_add_radio_category';

/**
 * @global string $mfields_radio_category_ajax_action
 */
$mfields_radio_category_ajax_action = 'mfields_add_radio_category';

if( !function_exists( 'mfields_remove_category_meta_box' ) ) {
	add_action( 'admin_head', 'mfields_remove_category_meta_box', 10 );
	/**********************************************************
	* Remove original category metabox from edit post screen.
	* Add new category metabox with radio buttons
	**********************************************************/
	function mfields_remove_category_meta_box() {
		remove_meta_box( 'categorydiv', 'post', 'side' );
		add_meta_box(
			'radio_category',
			'Categories',
			'mfields_radio_categories',
			'post',
			'side',
			'core',
			array( 'taxonomy' => 'category' ));
	}
}
if( !function_exists( 'mfields_radio_category_script' ) ) {
	function mfields_radio_category_script(){
		global $mfields_radio_category_ajax_action;
		$action = $mfields_radio_category_ajax_action;
		$nonce = wp_create_nonce( $mfields_radio_category_ajax_action );
		print <<<EOF
		<script type='text/javascript'>
		/* <![CDATA[ */
		jQuery(document).ready( function($) {
			
			/* Toggle the "Add New Category" box */
			$( '#mfields-category-add-toggle' ).click( function() {
				$( '#mfields-category-add' ).slideToggle( 'fast' );
				return false;
				} );
			
			/* New category's parent. */
			newcatParent = $( "#mfields_newcat_parent" ).val();
			$( "#mfields_newcat_parent" ).change( function ( e ) {
				newcatParent = $( this ).val();
			} );
			
			/* Kill default value of new category input. */
			$( "#mfields-newcat" ).focus( function () {
				var val = $( this ).val();
				if( val === 'New category name' )
					$( this ).val('');
			} );
			
			/* Handle AJAX request */
			$( '#mfields-category-add-sumbit' ).click( function() {
				var newcat = $( '#mfields-newcat' ).val();
				$.post( ajaxurl, {
					action: "{$action}",
					_ajax_nonce: "{$nonce}",
					newcat: newcat,
					newcat_parent: newcatParent
					},
					function( data ) {
						$( '#radio_category .inside' ).html( data );
					}
				);
			} );
		} );
		
		/* ]]> */
		</script>
EOF;
	}
}
if( !function_exists( 'mfields_radio_category_style' ) ) {
	add_action( 'admin_head-post.php', 'mfields_radio_category_style' );
	function mfields_radio_category_style(){
		$o = <<<EOF
		<style>
		#radio_category div.tabs-panel,
		#post-body #radio_category div.tabs-panel {
			height: 200px;
			overflow: auto;
			/*padding: 0.5em 0.9em;*/
			border-style: solid;
			border-width: 1px;
			margin: 0 5px 0 0;
		}
		#radio_category ul.children {
			margin:0;
			padding:0;
			}
		#radio_category ul.children li {
			margin:0;
			padding: 0 0 0 1.5em;
		}
		
		#radio_category li label {
			display: block;
			padding: 0 0.9em;
			}
		</style>
EOF;
	print "\n" . '<!-- mfields-radio-categories -->' . "\n" . trim( preg_replace( '/\s+/', ' ', $o ) ) . "\n";
	}
}
if( !function_exists( 'mfields_radio_categories_ajax_handler' ) ) {
	add_action( 'wp_ajax_' . $mfields_radio_category_ajax_action, 'mfields_radio_categories_ajax_handler' );
	function mfields_radio_categories_ajax_handler() {
		global $mfields_radio_category_ajax_action;
		
		check_ajax_referer( $mfields_radio_category_ajax_action );
		
		if ( !current_user_can( 'manage_categories' ) )
			die('-1');
		
		$parent = ( isset( $_POST['newcat_parent'] ) && !empty( $_POST['newcat_parent'] ) ) ? (int) $_POST['newcat_parent'] : 0;
		
		if( isset( $_POST['newcat'] ) && !empty( $_POST['newcat'] ) ) {
			$cat_name = strip_tags( trim( $_POST['newcat'] ) );
			$category_nicename = sanitize_title( $name );
			$cat_id = wp_create_category( $cat_name, $parent );
			$category = get_category( $cat_id );
			$args = array( 'selected_cats' => array( $cat_id ) );
			mfields_radio_categories( 0, $args );
		}
		exit();
	}
}
if( !function_exists( 'mfields_radio_categories' ) ) {
	/**********************************************************
	* Forked Version of post_categories_meta_box()
	* Defined on line 287 of /wp-admin/includes/meta-boxes.php
	* of WordPress version 3.0 Beta 2
	**********************************************************/
	function mfields_radio_categories( $post_object = false, $args = false ) {
?>

<div id="categories-all" class="tabs-panel">
	<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">
<?php 
	
	if( is_object( $post_object ) )
		$post_id = $post_object->ID;
	else{
		global $post;
		$post_id = $post->ID;
	}
	
	mfields_terms_radiolist( $post_id, $args );
	
	?>
	</ul>
</div>

<?php if ( current_user_can('manage_categories') ) : ?>
<div id="category-adder" class="wp-hidden-children">
	<h4><a id="mfields-category-add-toggle" href="#mfields-category-add" class="hide-if-no-js" tabindex="3"><?php _e( '+ Add New Category' ); ?></a></h4>
	<p id="mfields-category-add" class="wp-hidden-child">
	<label class="screen-reader-text" for="mfields-newcat"><?php _e( 'Add New Category' ); ?></label><input type="text" name="mfields-newcat" id="mfields-newcat" class="form-required form-input-tip" value="<?php esc_attr_e( 'New category name' ); ?>" tabindex="3" aria-required="true"/>
	<label class="screen-reader-text" for="mfields_newcat_parent"><?php _e('Parent category'); ?>:</label><?php wp_dropdown_categories( array( 'hide_empty' => 0, 'name' => 'mfields_newcat_parent', 'orderby' => 'name', 'hierarchical' => 1, 'show_option_none' => __('Parent category') ) ); ?>
	
	<input type="button" id="mfields-category-add-sumbit" class="add:categorychecklist:category-add button" value="<?php esc_attr_e( 'Add' ); ?>" tabindex="3" />

<?php	wp_nonce_field( 'add-category', '_ajax_nonce', false ); ?>
	<span id="category-ajax-response"></span></p>
</div>
<?php
endif;

mfields_radio_category_script();

}
}
if( !function_exists( 'mfields_terms_radiolist' ) ) {
	/**********************************************************
	* Forked Version of wp_terms_checklist()
	* Defined on line 245 of /wp-admin/includes/template.php
	* of WordPress version 3.0 Beta 2
	**********************************************************/
	function mfields_terms_radiolist( $post_id = 0, $args = array() ) {

		$defaults = array(
			'descendants_and_self' => 0,
			'selected_cats' => false,
			'popular_cats' => false,
			'walker' => null,
			'taxonomy' => 'category',
			'checked_ontop' => true
		);
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		if ( empty( $walker ) || !is_a( $walker, 'Walker' ) )
			$walker = new mfields_Walker_Category_Radiolist;

		$descendants_and_self = (int) $descendants_and_self;

		$args = array( 'taxonomy' => $taxonomy );

		$tax = get_taxonomy($taxonomy);
		$args['disabled'] = !current_user_can($tax->cap->assign_terms);
				
		if ( is_array( $selected_cats ) )
			$args['selected_cats'] = $selected_cats;
		elseif ( $post_id )
			$args['selected_cats'] = wp_get_object_terms($post_id, $taxonomy, array_merge($args, array('fields' => 'ids')));
		else
			$args['selected_cats'] = array( 1 );
		
		if ( $descendants_and_self ) {
			$categories = (array) get_terms($taxonomy, array( 'child_of' => $descendants_and_self, 'hierarchical' => 0, 'hide_empty' => 0 ) );
			$self = get_term( $descendants_and_self, $taxonomy );
			array_unshift( $categories, $self );
		} else {
			$categories = (array) get_terms($taxonomy, array('get' => 'all'));
		}

		if ( $checked_ontop ) {
			// Post process $categories rather than adding an exclude to the get_terms() query to keep the query the same across all posts (for any query cache)
			$checked_categories = array();
			$keys = array_keys( $categories );

			foreach( $keys as $k ) {
				if ( in_array( $categories[$k]->term_id, $args['selected_cats'] ) ) {
					$checked_categories[] = $categories[$k];
					unset( $categories[$k] );
				}
			}

			// Put checked cats on top
			echo call_user_func_array( array( &$walker, 'walk' ), array( $checked_categories, 0, $args ) );
		}
		// Then the rest of them
		echo call_user_func_array(array(&$walker, 'walk'), array($categories, 0, $args));
	}
}

/**
 * Radio Button Category Walker.
 * Forked Version of Walker_Category_Checklist class
 * Originally defined on line 184 of /wp-admin/includes/template.php in WordPress version 3.0 Beta 2
 * @package Radio Button Categories Plugin
 */
class mfields_Walker_Category_Radiolist extends Walker {
	var $tree_type = 'category';
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id'); //TODO: decouple this

	function start_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent<ul class='children'>\n";
	}

	function end_lvl(&$output, $depth, $args) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	function start_el(&$output, $category, $depth, $args) {
		extract($args);
		if ( empty($taxonomy) )
			$taxonomy = 'category';

		if ( $taxonomy == 'category' )
			$name = 'post_category';
		else
			$name = 'tax_input['.$taxonomy.']';

		$output .= "\n<li id='{$taxonomy}-{$category->term_id}'$class>" . '<label class="selectit"><input value="' . $category->term_id . '" type="radio" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' . checked( in_array( $category->term_id, $selected_cats ), true, false ) . ' /> ' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
	}

	function end_el(&$output, $category, $depth, $args) {
		$output .= "</li>\n";
	}
}




?>