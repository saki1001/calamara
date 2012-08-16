<div id="slideshow">
    <div class="slideshow_container">
        <div class="slideshow"></div>
        <div class="border right">
            <a class="button next transparent"></a>
        </div>
        <div class="border left">
            <a class="button previous transparent"></a>
        </div>
    </div>
</div>

<div id="slideshow_text">
   <div class="text_container"></div>
</div>

<script type="text/javascript">
    var slideshow_images = <?php echo json_encode($images); ?>;
    var slideshow_settings = <?php echo json_encode($settings); ?>;
</script>

<?php if(!empty($printStyle)): ?>
    <style type="text/css">
        <?php echo $printStyle; ?>
    </style>
<?php endif; ?>
