<? if(count($attachments) <= 0):
	_e('Add slides to this slideshow by attaching images to it from the media page.', 'slideshow-plugin'); ?>

<? else: ?>
	<table class="wp-list-table widefat fixed media" cellspacing="0">
		<tbody id="the-list">

			<? foreach($attachments as $attachment):?>
				<? $editUrl = admin_url() . '/media.php?attachment_id=' . $attachment->ID . '&amp;action=edit'; ?>
				<? $image = wp_get_attachment_image_src($attachment->ID); ?>
				<? if(!$image[3]) $image[0] = $noPreviewIcon; ?>

				<tr id="post-<? echo $attachment->ID; ?>" class="alternate author-self status-inherit" valign="top">

					<td class="column-icon media-icon">
						<a href="<? echo $editUrl; ?>" title="Edit &#34;<? echo $attachment->post_title; ?>&#34;">
							<img
								width="80"
								height="60"
								src="<? echo $image[0]; ?>"
								class="attachment-80x60"
								alt="<? echo $attachment->post_title; ?>"
								title="<? echo $attachment->post_title; ?>"
							/>
						</a>
					</td>

					<td class="title column-title">
						<strong>
							<a href="<? echo $editUrl; ?>" title="Edit &#34;<? echo $attachment->post_title; ?>&#34;"><? echo $attachment->post_title; ?></a>
						</strong>

						<p>
							<? echo $attachment->post_content; ?>
						</p>
					</td>
				</tr>

			<? endforeach; ?>

		</tbody>
	</table>
<? endif; ?>