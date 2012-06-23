<?php
// don't load directly
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_NoobSlide")) :

class Shiba_Gallery_NoobSlide {
	var $caption_opacity = 0.7;
	
	function generate_containers($id, $size, $args, $doclick = TRUE, $noobnum = '1') {
		global $shiba_gallery;
		
		// Make all noobslide image area clickable and advance
		if ($doclick)
			$click_event = "onClick=\"nS{$shiba_gallery->nsNum}.next();\"";
		else $click_event = "";
		
		$outStr = '';
		$outerW = $size[0] + $shiba_gallery->helper->get_frame_inner_width($args['frame']);
		$noobstyle = "width:{$size[0]}px; height:{$size[1]}px;";
		
		switch ($noobnum) {
			case '6':
				$mainW = $outerW + $shiba_gallery->helper->get_frame_width($args['frame']) + 60 + 60;
				$outStr .= "<div class='noobmain {$args['frame']} shiba-gallery' style=\"width:{$mainW}px;margin:auto;\">\n";
				$outStr .= "<div class='shiba-outer' style=\"width:{$outerW}px;float:left;\">\n";
				$outStr .= "<div class='noobmask shiba-stage' style='{$noobstyle}' >\n";
				$outStr .= "<div id='$id' class='noobslide' {$click_event}>\n";
			break;
			default:
				$mainW = $outerW + $shiba_gallery->helper->get_frame_width($args['frame']);
				$outStr .= "<div class='noobmain {$args['frame']} shiba-gallery' style=\"width:{$mainW}px;margin:auto;\">\n";
				$outStr .= "<div class='shiba-outer' style=\"width:{$outerW}px;\">\n";
				$outStr .= "<div class='noobmask shiba-stage' style='{$noobstyle}' >\n";
				$outStr .= "<div id='$id' class='noobslide' {$click_event}>\n";
			break;
		}	
		return $outStr;
	}

	function _generate_object_item($image, $link) {
		global $shiba_gallery;
		
		$title = esc_js($image->post_title);
		$author = $image->post_author;
		$date = $image->post_date;
		$imglink = $shiba_gallery->helper->get_attachment_url($image, $link);
		return "title:'$title', author:'$author', date:'$date', link:'$imglink'";
	}

	function generate_object_items($images, $link) {
		$outStr = '';
		foreach ($images as $image) {
			$outStr .= "{";
			$outStr .= $this->_generate_object_item($image, $link);
			$outStr .= "},\n";
		}
		// remove last ','
		$outStr = substr($outStr, 0, strlen($outStr)-2);
		return $outStr;
	}

	function create_items($all_img) {
		$outStr = "[";
		for ($i = 0; $i < count($all_img)-1; $i++) {
			$outStr .= $i . ",";
		}
		$outStr .= $i . "]";
		return $outStr;
	}


	function open_noobslide($size, $tsize, $args, $images, $all_img, $noobnum) {
		global $shiba_gallery;
		
		$id = "noobslide".$shiba_gallery->nsNum;
		$item_list = $this->create_items($all_img);
		$outStr = "";
		
		$jsStr = "
			var nS{$shiba_gallery->nsNum};
			window.addEvent('domready',function(){
		";
		
		switch ($noobnum) {	
		case '1':
		$jsStr .= "
			//SAMPLE 1 (auto, every 5 sec)
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $item_list,
				size: $size[0],
				autoPlay: true
			});
		});	
		";
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;
		
