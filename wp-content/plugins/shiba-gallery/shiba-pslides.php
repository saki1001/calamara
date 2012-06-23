<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_PSlides")) :

class Shiba_Gallery_PSlides {

	function js_pslides($size, $args, $images, $all_img) {
		global $shiba_gallery;

		$jsStr = "	
		jQuery.PictureSlides.set({
			containerId : \"picture-slides-container{$shiba_gallery->tsNum}\",
			// Large images to use and thumbnail settings
			images : [\n";
		
		$j = 0;	$imgNum = 0; $found = array();
		foreach ( $images as $image ) {		
			$description = $shiba_gallery->helper->get_attachment_description($image);
			$img_caption = $shiba_gallery->helper->get_caption($image, $args['caption'], $args['link']);
			$img = $all_img[$j]; $j++;
			$img[0] .= "?".$imgNum++; // stop chrome from caching image

			// padding for main image
			$padding = $shiba_gallery->helper->get_padding($size, $img);
			$jsStr .= "
				{
					image : \"{$img[0]}\", 
					width :  \"{$img[1]}\",
					height: \"{$img[2]}\",
					style: \"width:{$img[1]}px;height:{$img[2]}px;padding:{$padding};\",
					alt : \"{$description}\",
					text : '{$img_caption}'
				},\n";
		}
		
		// remove last comma (needed for explorer)
		$jsStr = substr($jsStr, 0, strlen($jsStr)-2);		
		$jsStr .= '
			]
		});';
		return $jsStr;
	}	
	
		
	function open_pslides($size, $args, $images, $all_img) {
		global $shiba_gallery;
		$key = key($images);
		$img_caption = $shiba_gallery->helper->get_caption($images[$key], $args['frame'], $args['link']);
		$outerW = $size[0] + $shiba_gallery->helper->get_frame_width($args['frame']);
		$outerH = $size[1] + $shiba_gallery->helper->get_frame_inner_width($args['frame']);
		$outStr = "<div id=\"picture-slides-container{$shiba_gallery->tsNum}\" class=\"picture-slides-container {$args['frame']} shiba-gallery\" style=\"width:{$outerW}px;\">\n";

		$outStr .= "<div class=\"picture-slides-fade-container shiba-outer\"  style=\"height:{$outerH}px;\">\n";
		$outStr .= "<a class=\"picture-slides-image-link\">
					<span class=\"picture-slides-image-load-fail\">The image failed to load:</span>
					<img class=\"picture-slides-image shiba-stage\" src=\"{$all_img[0][0]}\" alt=\"{$img_caption}\" /></a>\n";
		$outStr .= "</div>\n"; // Close picture-slides-fade-container
		if ($args['caption'] != 'none')
			$outStr .= 	"<div class=\"picture-slides-image-text\">{$img_caption}</div>\n";
		
	  	$outStr .= '
			<div class="navigation-controls shiba-nav">
				<a href="#" class="picture-slides-previous-image">Previous</a>
				<span class="picture-slides-image-counter"></span>
				<a href="#" class="picture-slides-next-image">Next</a>
		
				<a href="#" class="picture-slides-start-slideshow">Start slideshow</a>
				<a href="#" class="picture-slides-stop-slideshow">Stop slideshow</a>
			</div>';
		$outStr .= "<ul class=\"picture-slides-thumbnails\">\n";

		return $outStr;
	}
	

	function close_pslides($size, $args) {
		global $shiba_gallery;
		$wrap_size = $size[0] + $shiba_gallery->helper->get_frame_width($args['frame']); // plus frame border
		$slide_size = $wrap_size - 50;

		$outStr = "</ul>\n"; 
		$outStr .= "<div class=\"picture-slides-dim-overlay\"></div>\n";
		$outStr .= "</div>\n"; // Close picture-slides-container

		$shiba_gallery->tsNum++;
		return $outStr;		
	}	


		
	function render($images, $args) {
		global $shiba_gallery;	
		extract($args);
	
		$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
		$maxW = $size_arr[0]; $maxH = $size_arr[1];
		$tsize = $shiba_gallery->helper->get_thumb_size($tsize, $maxW, $maxH);

//		$shiba_gallery->pslide_option[$shiba_gallery->tsNum] = array('caption' => ($caption == 'none')?FALSE:TRUE );
		$shiba_gallery->jsStr .= $this->js_pslides($size_arr, $args, $images, $all_img);
		$imgStr = $this->open_pslides($size_arr, $args, $images, $all_img);
		$j = 0; 				
		foreach ( $images as $image ) {		
			$url = $shiba_gallery->helper->get_attachment_url($image, $link);
			$thumb = $shiba_gallery->helper->get_attachment_image_src($image->ID, $tsize);			
			$img = $all_img[$j]; $j++;
			
			// padding for thumb
			$padding = $shiba_gallery->helper->get_padding($tsize, $thumb);
	
			$imgStr .= "<li>\n";
			$imgStr .= "<a href=\"{$url}\"><img alt='{$image->post_title}'\"  src=\"{$thumb[0]}\" style=\"width:{$thumb[1]}px;height:{$thumb[2]}px;padding:{$padding};\"/></a>\n";
			$imgStr .= "</li>\n";
		}
		$imgStr .= $this->close_pslides($size_arr, $args);
		return $imgStr;
	}
} // end class
endif;
?>