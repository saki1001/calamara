<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_TinySS")) :

class Shiba_Gallery_TinySS {

	function open_tinyss($size, $args) {
		global $shiba_gallery;
	
		$outStr = "<ul id=\"tinyslideshow{$shiba_gallery->tsNum}\" class=\"tinyslideshow\" >\n";
		return $outStr;
	}
	

	function close_tinyss($size, $args) {
		global $shiba_gallery;
		$wrap_size = $size[0] + $shiba_gallery->helper->get_frame_width($args['frame']); // plus frame border
		$slide_size = $wrap_size - 50;
		$outStr = "
		</ul>
		<div id=\"ts_wrapper{$shiba_gallery->tsNum}\" class=\"ts_wrapper shiba-gallery {$args['frame']}\" style=\"width:{$wrap_size}px;\">
			<div class=\"shiba-outer\">
				<div id=\"ts_fullsize{$shiba_gallery->tsNum}\" class=\"ts_fullsize  shiba-stage\">\n";
		$outStr .= "<div id=\"ts_imgprev{$shiba_gallery->tsNum}\" class=\"ts_imgnav ts_imgprev\" style=\"height:{$size[1]}px;\" title=\"Previous Image\"></div>\n";
		$outStr .= "<div id=\"ts_imglink{$shiba_gallery->tsNum}\" class=\"ts_imglink\"></div>\n";
		$outStr .= "<div id=\"ts_imgnext{$shiba_gallery->tsNum}\" class=\"ts_imgnav ts_imgnext\" style=\"height:{$size[1]}px;\" title=\"Next Image\"></div>\n";
		
		$outStr .= "<div id=\"ts_image{$shiba_gallery->tsNum}\" class=\"ts_image\" style=\"width:{$size[0]}px;height:{$size[1]}px;\"></div>\n";
		$outStr .= "<div id=\"ts_information{$shiba_gallery->tsNum}\" class=\"ts_information shiba-caption\" style=\"width:{$size[0]}px;\">
					<h3></h3>
					<p></p>
					</div>\n";
					
		$outStr .= "</div>\n"; // close ts_fullsize
		$outStr .= "</div>\n"; // close shiba-outer
		$outStr .= "
			<div id=\"ts_thumbnails{$shiba_gallery->tsNum}\" class=\"ts_thumbnails\">
				<div id=\"ts_slideleft{$shiba_gallery->tsNum}\" class=\"ts_slideleft\" title=\"Slide Left\"></div>
				<div id=\"ts_slidearea{$shiba_gallery->tsNum}\" class=\"ts_slidearea\" style=\"width:{$slide_size}px;\">
					<div id=\"ts_slider{$shiba_gallery->tsNum}\" class=\"ts_slider\"></div>
				</div>
				<div id=\"ts_slideright{$shiba_gallery->tsNum}\" class=\"ts_slideright\" title=\"Slide Right\"></div>
			</div>\n";
		$outStr .= "</div>\n"; // close ts_wrapper
		$outStr .= "<div style=\"clear:left;\"></div>";
		$shiba_gallery->tsNum++;
		return $outStr;		
	}	


		
	function render($images, $args) {
		global $shiba_gallery;	
		extract($args);
	
		$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];
		$tsize = $shiba_gallery->helper->get_thumb_size($tsize, $maxW, $maxH);
		
		$shiba_gallery->tiny_option[$shiba_gallery->tsNum] = 
			array(	'caption' => ($caption == 'none')?FALSE:TRUE,
					'thumbHeight' => $tsize[1] );
		$imgStr = $this->open_tinyss($size_arr, $args);

		$j = 0; 		
		foreach ( $images as $image ) {		
			$title = $shiba_gallery->helper->get_attachment_title($image); 
			$description = $shiba_gallery->helper->get_attachment_description($image);
			$imglink = $shiba_gallery->helper->get_attachment_link($image, $args['link']);
			$url = $shiba_gallery->helper->get_attachment_url($image, $link);
			$thumb = $shiba_gallery->helper->get_attachment_image_src($image->ID, $tsize);
			
			$img = $all_img[$j]; $j++;
			
			// padding for main image
			$left_pad = intval(ceil(($maxW-$img[1]) *0.5));
			$top_pad = intval(ceil(($maxH-$img[2]) *0.5));
			// padding for thumb
			$padding = $shiba_gallery->helper->get_padding($tsize, $thumb);
	
			$imgStr .= "<li>\n";
			$imgStr .= "<h3>{$imglink}</h3>\n";
			$imgStr .= "<span style=\"width:{$img[1]}px;height:{$img[2]}px;padding:{$top_pad}px 0px;\">{$img[0]}</span>\n";
			if (($caption == 'description') || ($caption == 'permanent')) $imgStr .= "<p>{$description}</p>\n";
			else $imgStr .= "<p></p>\n";
			$imgStr .= "<a href=\"{$url}\"><img alt='{$title}' width=\"{$thumb[1]}\" height=\"{$thumb[2]}\"  src=\"{$thumb[0]}\" style=\"padding:{$padding};\"/></a>\n";
			$imgStr .= "</li>\n";
		}
		$imgStr .= $this->close_tinyss($size_arr, $args);
		return $imgStr;
	}
} // end class
endif;
?>