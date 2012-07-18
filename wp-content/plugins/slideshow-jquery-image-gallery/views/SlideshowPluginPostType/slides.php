<p><?php echo $uploadButton; ?></p>

<?php if(count($attachments) <= 0): ?>
	<p><?php _e('Add slides to this slideshow by using the button above or attaching images from the media page.', 'slideshow-plugin'); ?></p>

<?php else: ?>
	<table class="wp-list-table widefat fixed media" cellspacing="0">
		<tbody id="the-list">

			<?php foreach($attachments as $attachment):?>
				<?php $editUrl = admin_url() . '/media.php?attachment_id=' . $attachment->ID . '&amp;action=edit'; ?>
				<?php $image = wp_get_attachment_image_src($attachment->ID); ?>
				<?php if(!$image[3]) $image[0] = $noPreviewIcon; ?>

				<tr id="post-<?php echo $attachment->ID; ?>" class="alternate author-self status-inherit" valign="top">

					<td class="column-icon media-icon">
						<a href="<?php echo $editUrl; ?>" title="Edit &#34;<?php echo $attachment->post_title; ?>&#34;">
							<img
								width="80"
								height="60"
								src="<?php echo $image[0]; ?>"
								class="attachment-80x60"
								alt="<?php echo $attachment->post_title; ?>"
								title="<?php echo $attachment->post_title; ?>"
							/>
						</a>
					</td>

					<td class="title column-title">
						<strong>
							<a href="<?php echo $editUrl; ?>" title="Edit &#34;<?php echo $attachment->post_title; ?>&#34;"><?php echo $attachment->post_title; ?></a>
						</strong>

						<p>
							<?php echo $attachment->post_content; ?>
						</p>
					</td>
				</tr>

			<?php endforeach; ?>

		</tbody>
	</table>
<?php endif; ?>