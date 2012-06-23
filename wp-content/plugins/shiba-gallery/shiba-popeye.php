<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_Popeye")) :

class Shiba_Gallery_Popeye {

	function open_popeye($size, $args) {
		global $shiba_gallery;
		$outerW = $size[0] + $shiba_gallery->helper->get_frame_width($args['frame']);
		
		$outStr = "<div class=\"{$args['frame']} shiba-gallery popeye\" style=\"width:{$outerW}px;\">\n";
		$outStr .= "<div class=\"ppy\" id=\"ppy{$shiba_gallery->tsNum}\">\n";
		$outStr .= "<ul class=\"ppy-imglist\">\n";
		return $outStr;
	}
	

	function close_popeye($size, $args) {
		global $shiba_gallery;
		$outStr = "</ul>\n";

			$outStr .= "<div class=\"ppy-outer shiba-outer\">\n";
			$outStr .= "<div class=\"ppy-stage shiba-stage\" style=\"width:{$size[0]}px;height:{$size[1]}px;\">\n";
 			$outStr .= '
                   <div class="ppy-nav"> 
                        <div class="nav-wrap"> 
                            <a class="ppy-prev" title="Previous image">Previous image</a> 
                            <a class="ppy-switch-enlarge" title="Enlarge">Enlarge</a> 
                            <a class="ppy-switch-compact" title="Close">Close</a> 
                            <a class="ppy-next" title="Next image">Next image</a> 
                        </div> 
                    </div> 
                    <div class="ppy-counter"> 
                        <strong class="ppy-current"></strong> / <strong class="ppy-total"></strong> 
                    </div> ';
           	$outStr .=  "</div><!--End ppy-stage-->\n";
			if ($args['caption'] == 'permanent') $outStr .= "<div class=\"ppy-caption shiba-caption\" style=\"height:60px;\">\n";
		   	else $outStr .= "<div class=\"ppy-caption shiba-caption\">\n";
		   	$outStr .= "<span class=\"ppy-text\"></span>\n"; 
           	$outStr .= "</div> <!-- Close caption -->\n";
			$outStr .= "</div><!--End ppy-outer -->\n"; 
			$outStr .= "</div></div>\n";
		$shiba_gallery->tsNum++;
		return $outStr;		
	}	


		
	function render($images, $args) {
		global $shiba_gallery;	
		extract($args);
	
		$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];

		// Add popeye options
		switch ($caption) {
		case 'title':
		case 'description':	
			$shiba_gallery->popeye_option[$shiba_gallery->tsNum] = array("caption" => "hover");
			break;
		case 'none':
			$shiba_gallery->popeye_option[$shiba_gallery->tsNum] = array("caption" => FALSE);
			break;
		case 'permanent':
			$shiba_gallery->popeye_option[$shiba_gallery->tsNum] = array("caption" => "permanent");
			break;
		}
		if ($args['frame'] == 'frame2') $shiba_gallery->popeye_option[$shiba_gallery->tsNum]['navigation'] = 'permanent';	
				
		$imgStr = $this->open_popeye($size_arr, $args);
		$j = 0;		
		foreach ( $images as $image ) {		
			$img_caption = $shiba_gallery->helper->get_caption($image, $caption, $link);
			
			// Get url of full image
			$full = $shiba_gallery->helper->get_attachment_image_src($image->ID, "full");
			$full_empty = SHIBA_GALLERY_URL . '/images/full_empty1.jpg';
			$img = $all_img[$j]; $j++;
			
			// padding for main image
			$left_pad = intval(ceil(($maxW-$img[1]) *0.5));
			$top_pad = intval(ceil(($maxH-$img[2]) *0.5));
 	
			$imgStr .= "<li>\n";
			if (is_array($full)) $imgStr .= "<a href=\"{$full[0]}\">\n";
			else $imgStr .= "<a href=\"{$full_empty}\">\n";
			$imgStr .= "<img src=\"{$img[0]}\" alt=\"\" width=\"{$img[1]}\" height=\"{$img[2]}\"/>\n";
			$imgStr .= "</a>\n";
			$imgStr .= "<span class=\"ppy-extcaption\">\n";
			$imgStr .= $img_caption;
			$imgStr .= "</span>\n";
			$imgStr .= "</li>\n";
		}
		$imgStr .= $this->close_popeye($size_arr, $args);
		return $imgStr;
	}
} // end class
endif;
?>