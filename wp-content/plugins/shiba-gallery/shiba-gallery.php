<?php
/*
Plugin Name: Shiba Gallery
Plugin URI: http://shibashake.com/wordpress-theme/super-wordpress-gallery-plugin
Description: Allows you to display your WordPress galleries using NoobSlide, SlimBox, TINY SlideShow, or the WordPress native gallery. Display multiple galleries and mix and match any way you want using the gallery shortcode.
Version: 3.7
Author: ShibaShake
Author URI: http://shibashake.com
*/


/*  Copyright 2009  Shiba Gallery  

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

	MooTools and NoobSlide are distributed under the MIT License.
	
*/


// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

define( 'SHIBA_GALLERY_DIR', WP_PLUGIN_DIR . '/shiba-gallery' );
define( 'SHIBA_GALLERY_URL', WP_PLUGIN_URL . '/shiba-gallery' );

// Load galleries

if (!class_exists("Shiba_Gallery")) :

class Shiba_Gallery {
	var $found = array();
	var $thumb_types = array('native', 'lytebox', 'slimbox', 'navlist');
	
	var $image_default, $empty_image, $default_gallery;
	var $PANEL_W = 480; // Size of noobslide panels
	var $PANEL_H = 240;
	var $TRANSITION = 'Fx.Transitions.Quint.easeOut';
	
	var $option_page;
	var $general, $helper;
	var $slimbox, $lytebox, $noobslide, $tinyss, $pslides, $popeye, $native, $navlist, $galleria;
	var $popeye_option, $tiny_option, $galleria_option;
	var $options;
	var $jsStr = "";
	var $ppy_type = array();
	var $nsNum = 1;
	var $tsNum = 0;

	function Shiba_Gallery() {	

		require(SHIBA_GALLERY_DIR."/shiba-gallery-general.php");
		if (class_exists("Shiba_Gallery_General")) 
			$this->general = new Shiba_Gallery_General();	
		require(SHIBA_GALLERY_DIR."/shiba-gallery-helper.php");
		if (class_exists("Shiba_Gallery_Helper")) 
			$this->helper = new Shiba_Gallery_Helper();	
	
		add_action('init', array(&$this,'init') );
//		add_action('admin_init', array(&$this,'init_admin') );
		add_action('admin_menu', array(&$this,'add_pages') );

		register_activation_hook( __FILE__, array(&$this,'activate') );
		register_deactivation_hook( __FILE__, array(&$this,'deactivate') );

		$this->empty_image = SHIBA_GALLERY_URL.'/images/empty.jpg';

		// assign default gallery options
		$options = get_option('shiba_gallery_options');
		if (!is_array($options)) $options = array();
		if (!isset($options['default_gallery'])) $options['default_gallery'] = 'noobslide_thumb';
		if (!isset($options['default_frame'])) $options['default_frame'] = 'frame7';
		if (!isset($options['default_caption'])) $options['default_caption'] = 'title';
		if (!isset($options['default_link'])) $options['default_link'] = 'attachment';
		
		if (!isset($options['default_image'])) $options['default_image'] = 0;
		$this->options = $options;
	}

	function activate() {
	}

	function deactivate() {
	}

	function init_admin() {
	}


		
	function init() {
		if (is_admin()) return;

		wp_enqueue_style('shiba-gallery', SHIBA_GALLERY_URL.'/shiba-gallery.css', array(), '3.6');	
		wp_enqueue_style('shiba-gallery-frames', SHIBA_GALLERY_URL.'/shiba-gallery-frames.css', array(), '3.6');	

		wp_enqueue_script('jquery');
	
		// Fix Chrome bug
		add_action( 'wp_head', array(&$this, 'fix_chrome_bug'), 1);
		add_action('wp_print_footer_scripts', array(&$this,'shiba_add_scripts'), 1);
		add_action('wp_footer', array(&$this,'shiba_gallery_footer'), 51);
		add_action('wp_head', array(&$this,'shiba_gallery_header'), 51);
	
		add_filter('post_gallery', array(&$this,'parse_gallery_shortcode'), 10, 2);			
		add_filter('img_caption_shortcode', array(&$this, 'gallery_caption_shortcode'), 10, 3);
	}

