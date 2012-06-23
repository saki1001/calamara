<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_NavList")) :

class Shiba_Gallery_NavList {

	function render($images, $args) {
		global $shiba_gallery;	
		static $navNum = 1;

		extract($args);
		
		$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];
		
		$j = 0;
		$imgStr = "<div class=\"shiba-gallery $frame\">";
		foreach ( $images as $image ) {		
			$title = $shiba_gallery->helper->get_attachment_title($image);
			$img_link = $shiba_gallery->helper->get_attachment_link($image, $link);
			$description = $shiba_gallery->helper->get_attachment_description($image, 200);
	
			// Set the link to the attachment URL
			// wp_get_attachment_image or wp_get_attachment_link or wp_get_attachment_image_src
			$img = $all_img[$j];
			if (($img[1] <= 1) && ($img[2] <= 1)) {
				$img[1] = 100; $img[2] = 100;
			}
			$padding = $shiba_gallery->helper->get_padding(array($maxW,$maxH), $img);
			
			// insert image into image_link
			$img_html = "<div class=\"navlist-image shiba-outer\">";
			$img_html .= "<img src=\"{$img[0]}\" width=\"{$img[1]}\" height=\"{$img[2]}\" style=\"padding:{$padding};\" alt=\"{$title}\" title=\"{$title}\" class=\"shiba-stage\"/>";
			$img_html .= "</div>";
			$img_link = str_replace('">', '">'.$img_html, $img_link);
			
			$imgStr .= "<div class=\"navlist-item\">";
			$imgStr .= $img_link;
			$imgStr .= '<br/>';
			$imgStr .= $description;
			$imgStr .= "</div>\n";
			$j++;
		}
		$imgStr .= "</div>\n";
		$navNum++;
		return $imgStr;
	}
} // end class
endif;
?>