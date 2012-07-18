<table border="0">
	<tr>
		<td><?php _e('Style', 'slideshow-plugin'); ?></td>
		<td>
			<select class="style-list" name="style">
				<?php foreach($styles as $key => $name): ?>
					<option value="<?php echo $key; ?>" <?php selected($settings['style'], $key); ?>><?php echo $name; ?></option>
				<?php endforeach; ?>
				<option value="custom-style" <?php selected($settings['style'], 'custom-style'); ?>><?php _e('Custom Style', 'slideshow-plugin') ?></option>
			</select>
		</td>
		<td><i><?php _e('The style used for this slideshow', 'slideshow-plugin'); ?></i></td>
	</tr>
</table>

<table border="0" class="custom-style">
	<tr>
		<td><strong><?php _e('Custom Style Editor', 'slideshow-plugin'); ?></strong></td>
		<td></td>
	</tr>
	<tr>
		<td><?php _e('Custom style', 'slideshow-plugin'); ?></td>
		<td><textarea rows="20" cols="60" class="custom-style-textarea" name="custom-style"><?php echo $settings['custom-style']; ?></textarea></td>
		<input type="hidden" class="custom-style-default-css-url" value="<?php  ?>" />
	</tr>
</table>

<style type="text/css">
	.custom-style{
		display: none;
	}
</style>