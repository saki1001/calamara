<p>
	<label for="<? echo $this->get_field_id('title'); ?>"><? _e('Title', 'slideshow-plugin'); ?></label>
	<input class="widefat" id="<? echo $this->get_field_id('title'); ?>" name="<? echo $this->get_field_name('title'); ?>" value="<? echo $instance['title']; ?>" style="width:100%" />
</p>

<p>
	<label for="<? echo $this->get_field_id('slideshowId'); ?>"><? _e('Slideshow', 'slideshow-plugin'); ?></label>
	<select class="widefat" id="<? echo $this->get_field_id('slideshowId'); ?>" name="<? echo $this->get_field_name('slideshowId'); ?>" value="<? echo $instance['slideshowId']; ?>" style="width:100%">
		<option value="-1" <? selected($instance['slideshowId'], -1); ?>><? _e('Random Slideshow', 'slideshow-plugin'); ?></option>
		<? foreach($slideshows as $slideshow): ?>
			<option value="<? echo $slideshow->ID ?>" <? selected($instance['slideshowId'], $slideshow->ID); ?>><? echo $slideshow->post_title ?></option>
		<? endforeach; ?>
	</select>
</p>