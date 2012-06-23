<?php
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

require_once('includes/meta-boxes.php');

global $shiba_gallery;
$action = '';
$location = "upload.php?page=shiba_gallery"; // based on the location of your sub-menu page

switch($action) :
default:
endswitch;

if (isset($_POST['save_gallery_options']) || isset($_POST['default_image'])) {

	if ( ! current_user_can('switch_themes') )
		wp_die(__('You are not allowed to change gallery settings.'));
	check_admin_referer("shiba_gallery_options");

	// remove non gallery options from POST array
	unset($_POST['_wpnonce'], $_POST['_wp_http_referer'], $_POST['save_gallery_options']);
	
	update_option('shiba_gallery_options', $_POST);
	$location = add_query_arg('message', 1, $location);

	$shiba_gallery->general->javascript_redirect($location);
	exit;
}	


$messages[1] = __('Shiba gallery settings updated.', 'shiba_gallery');
$messages[2] = __('Shiba gallery settings failed to update.', 'shiba_gallery');

if ( isset($_GET['message']) && (int) $_GET['message'] ) {
	$message = $messages[$_GET['message']];
	$_SERVER['REQUEST_URI'] = remove_query_arg(array('message'), $_SERVER['REQUEST_URI']);
}

		
$title = __('Shiba gallery Options', 'gallery_options');
add_meta_box(	'postimagediv', __('Default Gallery Image'), 'post_thumbnail_meta_box', 
				$shiba_gallery->option_page, 'side', 'core');
?>
<style>
#shiba-gallery_options h3 { margin-top:30px; }
</style>

    <div class="wrap">   
    <?php screen_icon(); ?>
    <h2><?php echo esc_html( $title ); ?></h2>

	<?php
		if ( !empty($message) ) : 
		?>
		<div id="message" class="updated fade"><p><?php echo $message; ?></p></div>
		<?php 
		endif; 
		$options = get_option('shiba_gallery_options');
		if (!is_array($options)) $options = array();
	?>

    <form name="shiba-gallery_options" id="shiba-gallery_options" method="post" action="" class="">
        <?php wp_nonce_field("shiba_gallery_options"); ?> 

		<h3>Set Default Gallery Type</h3>
		<p>Please enter which gallery type to want to use as the default.</p>
        <input type="text" size="35" name="default_gallery" value="<?php echo $options['default_gallery'];?>">
        
        <p>
        <small>Current gallery types include - tiny, slimbox, pslides, popeye, native, and a variety of noobslide galleries.<br/>
        Noobslide gallery types include - 1 through 8, galleria, slideviewer, thumb, nativex. Specified as noobslide_1, noobslide_galleria, etc.</small>
        </p>
        
		<h3>Set Default Image</h3>
		<p>Please pick which image you want to use as a default thumbnail when a gallery post or page does not have an assigned thumbnail/featured image.</p>
        
        <p>
        <?php if (isset($options['default_image']) && $options['default_image']) { 
			$img = wp_get_attachment_image_src($options['default_image'], array(200, 200));
		?>
        	<img src="<?php echo $img[0];?>" width="<?php echo $img[1];?>" height="<?php echo $img[2];?>"/>
        <?php } ?>
        </p>
        
        <p>
        <a title="Set featured image" href="media-upload.php?post_id=-1371&type=image&TB_iframe=1" id="set-post-thumbnail" class="thickbox">Set Default Image</a> | 
        <a href="#" onClick="document.getElementById('default_image').value = '';document.getElementById('shiba-gallery_options').submit()">Unset Default Image</a>
        </p>

  		<input name="default_image" id="default_image" type="hidden" value="<?php echo (isset($options['default_image']))? $options['default_image'] : "";?>"/>
        
        <h3>Set Default Frame</h3>
        <?php 
			$selected = (isset($options['default_frame'])) ? $options['default_frame'] : 'frame4';
			$shiba_gallery->helper->render_frame_options('default_frame', $selected); ?>

		<p><input type="checkbox" name="image_frame" <?php if (isset($options['image_frame'])) echo 'checked';?>/>  Use default frame on images.</p>

        <h3>Set Default Caption</h3>
		<select name='default_caption' id='default_caption'>
			<!-- Display themes as options -->
			<?php 
				if (isset($options['default_caption']))
					$selected = $options['default_caption'];
				else
					$selected = 'title';	
				echo $shiba_gallery->general->write_option("None", "none", $selected);
				echo $shiba_gallery->general->write_option("Title", "title", $selected);
				echo $shiba_gallery->general->write_option("Description", "description", $selected);
				echo $shiba_gallery->general->write_option("Permanent", "permanent", $selected);
		   ?>
		</select>
 
         <h3>Set Default Link</h3>
		<select name='default_link' id='default_link'>
			<!-- Display themes as options -->
			<?php 
				if (isset($options['default_link']))
					$selected = $options['default_link'];
				else
					$selected = 'attachment';	
				echo $shiba_gallery->general->write_option("None", "none", $selected);
				echo $shiba_gallery->general->write_option("File", "file", $selected);
				echo $shiba_gallery->general->write_option("Attachment", "attachment", $selected);
		   ?>
		</select>
       
       <h3>
		<input name="save_gallery_options" type="submit" class="button button-primary" value="<?php esc_attr_e('Save Options'); ?>"/>
        </h3>
    </form>
</div>