	function fix_chrome_bug() {
		?>		
		<script type="text/javascript">delete Function.prototype.bind;</script>
		<?php
	}


	function add_pages() {
	
		// Add a new submenu
		$this->option_page = add_media_page(	__('Shiba Gallery', 'shiba_gallery'), __('Shiba Gallery', 'shiba_gallery'), 
											'administrator', 'shiba_gallery', 
											array(&$this,'add_gallery_options_page') );
		add_action("admin_print_scripts-{$this->option_page}", array(&$this,'add_gallery_option_scripts'));
		add_action("admin_print_styles-{$this->option_page}", array(&$this,'add_gallery_option_styles'));
	
		if (isset($_GET['post_id']) && $_GET['post_id'] == -1371) {
			// Add use as featured image link - NOTE action has to have order > 10 (e.g. 20) because in async-upload.php there is a filter added that does just the Delete link. So we want to override that.
			add_filter('attachment_fields_to_edit', array(&$this, 'add_featured_link'), 20, 2);
			add_filter('media_send_to_editor', array(&$this,'gallery_image_selected'), 10, 3);
			add_filter('media_upload_tabs', array(&$this,'gallery_image_tabs'), 10, 1);
		}
	}

	function add_gallery_options_page() {
		include('shiba-gallery-options.php');	
	}
	function add_gallery_option_styles() {
		wp_enqueue_style('thickbox');
	}
 
	function add_gallery_option_scripts() {
		wp_enqueue_script('thickbox');
	}

	function gallery_image_tabs($_default_tabs) {
		unset($_default_tabs['type_url']);
		unset($_default_tabs['gallery']);
		
		return($_default_tabs);	
	}


	function add_featured_link($form_fields, $post) {
		$send = "<input type='submit' class='button' name='send[$post->ID]' value='" . esc_attr__( 'Use as Default' ) . "' />";

		$form_fields['buttons'] = array('tr' => "\t\t<tr class='submit'><td></td><td class='savesend'>$send</td></tr>\n");
		return $form_fields;
	}

	function gallery_image_selected($html, $send_id, $attachment) {
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
		var win = window.dialogArguments || opener || parent || top;
				
		//		win.tb_remove();
		win.jQuery( '#default_image' ).val('<?php echo $send_id;?>');
		// submit the form
		win.jQuery( '#shiba-gallery_options' ).submit();
		/* ]]> */
		</script>
		<?php
		exit();
	}
		
	function shiba_gallery_header() {
		?><style>
		.wp-caption p.wp-caption-text { margin: 10px 0 10px 0 !important; }
		<?php
		
		if (isset($this->options['image_frame'])) { ?>
			.post .wp-caption { padding:0; background:transparent; border:none; }
		<?php }
		
		
		$current_theme = get_current_theme();
		switch ($current_theme) {
		case 'Twenty Ten': ?>
			#content .gallery img { border:none; }
			#content .shiba-caption h3,#content .shiba-caption p, #content .shiba-caption h4, #content .gallery-caption p, #content .wp-caption img { margin: 0; color:inherit;}
			#content .noobpanel h3 { clear:none; margin:0; }
			.ts_information h3 { font-weight: bold; }
		<?php break;
		case 'Thematic':?>
			#content .noobpanel h3 { clear:none; margin:0; }
		<?php break;		
		}
		?>  
		</style>
      
		<script type="text/javascript">
		<!--//<![CDATA[
		//]]>-->
		</script>
		<?php
	
	}

