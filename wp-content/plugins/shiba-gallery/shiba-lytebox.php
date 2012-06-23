<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_Lytebox")) :

class Shiba_Gallery_Lytebox {

	function render($images, $args) {
		global $shiba_gallery;
		static $lyteNum = 1;
	
		extract($args);
		$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];
		
		$j = 0;
		$imgStr = "<div class=\"shiba-gallery $frame\">";
		foreach ( $images as $image ) {	
			$title = $shiba_gallery->helper->get_attachment_title($image); 
			$img_caption = $shiba_gallery->helper->get_caption($image, $caption, $link);
	
			// Set the link to the attachment URL
			// wp_get_attachment_image or wp_get_attachment_link or wp_get_attachment_image_src
			$img = $all_img[$j];
			$full = $shiba_gallery->helper->get_attachment_image_src($image->ID, "full");
			$full_empty = SHIBA_GALLERY_URL . '/images/full_empty1.jpg';
			if (($img[1] <= 1) && ($img[2] <= 1)) {
				$img[1] = 100; $img[2] = 100;
			}
			$padding = $shiba_gallery->helper->get_padding(array($maxW,$maxH), $img);
					
			$imgStr .= "<div class=\"lytebox-thumb shiba-outer\" style=\"width:{$maxW}px;height:{$maxH}px;\">";
			if (is_array($full)) 
				$imgStr .= "<a href=\"{$full[0]}\" rel=\"lytebox[shiba{$lyteNum}]\" title='{$img_caption}'>";
			else	
				$imgStr .= "<a href=\"{$full_empty}\" rel=\"lytebox[shiba{$lyteNum}]\" title='{$img_caption}'>";
			$imgStr .= "<img src=\"{$img[0]}\" width=\"{$img[1]}\" height=\"{$img[2]}\" style=\"padding:{$padding};width:{$img[1]}px; height:{$img[2]}px;\" alt=\"{$title}\" title=\"{$title}\" class=\"shiba-stage\"/>";
			$imgStr .= "</a></div>\n";
			$j++;
		}
		$imgStr .= '<div style="clear:both;"></div>' ."\n";
		$imgStr .= "</div>\n";
		$lyteNum++;
		return $imgStr;
	}
} // end class
endif;
?>