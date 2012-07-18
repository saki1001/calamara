<div class="slideshow_container">
	<div class="slideshow"></div>
	<div class="descriptionbox">
	   <div class="text_container"></div>
	</div>
	<a class="button next transparent"></a>
	<a class="button previous transparent"></a>

	<script type="text/javascript">
		var slideshow_images = <?php echo json_encode($images); ?>;
		var slideshow_settings = <?php echo json_encode($settings); ?>;
	</script>

	<?php if(!empty($printStyle)): ?>
		<style type="text/css">
			<?php echo $printStyle; ?>
		</style>
	<?php endif; ?>
</div>