	function shiba_add_scripts() {
		global $wp_scripts;
		if(is_admin()) return;
		
		if (isset($this->found['noobslide'])) :
//			wp_enqueue_script('mootools', SHIBA_GALLERY_URL.'/noobslide/mootools-1.2.4-core.js', array(), '1.2.4', true);
			wp_enqueue_script('mootools', SHIBA_GALLERY_URL.'/noobslide/mootools-core-1.4.2-full-compat-yc.js', array(), '1.4.2', true);
			wp_enqueue_script('noobslide', SHIBA_GALLERY_URL.'/noobslide/_class.noobSlide.packed.js', array('mootools'), '1.0', true);
			// Have to manually add to in_footer
			// Check if mootools is done, if not, then add to footer
			if (!in_array('mootools', $wp_scripts->done) && !in_array('mootools', $wp_scripts->in_footer)) {
				$wp_scripts->in_footer[] = 'mootools';
//				$wp_scripts->done[] = 'mootools'; // Can't mark done or else it won't get added
			}	
			$wp_scripts->in_footer[] = 'noobslide';
		endif;	


		if (isset($this->found['slimbox'])) :
			wp_enqueue_script('slimbox', SHIBA_GALLERY_URL.'/slimbox/js/slimbox2.js', array(), '1.0', true);
			$wp_scripts->in_footer[] = 'slimbox';
		endif;

		if (isset($this->found['lytebox'])) :
			wp_enqueue_script('lytebox', SHIBA_GALLERY_URL.'/lytebox/lytebox.js', array(), '1.0', true);
			$wp_scripts->in_footer[] = 'lytebox';
		endif;

		if (isset($this->found['popeye'])) :
			wp_enqueue_script('popeye', SHIBA_GALLERY_URL.'/popeye/lib/popeye/jquery.popeye-2.0.4.min.js', array(), '2.0.4', true);
			$wp_scripts->in_footer[] = 'popeye';
		endif;
		
		if (isset($this->found['pslides'])) :
			wp_enqueue_script('pslides', SHIBA_GALLERY_URL.'/pslides/js/PictureSlides-jquery-2.0.js', array(), '1.0', true);
			$wp_scripts->in_footer[] = 'pslides';
		endif;
		
		
		if (isset($this->found['tiny'])) :
			wp_enqueue_script('tiny', SHIBA_GALLERY_URL.'/tinyss/script2.js', array(), '1.0', true);
			$wp_scripts->in_footer[] = 'tiny';
    	endif;

		if (isset($this->found['galleria'])) :
			wp_enqueue_script('galleria', SHIBA_GALLERY_URL.'/galleria/galleria-1.2.7.min.js', array(), '1.0', true);
			$wp_scripts->in_footer[] = 'galleria';
		endif;

	}

