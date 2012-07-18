<table border="0">
	<tr>
		<td><?php _e('Number of seconds the slide takes to slide in', 'slideshow-plugin'); ?></td>
		<td><input type="text" name="slideSpeed" value="<?php echo $settings['slideSpeed']; ?>" size="5" /></td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php echo $defaultSettings['slideSpeed'] ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Number of seconds the description takes to slide in', 'slideshow-plugin'); ?></td>
		<td><input type="text" name="descriptionSpeed" value="<?php echo $settings['descriptionSpeed']; ?>" size="5" /></td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php echo $defaultSettings['descriptionSpeed'] ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Seconds between changing slides', 'slideshow-plugin'); ?></td>
		<td><input type="text" name="intervalSpeed" value="<?php echo $settings['intervalSpeed']; ?>" size="5" /></td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php echo $defaultSettings['intervalSpeed'] ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Width of the slideshow', 'slideshow-plugin'); ?></td>
		<td><input type="text" name="width" value="<?php echo $settings['width']; ?>" size="5" /></td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php echo $defaultSettings['width'] ?> - <?php _e('Defaults to parent\'s width.', 'slideshow-plugin'); ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Height of the slideshow', 'slideshow-plugin'); ?></td>
		<td><input type="text" name="height" value="<?php echo $settings['height']; ?>" size="5" /></td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php echo $defaultSettings['height'] ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Fit image into slideshow (stretching it)', 'slideshow-plugin'); ?></td>
		<td>
			<label><input type="radio" name="stretch" value="true" <?php checked($settings['stretch'], 'true'); ?> /> <?php _e('Yes', 'slideshow-plugin'); ?></label><br />
			<label><input type="radio" name="stretch" value="false" <?php checked($settings['stretch'], 'false'); ?> /> <?php _e('No', 'slideshow-plugin'); ?></label>
		</td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php if($defaultSettings['stretch'] == 'true') _e('Yes', 'slideshow-plugin'); else _e('No', 'slideshow-plugin'); ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Activate buttons (so the user can scroll through the slides)', 'slideshow-plugin'); ?></td>
		<td>
			<label><input type="radio" name="controllable" value="true" <?php checked($settings['controllable'], 'true'); ?> /> <?php _e('Yes', 'slideshow-plugin'); ?></label><br />
			<label><input type="radio" name="controllable" value="false" <?php checked($settings['controllable'], 'false'); ?> /> <?php _e('No', 'slideshow-plugin'); ?></label>
		</td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php if($defaultSettings['controllable'] == 'true') _e('Yes', 'slideshow-plugin'); else _e('No', 'slideshow-plugin'); ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Send user to image URL on click', 'slideshow-plugin'); ?></td>
		<td>
			<label><input type="radio" name="urlsActive" value="true" <?php checked($settings['urlsActive'], 'true'); ?> /> <?php _e('Yes', 'slideshow-plugin'); ?></label><br />
			<label><input type="radio" name="urlsActive" value="false" <?php checked($settings['urlsActive'], 'false'); ?> /> <?php _e('No', 'slideshow-plugin'); ?></label>
		</td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php if($defaultSettings['urlsActive'] == 'true') _e('Yes', 'slideshow-plugin'); else _e('No', 'slideshow-plugin'); ?></i></td>
	</tr>
	<tr>
		<td><?php _e('Show title and description', 'slideshow-plugin'); ?></td>
		<td>
			<label><input type="radio" name="showText" value="true" <?php checked($settings['showText'], 'true'); ?> /> <?php _e('Yes', 'slideshow-plugin'); ?></label><br />
			<label><input type="radio" name="showText" value="false" <?php checked($settings['showText'], 'false'); ?> /> <?php _e('No', 'slideshow-plugin'); ?></label>
		</td>
		<td><i><?php _e('Default', 'slideshow-plugin'); ?>: <?php if($defaultSettings['showText'] == 'true') _e('Yes', 'slideshow-plugin'); else _e('No', 'slideshow-plugin'); ?></i></td>
	</tr>
</table>