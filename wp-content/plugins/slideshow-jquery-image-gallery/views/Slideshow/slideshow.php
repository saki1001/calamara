<div class="slideshow_container">
	<div class="slideshow"></div>
	<div class="descriptionbox transparent"></div>
	<a class="button next transparent"></a>
	<a class="button previous transparent"></a>

	<script type="text/javascript">
		var slideshow_images = <? echo json_encode($images); ?>;
		var slideshow_settings = <? echo json_encode($settings); ?>;
	</script>

	<? if(!empty($printStyle)): ?>
		<style type="text/css">
			<? echo $printStyle; ?>
		</style>
	<? endif; ?>
</div>