<table border="0">
	<tr>
		<td><? _e('Style', 'slideshow-plugin'); ?></td>
		<td>
			<select class="style-list" name="style">
				<? foreach($styles as $key => $name): ?>
					<option value="<? echo $key; ?>" <? selected($settings['style'], $key); ?>><? echo $name; ?></option>
				<? endforeach; ?>
				<option value="custom-style" <? selected($settings['style'], 'custom-style'); ?>><? _e('Custom Style', 'slideshow-plugin') ?></option>
			</select>
		</td>
		<td><i><? _e('The style used for this slideshow', 'slideshow-plugin'); ?></i></td>
	</tr>
</table>

<table border="0" class="custom-style">
	<tr>
		<td><strong><? _e('Custom Style Editor', 'slideshow-plugin'); ?></strong></td>
		<td></td>
	</tr>
	<tr>
		<td><? _e('Custom style', 'slideshow-plugin'); ?></td>
		<td><textarea rows="20" cols="60" class="custom-style-textarea" name="custom-style"><? echo $settings['custom-style']; ?></textarea></td>
		<input type="hidden" class="custom-style-default-css-url" value="<?  ?>" />
	</tr>
</table>

<style type="text/css">
	.custom-style{
		display: none;
	}
</style>