	function load_gallery($type) {
		switch($type) {
		case 'slimbox':
			if (!is_object($this->slimbox)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-slimbox.php");		
				if (class_exists("Shiba_Gallery_SlimBox")) 
					$this->slimbox = new Shiba_Gallery_SlimBox();	
			}
			break;
		case 'lytebox':
			if (!is_object($this->lytebox)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-lytebox.php");		
				if (class_exists("Shiba_Gallery_LyteBox")) 
					$this->lytebox = new Shiba_Gallery_LyteBox();	
			}
			break;			
		case 'popeye':
			if (!is_object($this->popeye)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-popeye.php");
				if (class_exists("Shiba_Gallery_Popeye")) 
					$this->popeye = new Shiba_Gallery_Popeye();	
			}
			break;
		case 'pslides':
			if (!is_object($this->pslides)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-pslides.php");
				if (class_exists("Shiba_Gallery_PSlides")) 
					$this->pslides = new Shiba_Gallery_PSlides();	
			}
			break;
		case 'tiny':
		case 'smoothgallery':
			if (!is_object($this->tinyss)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-tinyss.php");
				if (class_exists("Shiba_Gallery_TinySS")) 
					$this->tinyss = new Shiba_Gallery_TinySS();	
			}
			break;
		case 'native':
			if (!is_object($this->native)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-native.php");
				if (class_exists("Shiba_Gallery_Native")) 
					$this->native = new Shiba_Gallery_Native();	
			}
		break;
		case 'navlist':
			if (!is_object($this->navlist)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-navlist.php");		
				if (class_exists("Shiba_Gallery_NavList")) 
					$this->navlist = new Shiba_Gallery_NavList();	
			}
			break;
		case 'galleria':
			if (!is_object($this->galleria)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-galleria.php");		
				if (class_exists("Shiba_Gallery_Galleria")) 
					$this->galleria = new Shiba_Gallery_Galleria();	
			}
			break;
		case 'noobslide':
		case 'slideviewer':
		case 'nativex':
				if (!is_object($this->noobslide)) {
				require_once(SHIBA_GALLERY_DIR."/shiba-noobslide.php");
				if (class_exists("Shiba_Gallery_NoobSlide")) 
					$this->noobslide = new Shiba_Gallery_NoobSlide();	
			}
			break;
		}	
	}
	
	
	function shiba_gallery_footer() {
		global $shiba_gallery; // On some installations $shiba_gallery is not equal to $this. Don't know why
		
		if(is_admin()) return;

		?>
		<script type="text/javascript">
		<!--//<![CDATA[

		// Render javascript as necessary
		// Deal with IE7 peculiarities
		if (navigator.userAgent.indexOf("MSIE 7") != -1) {
			jQuery('.shiba-caption').css('padding-bottom', '0px');
			jQuery('.noobslide_info_overlay').css({'zoom':'1','left':'0px'});
		}
		
		<?php		
		if ($shiba_gallery->jsStr) echo $shiba_gallery->jsStr; 
			
		if (isset($this->found['galleria'])) : 
			$galleria_theme = SHIBA_GALLERY_URL."/galleria/themes/classic/galleria.classic.min.js";
			$galleria_theme = apply_filters('galleria_theme', $galleria_theme);
			echo "Galleria.loadTheme('{$galleria_theme}');\n";
			foreach ( $this->galleria_option as $id => $option ) {
				// Write out the options array
				if (!empty($option)) {
					echo $this->general->write_array("galleriaOptions{$id}", $option);
					echo "Galleria.configure(galleriaOptions{$id});";
				}	
				echo "Galleria.run('#galleria-{$id}');\n";
			} 
		endif;
				
		if (isset($this->found['popeye'])) : ?>	
			jQuery(document).ready(function () {
				<?php 
					foreach ( $this->popeye_option as $id => $option ) {
						// Write out the options array
						echo $this->general->write_array("options{$id}", $option);
						echo "jQuery('#ppy'+'{$id}').popeye(options{$id});\n";
					}
				?>	
			});   
		<?php endif;

		if (isset($this->found['pslides'])) : ?>	
			jQuery(document).ready(function () {
				jQuery.PictureSlides.init();
			});
		<?php endif;
		
		if (isset($this->found['noobslide'])) :
		endif;
		
		if (isset($this->found['tiny'])) : ?>
			jQuery('.tinyslideshow').css('display','none');
			jQuery('.ts_wrapper').css('display','block');
			var slideshow = new Array();
			<?php 
				foreach ( $this->tiny_option as $id => $option ) { 
					echo "slideshow[$id] = new TINY.slideshow(\"slideshow[$id]\");\n";
					// Need to set the height of the thumbnail area
					echo "jQuery('#ts_slideleft{$id}').height({$option['thumbHeight']});\n";
					echo "jQuery('#ts_slideright{$id}').height({$option['thumbHeight']});\n";
					echo "jQuery('#ts_slidearea{$id}').height({$option['thumbHeight']});\n";
					echo "jQuery('#ts_slider{$id}').height({$option['thumbHeight']});\n";
				}
			?>	
			jQuery(document).ready(function () {
			<?php 
				foreach ( $this->tiny_option as $id => $option ) { 
					echo "slideshow[$id].auto=true;\n";
					echo "slideshow[$id].speed=5;\n";
					echo "slideshow[$id].link=\"linkhover\";\n";
					if ($option['caption']) echo "slideshow[$id].info=\"ts_information{$id}\";\n";
					else echo "slideshow[$id].info=false;\n";
					echo "slideshow[$id].thumbs=\"ts_slider{$id}\";\n";
					echo "slideshow[$id].left=\"ts_slideleft{$id}\";\n";
					echo "slideshow[$id].right=\"ts_slideright{$id}\";\n";
					echo "slideshow[$id].scrollSpeed=4;\n";
					echo "slideshow[$id].spacing=5;\n";
					echo "slideshow[$id].active=\"#fff\";\n";
					echo "slideshow[$id].init(\"tinyslideshow{$id}\",\"ts_image{$id}\",\"ts_imgprev{$id}\",\"ts_imgnext{$id}\",\"ts_imglink{$id}\");\n";
				}
			?>	
			});
		<?php endif;
			
		if (isset($this->found['native'])) :
		endif;	
		
		?>   			 
		//]]>-->
		</script>
		<?php			
	}


