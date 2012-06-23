<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_Native")) :

class Shiba_Gallery_Native {

	function render($attachments, $attr) {
		global $shiba_gallery;
		extract($attr);
	
		$output = "
			<style type='text/css'>
				#{$selector} .shiba-outer {
					margin: auto;
				}
				#{$selector} .gallery-item {
					float: {$float};
					margin-top: 10px;
					text-align: center;
					width: {$itemwidth}%;			}
				#{$selector} .gallery-caption {
					margin: 0px 5px 0px 5px;
				}
			</style>
			<!-- see gallery_shortcode() in wp-includes/media.php -->
			";
		
		$output .= "<div id='$selector' class='gallery galleryid-{$id} shiba-gallery {$frame}'>";
	
		$size_arr = $shiba_gallery->helper->get_gallery_size($attachments, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];

		$i = 0; $j = 0; 		
		foreach ( $attachments as $id => $attachment ) {
			$url = $shiba_gallery->helper->get_attachment_url($attachment, $link);
			$img_caption = $shiba_gallery->helper->get_caption($attachment, $caption, $link);
			
			$img = $all_img[$j]; $j++;
			$outerW = $img[1] + $shiba_gallery->helper->get_frame_inner_width($frame);
			$output .= "<{$itemtag} class='gallery-item'>";
			$output .= "<{$icontag} class='gallery-icon'>";
			$output .= "<div class=\"shiba-outer\" style=\"width:{$outerW}px;\">";
			// Link image based on link argument
			if ($url)
				$output .= "<a href=\"{$url}\"><img src=\"{$img[0]}\" width={$img[1]} height={$img[2]} class=\"shiba-stage\"/></a>";
			else
				$output .= "<img src=\"{$img[0]}\" width={$img[1]} height={$img[2]} class=\"shiba-stage\"/>";
					
			$output .= "</div></{$icontag}>";
			// wp_texturize	
			if ( $captiontag ) {
				$output .= "
					<{$captiontag} class='native-text'>
					" . $img_caption . "
					</{$captiontag}>";
			}
			$output .= "</{$itemtag}>";
			if ( $columns > 0 && ++$i % $columns == 0 )
				$output .= '<br style="clear: both" />';
		}
	
		$output .= "
				<br style='clear: both;' />
			</div>\n";
	
		return $output;
	}
} // end class
endif;
?>