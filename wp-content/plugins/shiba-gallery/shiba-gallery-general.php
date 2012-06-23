<?php
if (!function_exists('is_admin')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}

if (!class_exists("Shiba_Gallery_General")) :

class Shiba_Gallery_General {
	
	// General base functions	
	function javascript_redirect($location) {
		// redirect after header here can't use wp_redirect($location);
		?>
		  <script type="text/javascript">
		  <!--
		  window.location= <?php echo "'" . $location . "'"; ?>;
		  //-->
		  </script>
		<?php
		exit;
	}

	function substring($str, $startPattern, $endPattern) {
			
		$pos = strpos($str, $startPattern);
		if($pos === false) {
			return "";
		}
	 
		$pos = $pos + strlen($startPattern);
		$temppos = $pos;
		$pos = strpos($str, $endPattern, $pos);
		$datalength = $pos - $temppos;
	 
		$data = substr($str, $temppos , $datalength);
		return $data;
	}
			
	function write_option($name, $value, $selected) {
		if ($selected && ($value == $selected)) 
			return "<option class='theme-option' value='" . $value . "' selected>" . $name . "</option>\n"; 
		else
			return "<option class='theme-option' value='" . $value . "'>" . $name . "</option>\n"; 
	}

	function write_array($name, $arr) {
		$i = 0; $size = count($arr)-1;
		$arrStr = "";
		$arrStr .= 'var '.$name.' = {';
		foreach ($arr as $key => $value) {
			if (is_string($arr[$key])) $arrStr .= "{$key}: \"$arr[$key]\"";
			else if (is_bool($arr[$key])) { $arrStr .= "{$key}:"; $arrStr .= ($arr[$key])?'true':'false'; }
			else $arrStr .= "{$key}: $arr[$key]";
			if ($i < $size) $arrStr .= ",\n"; $i++;
		}
		$arrStr .= "}\n";
		return $arrStr;
	}
	
} // end Shiba_General class
endif;

?>