	function menu_order_cmp($a, $b) {
		global $menu_order;

   		$pos1 = $menu_order[$a->ID];
   		$pos2 = $menu_order[$b->ID];

   		if ($pos1==$pos2)
       		return 0;
  		 else
      		return ($pos1 < $pos2 ? -1 : 1);
	}
	
	/**
	 * Shiba Gallery Shortcode function.
	 *
	 * Borrows from the native gallery_shortcode function in wp-includes/media.php.
	 *
	 * @param array $attr Attributes attributed to the shortcode.
	 * @return string HTML content to display gallery.
	 */
	function parse_shiba_gallery($attr, $dtype) {
		global $post, $wp_locale;
		static $instance = 0;
		static $use_default = FALSE;
		$output = '';
		$instance++;
		
		if (isset($attr['type'])) {
			if (in_array($attr['type'], $this->thumb_types))
				if (!isset($attr['size']))
					$attr['size']='thumbnail';
		
		} else {
			if (in_array($dtype, $this->thumb_types))
				if (!isset($attr['size']))
					$attr['size']='thumbnail';
		}
			
		// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
		if ( isset( $attr['orderby'] ) ) {
			$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
			if ( !$attr['orderby'] )
				unset( $attr['orderby'] );
		}

		if (isset($attr['frame'])) $attr['frame'] = $this->helper->translate_frame_name($attr['frame']);	
		$default_values = apply_filters( 'shiba_gallery_defaults', 
			array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post->ID,
			'itemtag'    => 'dl',
			'icontag'    => 'dt',
			'captiontag' => 'dd',
			'caption'	 => $this->options['default_caption'],
			'columns'    => 3,
			'include'    => '',
			'exclude'    => '',
			'size' 		 => 'medium',
			'tsize'		 => 'auto',
			'link' 		 => $this->options['default_link'],
			'type' 		 => $dtype,
	
			'frame'		 => $this->options['default_frame'],
			'post_type'  => '',
			'category'	 => '',
			'tag'		 => '',
			'tag_and'		 => '',
			'recent'	 => FALSE,
			'related'	 => FALSE,
			'page'		 => 1,
			'numberposts' => -1
	
		) );
		extract(shortcode_atts($default_values, $attr));
	
		$id = absint($id);
		$order = esc_attr($order);
		$orderby = esc_attr($orderby);
		$size = esc_attr($size);
		$tsize = esc_attr($tsize);
		$type = esc_attr($type);
		$columns = absint($columns);
		$link = esc_attr($link);
		$caption = esc_attr($caption);
		
		$post_type = esc_attr($post_type);
		$category = esc_attr($category);
		$tag = esc_attr($tag);
		$tag_and = esc_attr($tag_and);
		$recent = (bool)$recent;
		$related = (bool)$related;
		$page = absint($page);
		$numberposts = intval($numberposts);
		
		if ( 'RAND' == $order )
			$orderby = 'none';
	
		// if size is custom - array(width, height) - then convert the string into an array
		if (strpos($size, '(') !== FALSE) {
			// convert size to array
			$size = explode(',', $this->general->substring($size, '(', ')') ); 
		}	
		if (is_array($size)) if (count($size) != 2) $size = 'medium';

		if (strpos($tsize, '(') !== FALSE) {
			// convert size to array
			$tsize = explode(',', $this->general->substring($tsize, '(', ')') ); 
		}	
		if (is_array($tsize)) if (count($tsize) != 2) $tsize = 'auto';

	
		// 'post_mime_type' => 'image',
		// sort menu order later
		$args = array( 	'post_status' => 'publish',
						'post_type' => $post_type, 
						'order' => $order, 
						'orderby' => $orderby,
						'numberposts' => $numberposts );

