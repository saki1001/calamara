<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_Galleria")) :

class Shiba_Gallery_Galleria {

	function render($images, $args) {
		global $shiba_gallery;	

		extract($args);

//		$options = array('thumbnails' => FALSE);
		switch ($caption) {
		case 'none':
			 $options['showInfo'] = TRUE;
			break;
		case 'title':
		case 'description':	
		default:
			 $options['showInfo'] = TRUE;
			break;
		}
		$shiba_gallery->galleria_option[$shiba_gallery->tsNum] = $options;
		
		$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];
		// Actual thumb size is set automatically by the gallery though.
		$tsize = $shiba_gallery->helper->get_thumb_size($tsize, $maxW, $maxH);
		
		$j = 0; 
		$gallery_width = $maxW + 55; // For thumbnails and spaces
		$gallery_height = $maxH + 50; // For thumbnails and spaces
		$imgStr = "<div id=\"galleria-{$shiba_gallery->tsNum}\" class=\"shiba-gallery $frame\" style=\"width:{$gallery_width}px;height:{$gallery_height}px;\">";
		foreach ( $images as $image ) {		
			$title = $shiba_gallery->helper->get_attachment_title($image);
			$linkStr = $shiba_gallery->helper->get_attachment_url($image, $link);
	
			// Set the link to the attachment URL
			// wp_get_attachment_image or wp_get_attachment_link or wp_get_attachment_image_src
			$img = $all_img[$j];
			$thumb = $shiba_gallery->helper->get_attachment_image_src($image->ID, array($tsize[0], $tsize[1]));
			$full = $shiba_gallery->helper->get_attachment_image_src($image->ID, "full");
			$full_empty = SHIBA_GALLERY_URL . '/images/full_empty1.jpg';
			if (($img[1] <= 1) && ($img[2] <= 1)) {
				$img[1] = 100; $img[2] = 100;
			}

			$imgStr .= "<a href=\"{$img[0]}\">\n";
			switch ($caption) {
			case 'none':
				$imgStr .= "<img src=\"{$thumb[0]}\"  data-big=\"{$full[0]}\" data-link=\"{$linkStr}\" class=\"shiba-stage\"/>";
				break;
			case 'title':
				$imgStr .= "<img src=\"{$thumb[0]}\"  data-big=\"{$full[0]}\" data-title=\"{$title}\" data-link=\"{$linkStr}\" class=\"shiba-stage\"/>";
				break;
			case 'description':	
			default:
				$description = $shiba_gallery->helper->get_attachment_description($image);
				$imgStr .= "<img src=\"{$thumb[0]}\"  data-big=\"{$full[0]}\" data-description=\"{$description}\" data-title=\"{$title}\" data-link=\"{$linkStr}\" class=\"shiba-stage\"/>";
				break;
			}
			
			$imgStr .= "</a>\n";
			$j++;
		}
		$imgStr .= '<div style="clear:both;"></div>' ."\n";
		$imgStr .= "</div>\n";
		$shiba_gallery->tsNum++;
		return $imgStr;
	}
} // end class
endif;
?>