		case '3':
		$jsStr .= "	
			//SAMPLE 3 (play, stop, playback)
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $item_list,
				size: $size[0],
				interval: 1000,
				startItem: 0,
				addButtons: {
					playback: document.id('playback$shiba_gallery->nsNum'),
					stop: document.id('stop$shiba_gallery->nsNum'),
					play: document.id('play$shiba_gallery->nsNum')
				}
			});
		});
		";
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;
		
		case '4':
			$w = $size[0]; $h = $size[1];
	
		$jsStr .= "	
			//SAMPLE 4 (walk to item)
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $$('#$id div'),
				size: $w,
				handles: $$('#handles$shiba_gallery->nsNum span'),
				onWalk: function(currentItem,currentHandle){
					document.id('noobslide_info$shiba_gallery->nsNum').set('html',currentItem.getElement('h3').innerHTML);
					this.handles.removeClass('active');
					currentHandle.addClass('active');
				}
			});
		});
		";
	
		$outStr .= 	$this->generate_containers($id, array($w,$h), $args, FALSE);
		break;
		case '5':
		$noobObjItems = $this->generate_object_items($images, $args['link']);
		if ($args['caption'] != 'none')
			$jsStr .= "var info$shiba_gallery->nsNum = document.id('info$shiba_gallery->nsNum').set('opacity',{$this->caption_opacity});\n";
		$jsStr .= "	
			//SAMPLE 5 (mode: vertical, using 'onWalk' )
			var sampleObjectItems =[
				$noobObjItems
			];
			nS$shiba_gallery->nsNum = new noobSlide({
				mode: 'vertical',
				box: document.id('$id'),
				size: $size[1],
				items: sampleObjectItems,
				addButtons: {
					previous: document.id('prev$shiba_gallery->nsNum'),
					play: document.id('play$shiba_gallery->nsNum'),
					stop: document.id('stop$shiba_gallery->nsNum'),
					next: document.id('next$shiba_gallery->nsNum')
				}";
		if ($args['caption'] != 'none')
			$jsStr .= ",
				onWalk: function(currentItem){
					info$shiba_gallery->nsNum.empty();
					if (currentItem.link) {
						new Element('h4').set('html','<a class=\"nooblink\" href=\"'+currentItem.link+'\">link</a>'+currentItem.title).inject(info$shiba_gallery->nsNum); 
					} else {
						new Element('h4').set('html',currentItem.title).inject(info$shiba_gallery->nsNum); 					
					}	
	//				new Element('p').set('html','<b>Author</b>: '+currentItem.author+' &nbsp; &nbsp; <b>Date</b>: '+currentItem.date).inject(info$shiba_gallery->nsNum);
				}\n";
		$jsStr .= "		
			});
		});\n";
		
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;
		case '6':
		$noobObjItems = $this->generate_object_items($images, $args['link']);
		if ($args['caption'] != 'none')
			$jsStr .= "var info$shiba_gallery->nsNum = document.id('$id').getNext().set('opacity',{$this->caption_opacity});\n";
		$jsStr .= "	
			//SAMPLE 6 (on 'mouseenter' walk)
			var sampleObjectItems =[
				$noobObjItems
			];
			nS$shiba_gallery->nsNum = new noobSlide({
				mode: 'vertical',
				box: document.id('$id'),
				items: sampleObjectItems,
				size: $size[1],
				handles: $$('#handles{$shiba_gallery->nsNum}_1 div').extend($$('#handles{$shiba_gallery->nsNum}_2 div')),
				handle_event: 'click',
				addButtons: {
					previous: document.id('prev{$shiba_gallery->nsNum}'),
					play: document.id('play{$shiba_gallery->nsNum}'),
					stop: document.id('stop{$shiba_gallery->nsNum}'),
					playback: document.id('playback{$shiba_gallery->nsNum}'),
					next: document.id('next{$shiba_gallery->nsNum}')
				},
				button_event: 'click',
				fxOptions: {
					duration: 1000,
					transition: ".$shiba_gallery->TRANSITION.",
					wait: false
				},
				onWalk: function(currentItem,currentHandle){\n";
		if ($args['caption'] != 'none')
			$jsStr.="
					info$shiba_gallery->nsNum.empty();
					if (currentItem.link) {
						new Element('h4').set('html','<a class=\"nooblink\" href=\"'+currentItem.link+'\">link</a>'+currentItem.title).inject(info$shiba_gallery->nsNum); 
					} else {
						new Element('h4').set('html',currentItem.title).inject(info$shiba_gallery->nsNum); 					
					}	
	//				new Element('p').set('html','<b>Author</b>: '+currentItem.author+' &nbsp; &nbsp; <b>Date</b>: '+currentItem.date).inject(info6);\n";
		$jsStr .="
					this.handles.set('opacity',0.3);
					currentHandle.set('opacity',1);
				}
			});
			//walk to next item
	//		nS6.next();
		});";
		
		$vthumb_w = $tsize[0] + 6;
		$thumbDiv = "\n<div class=\"noobslide_thumbs noobslide_vthumbs\" id=\"handles{$shiba_gallery->nsNum}_1\" style=\"width:{$vthumb_w}px\">\n";
		// draw firrst half of thumbnails
		$num_images = count($images);
		$end = intval(ceil($num_images * 0.5));
		$i = 0;
		foreach ($images as $image) {
			$img = $shiba_gallery->helper->get_attachment_image_src($image->ID, $tsize);
			$padding = $shiba_gallery->helper->get_thumb_padding($img);
			$thumbDiv .= "<div><img src=\"{$img[0]}\" style=\"padding:{$padding};width:{$img[1]}px;height:{$img[2]}px;\"/></div>\n";
			$i++;
			if ($i >= $end) break;
		}
		$thumbDiv .= "</div>\n";

		$outStr .= 	$this->generate_containers($id, $size, $args, TRUE, '6' );
		$outStr = str_replace("<div class='shiba-outer'",$thumbDiv."\n<div class='shiba-outer'",$outStr);
		break;
		case '7':
			$outerW = 54 + 6;
		$jsStr .= "	
			//SAMPLE 7
			var startItem = 0; //or   0   or any
			var thumbs_mask{$shiba_gallery->nsNum} = document.id('thumbs_mask{$shiba_gallery->nsNum}').setStyle('left',(startItem*{$outerW}-570)+'px').set('opacity',0.3);
			var fxOptions{$shiba_gallery->nsNum} = {property:'left',duration:1000, transition:".$shiba_gallery->TRANSITION.", wait:false}
			var thumbsFx = new Fx.Tween(thumbs_mask{$shiba_gallery->nsNum},fxOptions{$shiba_gallery->nsNum});
			nS{$shiba_gallery->nsNum} = new noobSlide({
				box: document.id('$id'),
				items: $item_list,
				size: $size[0],
				handles: $$('#thumbs_handles{$shiba_gallery->nsNum} span'),
				fxOptions: fxOptions{$shiba_gallery->nsNum},
				onWalk: function(currentItem){
					thumbsFx.start(currentItem*{$outerW}-570);
				},
				startItem: startItem
			});
			//walk to first with fx
			nS{$shiba_gallery->nsNum}.walk(0);
		});
		";
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;
		case '8':
		$w = $size[0]; $h = $size[1];
	
		$jsStr .= "	
			//SAMPLE 8
			var handles{$shiba_gallery->nsNum}_more = $$('#handles{$shiba_gallery->nsNum}_more span');
			nS{$shiba_gallery->nsNum} = new noobSlide({
				box: document.id('$id'),
				items: $$('#$id h3'),
				size: $w,
	//			handles: $$('#handles{$shiba_gallery->nsNum} span'),
				addButtons: {	previous: document.id('prev{$shiba_gallery->nsNum}'), 
								play: document.id('play{$shiba_gallery->nsNum}'), 
								stop: document.id('stop{$shiba_gallery->nsNum}'), 
								playback: document.id('playback{$shiba_gallery->nsNum}'), 
								next: document.id('next{$shiba_gallery->nsNum}') },
				onWalk: function(currentItem,currentHandle){
					//style for handles
					$$(this.handles,handles{$shiba_gallery->nsNum}_more).removeClass('active');
					$$(currentHandle,handles{$shiba_gallery->nsNum}_more[this.currentIndex]).addClass('active');
					//text for 'previous' and 'next' default buttons
					document.id('prev{$shiba_gallery->nsNum}').set('html','&lt;&lt; '+this.items[this.previousIndex].innerHTML);
					document.id('next{$shiba_gallery->nsNum}').set('html',this.items[this.nextIndex].innerHTML+' &gt;&gt;');
				}
			});
			//more 'previous' and 'next' buttons
			nS{$shiba_gallery->nsNum}.addActionButtons('previous',$$('#noobslide{$shiba_gallery->nsNum} .prev'));
			nS{$shiba_gallery->nsNum}.addActionButtons('next',$$('#noobslide{$shiba_gallery->nsNum} .next'));
			//more handle buttons
			nS{$shiba_gallery->nsNum}.addHandleButtons(handles{$shiba_gallery->nsNum}_more);
			//walk to item 3 witouth fx
			nS{$shiba_gallery->nsNum}.walk(0,false,true);	
		});
		";
	
		$outStr .= 	$this->generate_containers($id, array($w,$h), $args, FALSE);
		break;
		
		case 'slideviewer':
		if ($args['caption'] != 'none')
			$jsStr .= "var info$shiba_gallery->nsNum = document.id('$id').getNext().set('opacity',{$this->caption_opacity});\n";

		$jsStr .= "	
			//SAMPLE 4-modified (walk to item)
			nS{$shiba_gallery->nsNum} = new noobSlide({
				box: document.id('$id'),
				items: $$('#$id span'),
				size: $size[0],
				handles: $$('#handles{$shiba_gallery->nsNum} span'),
	
				onWalk: function(currentItem,currentHandle){\n";
		if ($args['caption'] != 'none')
			$jsStr .="				
					info{$shiba_gallery->nsNum}.empty();
					new Element('h4').set('html',currentItem.getElement('img').getProperty('alt')).inject(info{$shiba_gallery->nsNum});\n";
		$jsStr .= "			
					this.handles.removeClass('active');
					currentHandle.addClass('active');
				}
	
			});
		});";
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;
		
		case 'galleria':
			if ($args['caption'] != 'none')
				$jsStr .= "var info$shiba_gallery->nsNum = document.id('$id').getNext().set('opacity',{$this->caption_opacity});\n";
			//SAMPLE 6 (on 'mouseenter' walk)
			$jsStr .= "
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $$('#$id span'),
				size: $size[0],
				handles: $$('#handles{$shiba_gallery->nsNum} div'),
				handle_event: 'click',
				addButtons: {
					previous: document.id('prev$shiba_gallery->nsNum'),
					play: document.id('play$shiba_gallery->nsNum'),
					stop: document.id('stop$shiba_gallery->nsNum'),
					next: document.id('next$shiba_gallery->nsNum')
				},
				button_event: 'click',
				fxOptions: {
					duration: 1000,
					transition: ".$shiba_gallery->TRANSITION.",
					wait: false
				},
	
				onWalk: function(currentItem,currentHandle){\n";
			if ($args['caption'] != 'none')
				$jsStr .= "
					info{$shiba_gallery->nsNum}.empty();
					new Element('h4').set('html',currentItem.getElement('img').getProperty('alt')).inject(info{$shiba_gallery->nsNum});\n";
			$jsStr .= "		
					this.handles.set('opacity',0.5);
					currentHandle.set('opacity',1);
				}
				
			});
		});";
		
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;		
	
		case 'thumb':
			if ($args['caption'] != 'none')
				$jsStr .= "var info$shiba_gallery->nsNum = document.id('$id').getNext().set('opacity',{$this->caption_opacity});\n";
			$jsStr .= "
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $$('#$id span'),
				size: $size[0],
				handles: $$('#handles{$shiba_gallery->nsNum} div'),
				handle_event: 'click',
	
				onWalk: function(currentItem,currentHandle){\n";
			if ($args['caption'] != 'none')
				$jsStr .= "
					info{$shiba_gallery->nsNum}.empty();
					new Element('h4').set('html',currentItem.getElement('img').getProperty('alt')).inject(info{$shiba_gallery->nsNum});\n";
			$jsStr .= "		
					this.handles.set('opacity',0.5);
					currentHandle.set('opacity',1);
				}
				
			});
		});\n";	
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;		
			
		case 'nativex':
		$w = $size[0]; $h = $size[1];
	
		$jsStr .= "	
			//SAMPLE 8 modified with thumbnails
			nS{$shiba_gallery->nsNum} = new noobSlide({
				box: document.id('$id'),
				items: $$('#$id h3'),
				size: $w,
				handles: $$('#handles{$shiba_gallery->nsNum} div'),
				addButtons: {	play: document.id('play{$shiba_gallery->nsNum}'), 
								stop: document.id('stop{$shiba_gallery->nsNum}'), 
								playback: document.id('playback{$shiba_gallery->nsNum}')
							}
			});
			//more 'previous' and 'next' buttons
			nS{$shiba_gallery->nsNum}.addActionButtons('previous',$$('#noobslide{$shiba_gallery->nsNum} .prev'));
			nS{$shiba_gallery->nsNum}.addActionButtons('next',$$('#noobslide{$shiba_gallery->nsNum} .next'));
		});
		";
	
		$outStr .= 	$this->generate_containers($id, array($w,$h), $args, FALSE);
		break;
	
		case '2':			
		$jsStr .= "
			//SAMPLE 2 (transition: Bounce.easeOut)
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $item_list,
				size: $size[0],
				interval: 3000,
				fxOptions: {
					duration: 1000,
					transition: ".$shiba_gallery->TRANSITION.",
					wait: false
				},
				addButtons: {
					previous: document.id('prev$shiba_gallery->nsNum'),
					play: document.id('play$shiba_gallery->nsNum'),
					stop: document.id('stop$shiba_gallery->nsNum'),
					next: document.id('next$shiba_gallery->nsNum')
				}
			});
		});
		";
		$outStr .= 	$this->generate_containers($id, $size, $args);
		break;

		default:
		$jsStr .= "
			//SAMPLE 2 (transition: Bounce.easeOut)
			nS$shiba_gallery->nsNum = new noobSlide({
				box: document.id('$id'),
				items: $item_list,
				size: $size[0],
				interval: 3000,
				fxOptions: {
					duration: 1000,
					transition: ".$shiba_gallery->TRANSITION.",
					wait: false
				},
				addButtons: {
					previous: document.id('prev$shiba_gallery->nsNum'),
					play: document.id('play$shiba_gallery->nsNum'),
					stop: document.id('stop$shiba_gallery->nsNum'),
					next: document.id('next$shiba_gallery->nsNum')
				}
			});
		});
		";
		$outStr = 	$this->generate_containers($id, $size, $args);
		$jsStr = apply_filters('shiba_js_noobslide', $jsStr, $size, $args, $images, $all_img, $noobnum);
		$outStr = apply_filters('shiba_open_noobslide', $outStr, $size, $args, $images, $all_img, $noobnum);
		break;		
		} // end switch
	
		$shiba_gallery->jsStr .= $jsStr;
		return $outStr;
	} // end open noobslide


	
	function close_noobslide($size, $tsize, $args, $images, $all_img, $noobnum) {
		global $shiba_gallery;
		
		$outStr = '';
		switch ($noobnum) {
		case '1':
			$outStr .= "</div></div></div>\n";
			break;
		case '3':
			$outStr .= "</div></div></div>\n";	
			$outStr .= "
					<p class='noobslide_buttons shiba-nav'>
						<span id='playback$shiba_gallery->nsNum'>&lt; Playback</span>
						<span id='stop$shiba_gallery->nsNum'>Stop</span>
						<span id='play$shiba_gallery->nsNum'>Play &gt;</span>
					</p>\n";
			break;
		case '4':
			$w = $size[0];
			$outStr .= "</div></div></div>\n";	
			$outStr .= "
				<h4>Show: <span id=\"noobslide_info$shiba_gallery->nsNum\" class=\"noobslide_info\"></span></h4>
				<p class=\"noobslide_numcontrol\" id=\"handles$shiba_gallery->nsNum\" style=\"width:{$w}px;\">\n";
			$i = 0;	
			foreach ( $images as $image ) {		
				$num = $i+1;
				$outStr .= "<span class=\"noobslide_numthumb\">{$num}</span>\n";
				$i++;
			}
			$outStr .= "</p>\n";
			$outStr .= "<div style='clear:left;'></div>\n";
			break;
		case '5':
			$outStr .= "</div>\n"; // close noobslide
			if ($args['caption'] != 'none')	
				$outStr .= "<div id=\"info$shiba_gallery->nsNum\" class=\"noobslide_info_overlay shiba-caption\" style=\"width:$size[0]px;\"></div>";
			$outStr .= "</div></div>\n"; // close noobmask
			$outStr .= "
			<p class=\"noobslide_buttons shiba-nav\">		
				<span id=\"prev$shiba_gallery->nsNum\">&lt;&lt; Previous</span>
				<span id=\"play$shiba_gallery->nsNum\">Play &gt;</span>
				<span id=\"stop$shiba_gallery->nsNum\">Stop</span>
				<span id=\"next$shiba_gallery->nsNum\">Next &gt;&gt;</span>
			</p>\n";
			break;
		case '6':
			$outStr .= "</div>\n"; // close noobslide	
			if ($args['caption'] != 'none')	
				$outStr .= "<div class=\"noobslide_info_overlay shiba-caption\" style=\"width:$size[0]px;\"></div>\n";
			$outStr .= "</div></div>\n"; // close noobmask
			$vthumb_w = $tsize[0] + 6;
			$outStr .= "<div class=\"noobslide_thumbs noobslide_vthumbs\" id=\"handles{$shiba_gallery->nsNum}_2\" style=\"width:{$vthumb_w}px\">\n";
			// draw second half of thumbnails
			$num_images = count($images);
			$start = intval(ceil($num_images * 0.5));
			$i = 0;
			foreach ($images as $image) {
				if ($i < $start) { $i++; continue; }
				$img = $shiba_gallery->helper->get_attachment_image_src($image->ID, $tsize);
				$padding = $shiba_gallery->helper->get_thumb_padding($img);
				$outStr .= "<div><img src=\"{$img[0]}\" style=\"padding:{$padding};width:{$img[1]}px;height:{$img[2]}px;\"/></div>\n";
				if ($i >= $num_images) break;
				else $i++;
			}
			$outStr .= "</div>\n";
			$outStr .= "
				<p class=\"noobslide_buttons shiba-nav\" style=\"clear:both;\">
					<span id=\"prev{$shiba_gallery->nsNum}\">&lt;&lt; Previous</span>
					<span id=\"playback{$shiba_gallery->nsNum}\">&lt;Playback</span>
					<span id=\"stop{$shiba_gallery->nsNum}\">Stop</span>
					<span id=\"play{$shiba_gallery->nsNum}\">Play &gt;</span>
					<span id=\"next{$shiba_gallery->nsNum}\">Next &gt;&gt;</span>
				</p>\n";
			break;
		case '7':
			$elementW = 54; // use original here because the mask graphic is built for 54x41
			$elementH = 41;
			$outerW =  $elementW + 6;
			$outStr .= "</div></div></div>\n";
			$outStr .= "<div style=\"clear:both;height:20px;\"></div>\n";	
			$outStr .= "<div class=\"noobslide_thumb_overlay\" style=\"height:{$elementH}px;\">\n";
			$outStr .= "<div class=\"noobslide_thumbs\" style=\"height:{$elementH}px;\">\n";
			foreach ($images as $image) {
				$img = $shiba_gallery->helper->get_attachment_image_src($image->ID, array($elementW, $elementH));
				$pad_left = intval(ceil(($outerW-$img[1])*0.5));
				$outStr .= "<div style=\"width:{$outerW}px;height:{$elementH}px;\"><img src=\"{$img[0]}\" style=\"padding:0px {$pad_left}px 0px {$pad_left}px;width:{$img[1]}px;height:{$img[2]}px;\"/></div>\n";
			}
			$outStr .= "</div>\n";
	
			$outStr .= "<div id=\"thumbs_mask{$shiba_gallery->nsNum}\" class=\"noobslide_thumbs_mask\" style=\"width:1200px;height:{$elementH}px;background:url('".SHIBA_GALLERY_URL."/noobslide/thumbs_mask.gif') no-repeat center top;\"></div>\n";
	
			$outStr .= "<p id=\"thumbs_handles{$shiba_gallery->nsNum}\" class=\"noobslide_thumbs_handles\" style=\"height:{$elementH}px;\">\n";
			foreach ( $all_img as $img ) {		
				$outStr .= "<span style=\"width:{$outerW}px;height:{$elementH}px;\"></span>\n";
			}
			$outStr .= "</p></div>\n"; // End thumbs7
			break;
		case '8':
			$w = $size[0];	
			$outStr .= "</div></div></div>\n";
			$outStr .= "
			<p class=\"noobslide_buttons shiba-nav\">
			<span id=\"prev{$shiba_gallery->nsNum}\">&lt;&lt; Previous</span> | <span id=\"next{$shiba_gallery->nsNum}\">Next &gt;&gt;</span>
			</p>
	
			<p class=\"noobslide_buttons shiba-nav\">
				<span id=\"playback{$shiba_gallery->nsNum}\">&lt;Playback</span>
				<span id=\"stop{$shiba_gallery->nsNum}\">Stop</span>
				<span id=\"play{$shiba_gallery->nsNum}\">Play &gt;</span>
			</p>
			";
			$outStr .= "<div id=\"handles{$shiba_gallery->nsNum}_more\" class=\"noobslide_numcontrol\" style=\"width:{$w}px;\">\n";
			$i = 0;	
			foreach ( $images as $image ) {		
				$num = $i+1;
				$outStr .= "<span class=\"noobslide_numthumb\">{$num}</span>\n";
				$i++;
			}
			$outStr .= "</div>\n";
			$outStr .= "<div style='clear:left;'></div>\n";
			break;
		case 'slideviewer':
			$outStr .= "</div>\n"; // close noobslide	
			if ($args['caption'] != 'none')	
				$outStr .= "<div class=\"noobslide_info_overlay shiba-caption\" style=\"width:$size[0]px;\"></div>\n";
			$outStr .= "</div></div>\n"; // close noobmask
			$outStr .= "<p class=\"noobslide_numcontrol\" id=\"handles$shiba_gallery->nsNum\" style=\"width:$size[0]px;\">\n";
			$i = 0;	
			foreach ( $images as $image ) {
				$num = $i+1;		
				$outStr .= "<span class=\"noobslide_numthumb\">{$num}</span>\n";
				$i++;
			}
			$outStr .= "</p>\n";
			$outStr .= "<div style='clear:left;'></div>\n";
			break;
		case 'galleria':
		case 'thumb':
			$outStr .= "</div>\n"; // close noobslide	
			if ($args['caption'] != 'none')	
				$outStr .= "<div class=\"noobslide_info_overlay shiba-caption\" style=\"width:$size[0]px;\"></div>\n";
			$outStr .= "</div></div>\n"; // close noobmask
			$outStr .= "<div style=\"clear:both;\"></div>\n";
	
			if ($noobnum == 'galleria') {
			$outStr .= "
				<p class=\"noobslide_buttons shiba-nav\" style=\"clear:both;\">
					<span id=\"prev{$shiba_gallery->nsNum}\">&lt;&lt; Previous</span>
					<span id=\"stop{$shiba_gallery->nsNum}\">Stop</span>
					<span id=\"play{$shiba_gallery->nsNum}\">Play &gt;</span>
					<span id=\"next{$shiba_gallery->nsNum}\">Next &gt;&gt;</span>
				</p>\n";
			} else $outStr .= "<div style='height:10px;'></div>\n";	
			
			// get maxw amd maxh of thumbnails
			$all_img = array();
			$maxW = 0; $maxH = 0;
			
			foreach ( $images as $image ) {		
				$img = $shiba_gallery->helper->get_attachment_image_src($image->ID, $tsize);			
				$all_img[] = $img;
	
				$w = intval($img[1]); $h = intval($img[2]);
				if ($w > $maxW) $maxW = $w;
				if ($h > $maxH) $maxH = $h;
			}
			
	
			$outStr .= "<div id=\"handles{$shiba_gallery->nsNum}\" class=\"noobslide_thumbs\" style=\"width:{$size[0]}px;\">\n";
			// draw thumbnails
			foreach ($all_img as $img) {
				$padding = $shiba_gallery->helper->get_padding(array($maxW,$maxH), $img);
	
				$outStr .= "<div style=\"float:left;\"><img src=\"{$img[0]}\" style=\"padding:{$padding};width:{$img[1]}px;height:{$img[2]}px;\"/></div>\n";
			}
			$outStr .= "</div>\n";
			$outStr .= "<div style='clear:left;'></div>\n";
			break;		
		case 'nativex':
			$outStr .= "</div></div></div>\n";
			$outStr .= "
	
			<p class=\"noobslide_buttons shiba-nav\">
				<span id=\"playback{$shiba_gallery->nsNum}\">&lt;Playback</span>
				<span id=\"stop{$shiba_gallery->nsNum}\">Stop</span>
				<span id=\"play{$shiba_gallery->nsNum}\">Play &gt;</span>
			</p>
			";
	
			$w = $size[0]; 
			$outStr .= "<div id=\"handles{$shiba_gallery->nsNum}\" class=\"noobslide_thumbs\" style=\"width:{$w}px;\">\n";
			// draw thumbnails
			foreach ($images as $image) {
				$img = $shiba_gallery->helper->get_attachment_image_src($image->ID, $tsize);
				$imglink = $shiba_gallery->helper->get_attachment_link($image, $args['link']);
				$thumb_height = $tsize[1]+55; // 55 for text
	
				$padding = $shiba_gallery->helper->get_padding($tsize, $img);
	
				$outStr .= "<div style=\"float:left; width:{$tsize[0]}px; height:{$thumb_height}px; text-align:center;\">\n";
				$outStr .= "<img src=\"{$img[0]}\" style=\"padding:{$padding};width:{$img[1]}px;height:{$img[2]}px;\" />\n";
				$outStr .= "{$imglink}\n";
				$outStr .= "</div>\n";
			}
			$outStr .= "</div>\n";
			$outStr .= "<div style='clear:left;'></div>\n";
	
			break;
		case '2':
			$outStr .= "</div></div></div>\n";	
			$outStr .= "
					<p class='noobslide_buttons shiba-nav'>
						<span id='prev$shiba_gallery->nsNum'>&lt;&lt; Previous</span>
						<span id='play$shiba_gallery->nsNum'>Play &gt;</span>
						<span id='stop$shiba_gallery->nsNum'>Stop</span>
						<span id='next$shiba_gallery->nsNum'>Next &gt;&gt;</span>
					</p>\n";
			break;		
		default: // defaults to sample 2
			$closeStr = "</div></div></div>\n";	
			$closeStr .= "
					<p class='noobslide_buttons shiba-nav'>
						<span id='prev$shiba_gallery->nsNum'>&lt;&lt; Previous</span>
						<span id='play$shiba_gallery->nsNum'>Play &gt;</span>
						<span id='stop$shiba_gallery->nsNum'>Stop</span>
						<span id='next$shiba_gallery->nsNum'>Next &gt;&gt;</span>
					</p>\n";
			$outStr .= apply_filters('shiba_close_noobslide', $closeStr, $size, $args, $images, $all_img, $noobnum);
			break;
		}
		$outStr .= "</div><!-- Close noobmain -->\n"; // close noobmain		
		
		$shiba_gallery->nsNum++;
		return $outStr;		
	}	// end close noobslide
	
	

	function render($images, $args, $noobnum) {
		global $shiba_gallery;
		extract($args);
	
		$all_img = array(); $pimg_size = array();
		switch ($noobnum) {
		case '4':
		case '8':
		case 'nativex':
			$psize = $shiba_gallery->helper->get_panel_size($size, $pimg_size);
			$size_arr = $shiba_gallery->helper->get_gallery_size($images, $pimg_size, $all_img);
			$maxW = $psize[0]; $maxH = $psize[1];
			
			if (($noobnum == 'nativex') && ($tsize == 'auto') && ($psize[0] > 450)) {
				$tsize = 'thumbnail';
			}
			$tsize = $shiba_gallery->helper->get_thumb_size($tsize, $maxW, $maxH);
			$imgStr = $this->open_noobslide($psize, $tsize, $args, $images, $all_img, $noobnum);
			break;
		default:
			$size_arr = $shiba_gallery->helper->get_gallery_size($images, $size, $all_img);
			$maxW = $size_arr[0]; $maxH = $size_arr[1];
			$tsize = $shiba_gallery->helper->get_thumb_size($tsize, $maxW, $maxH);
			$imgStr = $this->open_noobslide($size_arr, $tsize, $args, $images, $all_img, $noobnum);
		}	
		
		$j = 0; 		
		foreach ( $images as $image ) {		
			$imglink = $shiba_gallery->helper->get_attachment_link($image, $link);
			$img_caption = $shiba_gallery->helper->get_caption($image, $caption, $link, '-');
			
			// Set the link to the attachment URL
			// wp_get_attachment_image or wp_get_attachment_link or wp_get_attachment_image_src
			$img = $all_img[$j]; $j++;
			$l_description = ''; 
			switch ($noobnum) {
			case '4':
				$w = $psize[0]; $h = $psize[1];
				$l_description = $shiba_gallery->helper->get_panel_text($image, $psize);
	
				$imgStr .= "<div class=\"noobpanel\" style='width:{$w}px;height:{$h}px;'>\n";
				$imgStr .= "<img src=\"$img[0]\" style=\"width:{$img[1]}px;height:{$img[2]}px;\" alt='$imglink'/>";
				$imgStr .= "<h3>$imglink</h3>\n";
				$imgStr .= "<p>$l_description</p>\n";
				$imgStr .= "</div>\n";
				break;
			case '8':
				$w = $psize[0]; $h = $psize[1];
				$l_description = $shiba_gallery->helper->get_panel_text($image, $psize);
	
				$imgStr .= "<div class=\"noobpanel\" style='width:{$w}px;height:{$h}px;'>\n";
	
				$imgStr .= "<p class=\"noobslide_buttons shiba-nav\">\n";
				$imgStr .= "<span class=\"prev\">&lt;&lt; Previous</span>\n";
				$imgStr .= "<span class=\"next\">Next &gt;&gt;</span>\n";
				$imgStr .= "</p>\n";
				$imgStr .= "<div style='clear:both;'></div>\n";
				
				$imgStr .= "<img src=\"{$img[0]}\" style=\"width:{$img[1]}px;height:{$img[2]}px;\" alt='{$imglink}'/>\n";
				$imgStr .= "<h3>$imglink</h3>\n";
				$imgStr .= "<p>$l_description</p>\n";
				$imgStr .= "</div>\n";
				break;
			case 'nativex':
				$w = $psize[0]; $h = $psize[1];
				$l_description = $shiba_gallery->helper->get_panel_text($image, $psize);
	
				$imgStr .= "<div class=\"noobpanel\" style='width:{$w}px;height:{$h}px;'>\n";
	
				$imgStr .= "<p class=\"noobslide_buttons shiba-nav\">\n";
				$imgStr .= "<span class=\"prev\">&lt;&lt; Previous</span>\n";
				$imgStr .= "<span class=\"next\">Next &gt;&gt;</span>\n";
				$imgStr .= "</p>\n";
				$imgStr .= "<div style='clear:both;'></div>\n";
				
				$imgStr .= "<img src=\"{$img[0]}\" style=\"width:{$img[1]}px;height:{$img[2]}px;\" alt='{$imglink}'/>\n";
				$imgStr .= "<h3>$imglink</h3>\n";
				$imgStr .= "<p>$l_description</p>\n";
				$imgStr .= "</div>\n";
				break;
	
			case '1':
			case '2':
			case '3':	
			case '5':
			case '6':
			case '7':
			case 'slideviewer':
			case 'galleria':
			case 'thumb':
				$padding = $shiba_gallery->helper->get_padding($size_arr, $img);
				$imgStr .= "<span style=\"width:{$img[1]}px;height:{$img[2]}px;padding:{$padding};\"><img src=\"$img[0]\" style=\"width:{$img[1]}px;height:{$img[2]}px;\" alt='$img_caption' /></span>\n";
				break;
			default:
				$padding = $shiba_gallery->helper->get_padding($size_arr, $img);
				$renderStr = "<span style=\"width:{$img[1]}px;height:{$img[2]}px;padding:{$padding};\"><img src=\"$img[0]\" style=\"width:{$img[1]}px;height:{$img[2]}px;\" alt='$img_caption' /></span>\n";
				$imgStr .= apply_filters('shiba_render_noobslide', $renderStr, $size_arr, $args, $image, $img, $noobnum);
				break;
	
			} // end switch
		} // end foreach

		switch ($noobnum) {
		case '4':
		case '8':
		case 'nativex':
			$imgStr .= $this->close_noobslide($psize, $tsize, $args, $images, $all_img, $noobnum);
			break;
		default:
			$imgStr .= $this->close_noobslide($size_arr, $tsize, $args, $images, $all_img, $noobnum);
		}	
		return $imgStr;
	} // end render noobslide
	
} // end class
endif;
?>