		if ($id && ($orderby == 'shiba_menu_order')) { unset($args['orderby']); unset($args['order']); }

		// add paging
		if (($numberposts > 0) && ($page > 1)) {
			$offset = ($page -1) * $numberposts;
			$args['offset'] = $offset;
		}
						
		if ($post_type == 'attachment') $args['post_status'] = NULL;
		if ( !empty($include) ) {
			$include = preg_replace( '/[^0-9,]+/', '', $include );
			$args['include'] = $include;
			if (!$post_type) { $args['post_type'] = 'any';  $args['post_status'] = 'any'; }
			$_attachments = get_posts( $args );
			
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
	
		} else {
			if ( !empty($exclude) ) {
				$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
				$args['exclude'] = $exclude;
			}	
			if ($category) :
				$category = preg_replace( '/[^0-9,]+/', '', $category );		
				$args['category'] = $category;
				if (!$post_type) $args['post_type'] = 'post'; 
				$attachments = get_posts( $args );			 
	
			elseif ($tag) :
				$args['tag'] = $tag;
				if (!$post_type) { $args['post_type'] = 'any'; $args['post_status'] = 'any'; } 
				$attachments = get_posts( $args );			 
			
			elseif ($tag_and) :
				// convert it into an array
				$args['tag_slug__and'] = explode(',',$tag_and);
				if (!$post_type) { $args['post_type'] = 'any'; $args['post_status'] = 'any'; } 
				$attachments = get_posts( $args );			 
			
			elseif ($related) : // works with the YARPP plugin to retrieve related posts and put it in a gallery
				global $yarpp;

				// MUST save state of global $wp_filter because we are currently in a $wp_filter loop
				$state = key($wp_filter['the_content']);

				if (is_object($yarpp) && is_object($yarpp->cache))  
					$attachments = $this->get_yarpp_related_current($post, $numberposts, $args);
				elseif (function_exists('yarpp_cache_enforce'))  
					$attachments = $this->get_yarpp_related_old($post, $numberposts, $args);					
				else return "<div>No related results - YARPP plugin not installed.</div>\n";
				
				if (!empty($attachments)) {
					$output = "<div class='alignspace'></div>\n";
					$output .= "<h2>Related Articles</h2>\n";
				}

				// Restore wp_filter array back to its previous state
				reset($wp_filter['the_content']);			
				while(key($wp_filter['the_content']) != $state)
					next($wp_filter['the_content']);
										
			elseif ($recent) :
				if (!$post_type) $args['post_type'] = 'post';
				else $args['post_type'] = $post_type;
				$args['post_status'] = 'publish';
				$args['order'] = 'DESC';
				$args['orderby'] = 'post_date';
				$attachments = get_posts( $args );			 
			
			else :
				$args['post_parent'] = $id;
				if (!$post_type)  { $args['post_type'] = 'any'; $args['post_status'] = 'any'; } 			
				$attachments = get_children( $args );			 
			endif;	
		}
		
		if ( empty($attachments) )
			return '<div></div>';


		// Sort menu_order here
		if ($id && ($orderby == 'shiba_menu_order')) { 
			global $menu_order;
			$menu_order = get_post_meta($id, '_menu_order', TRUE);
			if (is_array($menu_order)) {
				usort($attachments, array(&$this, 'menu_order_cmp')); 
				if ($order == 'DESC')
					$attachments = array_reverse($attachments);
				unset($menu_order);
			}	
		}	
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $attachment ) {
				$feed_link = wp_get_attachment_link($attachment->ID, "thumbnail", true);
				if (strpos($feed_link, "Missing Attachment") === FALSE)
					$output .= $feed_link . "\n";
			}	
			return $output;
		}
	
	
		$itemtag = tag_escape($itemtag);
		$captiontag = tag_escape($captiontag);
		$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
		$float = $wp_locale->text_direction == 'rtl' ? 'right' : 'left'; 
		
		$selector = "gallery-{$instance}";
		$output = apply_filters('gallery_style', $output);
		
		$args = compact('id', 'size', 'tsize', 'link', 'itemtag', 'captiontag', 'caption', 'icontag', 'columns', 'itemwidth', 'float','selector', 'frame');
	
		$pos = strpos($type, '_');
		$noobnum = 1;
		if ( $pos !== FALSE) { // get noobslide number
			$old_type = $type;
			$type = substr($type, 0, $pos);
			$noobnum = substr($old_type, $pos+1);
		}
		
		$this->found[$type] = TRUE;
		$this->load_gallery($type);
		switch ($type) {
		case 'smoothgallery':
			$this->found['tiny'] = TRUE;
		case 'tiny':	
			$output .= $this->tinyss->render($attachments, $args);
			break;	
		case 'lytebox':
			$output .= $this->lytebox->render($attachments, $args);
			break;
		case 'slimbox':
			$output .= $this->slimbox->render($attachments, $args);
			break;
		case 'pslides':
			$output .= $this->pslides->render($attachments, $args);
			break;
		case 'slideviewer':
			$this->found['noobslide'] = TRUE;	
			$output .= $this->noobslide->render($attachments, $args, 'slideviewer');
			break;	
		case 'popeye':
			$this->found['popeye'] = TRUE;	
			$output .= $this->popeye->render($attachments, $args);
			break;	
		case 'nativex':
			$this->found['noobslide'] = TRUE;	
			$output .= $this->noobslide->render($attachments, $args, 'nativex');
			break;	
		case 'noobslide':
			$output .= $this->noobslide->render($attachments, $args, $noobnum);
			break;	
		case 'native':		
			$output .= $this->native->render($attachments, $args);
			break;
		case 'navlist':
			$output .= $this->navlist->render($attachments, $args);
			break;
		case 'galleria':
			$this->found['galleria'] = TRUE;	
			$output .= $this->galleria->render($attachments, $args);
			break;
		default:
			// gallery type not found rerender using default gallery
			if ($use_default) { // no such default gallery use noobslide_thumb
				$this->options['default_gallery'] = $attr['type'] = 'noobslide_thumb';
			} else $attr['type'] = $this->options['default_gallery'];
			$use_default = TRUE;
			
			$output .= $this->parse_shiba_gallery($attr, $this->options['default_gallery']);
			break;	
		}	
		return $output;
	}

	function yarpp_distinct($distinct, $query) {
		return 'DISTINCT';
	}

	function yarpp_where($where, $query) {
		global $wpdb;
		if (isset($query->query_vars['post__not_in']) && !empty($query->query_vars['post__not_in'])) {
			$post__not_in = implode(',',  array_map( 'absint', $query->query_vars['post__not_in'] ));
			$where .= " AND {$wpdb->posts}.ID NOT IN ($post__not_in)";		
		}
//		$where = str_replace("AND {$wpdb->posts}.post_type = 'page'", "AND {$wpdb->posts}.post_type IN ('post', 'page')", $where);
		$where = str_replace("AND {$wpdb->posts}.post_type = 'page'", '', $where);
		return $where;
	}


	function get_yarpp_related_current($post, $numberposts, $args) {
		global $yarpp;

		$cache_status = $yarpp->cache->enforce($post->ID);
		$yarpp->cache->begin_yarpp_time($post->ID);

		add_filter('posts_distinct', array(&$this, 'yarpp_distinct'), 10, 2);
		add_filter('posts_where', array(&$this, 'yarpp_where'), 50, 2);
		$related_args = array( 	'p' => $post->ID,
								'order' => 'DESC',
								'orderby' => 'score',
								'post_type' => array('page', 'post'),
								'suppress_filters' => FALSE,
								'posts_per_page' => $numberposts,
								'showposts' => $numberposts );
		if ($args['exclude']) $related_args['post__not_in'] = wp_parse_id_list($args['exclude']);
								
		$related_query = new WP_Query();
		$related_query->query($related_args);

		$attachments = $related_query->posts;
		remove_filter('posts_distinct', array(&$this, 'yarpp_distinct'), 10, 2);
		remove_filter('posts_where', array(&$this, 'yarpp_where'), 10, 2);

		$yarpp->cache->end_yarpp_time(); // YARPP time is over... :(
		return $attachments;
	}
	
	function get_yarpp_related_old($post, $numberposts, $args) {
		global $yarpp_time, $yarpp_cache, $wp_filter;
		
		if (is_object($yarpp_cache)) {				
			$yarpp_cache->begin_yarpp_time($post->ID); // get ready for YARPP TIME!
			yarpp_cache_enforce($post->ID);
		} else {
			$yarpp_time = TRUE; // get ready for YARPP TIME!		
			yarpp_cache_enforce(array('post'),$post->ID);
		}	
		add_filter('posts_distinct', array(&$this, 'yarpp_distinct'), 10, 2);
		$related_args = array( 	'p' => $post->ID,
								'order' => 'DESC',
								'orderby' => 'score',
								'post_type' => array('page', 'post'),
								'suppress_filters' => FALSE,
								'posts_per_page' => $numberposts,
								'showposts' => $numberposts );
		if ($args['exclude']) $related_args['post__not_in'] = wp_parse_id_list($args['exclude']);

		$related_query = new WP_Query();
		$related_query->query($related_args);

		$attachments = $related_query->posts;
		remove_filter('posts_distinct', array(&$this, 'yarpp_distinct'), 10, 2);

		if (is_object($yarpp_cache))				
			$yarpp_cache->end_yarpp_time(); // YARPP time is over. :(
		else $yarpp_time = FALSE;

		return $attachments;
	}
	
	
	function parse_gallery_shortcode($output, $attr) {
		// get options
		$output .= $this->parse_shiba_gallery($attr, $this->options['default_gallery']);
		return $output;
	} 
	
	
	/**
	 * From wp-includes/media.php
	 */
	function gallery_caption_shortcode($output, $attr, $content) {
		$caption_type = '';

		// Check if image frames are set and if content contains img
		if (isset($this->options['image_frame']) && (strpos($content, '<img') !== FALSE)) {
			$caption_type = 'image';
			$content = str_replace(array('<br />'),'',$content);
		}
		
		// Check if content contains gallery
		if (strpos($content, '[gallery') !== FALSE) $caption_type = 'gallery';
		if (!$caption_type) return NULL;
		
		extract(shortcode_atts(array(
			'id'	=> '',
			'align'	=> 'alignnone',
			'width'	=> '',
			'frame' => $this->options['default_frame'],
			'caption' => ''
		), $attr));
		$align = esc_attr($align);
		$frame = $this->helper->translate_frame_name($frame);
		
		if ( 1 > (int) $width ) $width = 'auto';
		else $width = ($this->helper->get_frame_width($frame) + (int) $width) . 'px';	
	
		if ( $id ) $id = 'id="' . esc_attr($id) . '" ';

		$captionStr = '';
		switch ($caption_type) {	
		case 'image':
			$captionStr .= "<div {$id} class=\"wp-caption {$frame} {$align}\" style=\"width:{$width}\" >";
			$captionStr .= "<div class=\"shiba-outer shiba-gallery\" >";
			$captionStr .= "<div class=\"shiba-stage\">";
			$captionStr .= $content;
			$captionStr .= "<div class=\"wp-caption-text shiba-caption\" style=\"padding:5px 0;\">{$caption}</div>";
			$captionStr .= "</div> <!-- End shiba-stage -->";
			$captionStr .= "</div></div>";
			break;
		case 'gallery':	
			$captionStr .= 	"<div {$id} class=\"gallery-caption {$align}\" style=\"width:{$width}\" >";
			$captionStr .= do_shortcode( $content );
			$captionStr .= "<p class=\"gallery-caption-text\">{$caption}</p></div>";
			break;
		}
		return $captionStr;	
	}
} // end class
endif;


global $shiba_gallery;
if (class_exists("Shiba_Gallery") && !$shiba_gallery) {
    $shiba_gallery = new Shiba_Gallery();	
}	
?>