<?php

if (!function_exists('wp_image_zoooom_settings')) {
function wp_image_zoooom_settings($type) {

    $l = 'wp-image-zoooom';

    $plugin = array(
        'plugin_name'       => 'WP Image Zoom',
        'plugin_file'       => str_replace('includes/settings.php', 'image-zoooom.php', __FILE__),
        'plugin_server'     => 'https://www.silkypress.com',
        'author'            => 'Diana Burduja',
        'testing'           => false,
    );
    if ($type == 'plugin') return $plugin;

    $settings = array(
        'lensShape' => array(
            'label' => __('Lens Shape', $l),
            'values' => array(
                'none' => array('<i class="icon-lens_shape_none"></i>', __('No Lens', $l)),
                'round' => array('<i class="icon-lens_shape_circle"></i>', __('Circle Lens', $l)),
                'square' => array('<i class="icon-lens_shape_square"></i>', __('Square Lens', $l)),
                'zoom_window' => array('<i class="icon-type_zoom_window"></i>', __('With Zoom Window', $l)),
            ),
            'value' => 'zoom_window',
            'input_form' => 'buttons',
            'style' => 'full',
        ),
        'cursorType' => array(
            'label' => __('Cursor Type', $l),
            'values' => array(
                'default' => array('<i class="icon-cursor_type_default"></i>', __('Default', $l ) ),
                'pointer' => array('<i class="icon-cursor_type_pointer"></i>', __('Pointer', $l ) ),
                'crosshair' => array('<i class="icon-cursor_type_crosshair"></i>', __('Crosshair', $l ) ),
                'zoom-in' => array('<i class="icon-zoom-in"></i>', __('Zoom', $l ) ),
            ),
            'value' => 'default',
            'input_form' => 'buttons',
            'style' => 'full',
        ),
        'zwEasing' => array(
            'label' => __('Animation Easing Effect', $l ),
            'value' => 12,
            'description' => __('A number between 0 and 200 to represent the degree of the Animation Easing Effect', $l ),
            'input_form' => 'input_text',
        ),

        'lensSize' => array(
            'label' => __('Lens Size', $l ),
            'post_input' => 'px',
            'value' => 200,
            'description' => __('For Circle Lens it means the diameters, for Square Lens it means the width', $l ),
            'input_form' => 'input_text',
        ),
        'borderThickness' => array(
            'label' => __('Border Thickness', $l ),
            'post_input' => 'px',
            'value' => 1,
            'input_form' => 'input_text',
        ),
        'borderColor' => array(
            'label' => __('Border Color', $l ),
            'value' => '#ffffff',
            'input_form' => 'input_color',
        ),
        'lensFade' => array(
            'label' => __('Fade Time', $l ),
            'post_input' => 'sec',
            'value' => 0.5,
            'description' => __('The amount of time it takes for the Lens to slowly appear or disappear', $l),
            'input_form' => 'input_text',
        ),
        'tint' => array(
            'label' => __('Tint', $l),
            'value' => true,
            'description' => __('A color that will layed on top the of non-magnified image in order to emphasize the lens', $l),
            'input_form' => 'checkbox',
        ),
        'tintColor' =>array(
            'label' => __('Tint Color', $l),
            'value' => '#ffffff',
            'input_form' => 'input_color',
        ),
        'tintOpacity' => array(
            'label' => __('Tint Opacity', $l),
            'value' => '0.1',
            'post_input' => '%',
            'input_form' => 'input_text',
        ),
        'zwWidth' => array(
            'label' => __('Zoom Window Width', $l),
            'post_input' => 'px',
            'value' => 400,
            'input_form' => 'input_text',
        ),
        'zwHeight' => array(
            'label' => __('Zoom Window Height', $l),
            'post_input' => 'px',
            'value' => 360,
            'input_form' => 'input_text',
        ),
        'zwPadding' => array(
            'label' => __('Distance from the Main Image', $l),
            'post_input' => 'px',
            'value' => 10,
            'input_form' => 'input_text',
        ),
        'zwBorderThickness' => array(
            'label' => __('Border Thickness', $l),
            'post_input' => 'px',
            'value' => 1,
            'input_form' => 'input_text',
        ),
        'zwShadow' => array(
            'label' => __('Shadow Thickness', $l),
            'post_input' => 'px',
            'value' => 4,
            'input_form' => 'input_text',
            'description' => __('Use 0px to remove the shadow', $l),
        ),
        'zwBorderColor' => array(
            'label' => __('Border Color', $l),
            'value' => '#888888',
            'input_form' => 'input_color',
        ),
        'zwBorderRadius' => array(
            'label' => __('Rounded Corners', $l),
            'post_input' => 'px',
            'value' => 0,
            'input_form' => 'input_text',
        ),
        'zwFade' => array(
            'label' => __('Fade Time', $l),
            'post_input' => 'sec',
            'value' => 0.5,
            'description' => __('The amount of time it takes for the Zoom Window to slowly appear or disappear', $l),
            'input_form' => 'input_text',
        ),
        'enable_woocommerce' => array(
            'label' => __('Enable the zoom on WooCommerce products', $l),
            'value' => true,
            'input_form' => 'checkbox',
        ),
        'exchange_thumbnails' => array(
            'label' => __('Exchange the thumbnail with main image on WooCommerce products', $l),
            'value' => true,
            'input_form' => 'checkbox',
            'description' => __('On a WooCommerce gallery, when clicking on a thumbnail, not only the main image will be replaced with the thumbnail\'s image, but also the thumbnail will be replaced with the main image', $l),
        ),
        'enable_mobile' => array(
            'label' => __('Enable the zoom on mobile devices', $l),
            'value' => false,
            'input_form' => 'checkbox',
            'description' => __('Tablets are also considered mobile devices'),
        ),
        'woo_cat' => array(
            'label' => __('Enable the zoom on WooCommerce category pages', $l),
            'value' => false,
            'input_form' => 'checkbox',
        ),

        'force_woocommerce' => array(
            'label' => __('Force it to work on WooCommerce', $l),
            'value' => true,
            'input_form' => 'checkbox',
        ),
    );
    if ($type == 'settings') return $settings;


    $pro_fields = array(
        'remove_lightbox_thumbnails' => array(
            'label' => __('Remove the Lightbox on thumbnail images', $l),
            'value' => false,
            'pro' => true,
            'input_form' => 'checkbox',
            'description' => __('Some themes implement a Lightbox for WooCommerce galleris that opens on click. Enabling this checkbox will remove the Lightbox on thumbnail images and leave it only on the main image', 'wp-image-zoooom'),
            'disabled' => true,
        ),
        'remove_lightbox' => array(
            'label' => __('Remove the Lightbox', $l),
            'value' => false,
            'pro' => true,
            'input_form' => 'checkbox',
            'description' => __('Some themes implement a Lightbox that opens on click on the image. Enabling this checkbox will remove the Lightbox'),
            'disabled' => true,
        ),
        'woo_variations' => array(
            'label' => __('Enable on WooCommerce variation products', $l),
            'value' => false,
            'pro' => true,
            'input_form' => 'checkbox',
            'disabled' => true,
        ),
        'force_attachments' => array(
            'label' => __('Enable on attachments pages', $l),
            'value' => false,
            'pro' => true,
            'input_form' => 'checkbox',
            'disabled' => true,
        ),
        'custom_class' => array(
            'label' => __('Apply zoom on this particular image(s)', $l),
            'value' => '',
            'pro' => true,
            'input_form' => 'input_text',
            'description' => __('CSS style selector(s) for identifying the image(s) on which to apply the zoom.', $l ),
            'disabled' => true,
        ),
        'flexslider' => array(
            'label' => __('FlexSlider container class', $l),
            'value' => '',
            'pro' => true,
            'input_form' => 'input_text',
            'disabled' => true,
        ),
        'owl' => array(
            'label' => __('<a href="https://www.silkypress.com/i/js-owl" target="_blank">Owl Carousel</a> container class', $l),
            'value' => '',
            'input_form' => 'input_text',
            'pro' => true,
            'description' => __('If the images are in a Owl Carousel gallery, then type in here the class of the div containing the Owl Carousel gallery', $l ),
            'disabled' => true,
        ),
        'flickity' => array(
            'label' => __('<a href="https://flickity.metafizzy.co/" target="_blank">Flickity Carousel</a> container class', $l),
            'value' => '',
            'input_form' => 'input_text',
            'pro' => true,
            'description' => __('If the images are in a Flickity Carousel gallery, then type in here the class of the div containing the Flickity Carousel gallery', $l ),
            'disabled' => true,
        ),
        'slick' => array(
            'label' => __('<a href="https://kenwheeler.github.io/slick/" target="_blank">Slick carousel</a> container class', $l),
            'value' => '',
            'input_form' => 'input_text',
            'description' => __('If the images are in a Slick carousel gallery, then type in here the class of the div containing the Slick carousel gallery', $l ),
            'pro' => true,
            'disabled' => true,
        ),
        'huge_it_gallery' => array(
            'label' => __('<a href="https://www.silkypress.com/i/wp-huge-it-gallery" target="_blank">Huge IT Gallery</a> id', $l),
            'value' => '',
            'pro' => true,
            'input_form' => 'input_text',
            'disabled' => true,
        ),
        'enable_lightbox_zoom' => array(
            'label' => __('Enable inside a Lightbox. <a href="#TB_inline?width=600&height=400&inlineId=supported-lightboxes" class="thickbox">See supported lightboxes</a>', $l),
            'value' => false,
            'pro' => true,
            'input_form' => 'checkbox',
            'disabled' => true,
        ),
        'onClick' => array(
            'label' => __('Enable the zoom on ...', $l),
            'values' => array(
                'false' => 'mouse hover',
                'true' => 'mouse click',
            ),
            'value' => 'false',
            'input_form' => 'radio',
            'pro' => true,
            'disabled' => true,
        ),
        'ratio' => array(
            'label' => __('Zoom Level', $l),
            'values' => array(
                'default' => array( '<i class="icon-zoom_level_default"></i>', __('Default', $l) ),
                '1.5' => array( '<i class="icon-zoom_level_15"></i>', __('1,5 times', $l) ),
                '2' => array( '<i class="icon-zoom_level_2"></i>', __('2 times', $l) ),
                '2.5' => array( '<i class="icon-zoom_level_25"></i>', __('2,5 times', $l) ),
                '3' => array( '<i class="icon-zoom_level_3"></i>', __('3 times', $l) ),
            ),
            'value' => 'default',
            'input_form' => 'buttons',
            'pro' => true,
            'style' => 'full',
            'disabled' => true,
        ),
        'lensColour' => array(
            'label' => __('Lens Color', $l ),
            'value' => '#ffffff',
            'pro' => true,
            'input_form' => 'input_color',
            'disabled' => true,
        ),
        'lensOverlay' => array(
            'label' => __('Show as Grid', $l ),
            'value' => false,
            'pro' => true,
            'input_form' => 'checkbox',
            'disabled' => true,
        ),
        'zwResponsive' => array(
            'label' => __('Responsive', $l),
            'input_form' => 'checkbox',
            'pro' => true,
            'value' => false,
            'disabled' => true,
        ),
        'zwResponsiveThreshold' => array(
            'label' => __('Responsive Threshold', $l),
            'pro' => true,
            'post_input' => 'px',
            'value' => '',
            'input_form' => 'input_text',
            'disabled' => true,
        ),
        'zwPositioning' => array(
            'label' => __('Positioning', $l),
            'values' => array(
                'right_top' => array('<i class="icon-type_zoom_window_right_top"></i>', __('Right Top', $l)),
                'right_bottom' => array('<i class="icon-type_zoom_window_right_bottom"></i>', __('Right Bottom', $l)),
                'right_center' => array('<i class="icon-type_zoom_window_right_center"></i>', __('Right Center', $l)),
                'left_top' => array('<i class="icon-type_zoom_window_left_top"></i>', __('Left Top', $l)),
                'left_bottom' => array('<i class="icon-type_zoom_window_left_bottom"></i>', __('Left Bottom', $l)),
                'left_center' => array('<i class="icon-type_zoom_window_left_center"></i>', __('Left Center', $l)),
            ),
            'pro' => true,
            'value' => '',
            'disabled' => true,
            'input_form' => 'buttons',
            'style' => 'full',
            'disabled' => true,
        ),
        'mousewheelZoom' => array(
            'label' => __('Mousewheel Zoom', $l),
            'value' => '',
            'pro' => true,
            'input_form' => 'checkbox',
            'disabled' => true,
        ),
        /*
        'customText' => array(
            'label' => __('Text on the image', $l),
            'value' => __('', $l),
            'input_form' => 'input_text',
            'pro' => true,
            'disabled' => true,
        ),
        'customTextSize' => array(
            'label' => __('Text Size', $l),
            'post_input' => 'px',
            'value' => '',
            'input_form' => 'input_text',
            'pro' => true,
            'disabled' => true,
        ),
        'customTextColor' => array(
            'label' => __('Text Color', $l),
            'value' => '',
            'input_form' => 'input_color',
            'pro' => true,
            'disabled' => true,
        ),
        'customTextAlign' => array(
            'label' => __('Text Align', $l),
            'values' => array(
                'top_left' => array('<i class="icon-text_align_top_left"></i>', __('Top Left', $l ) ),
                'top_center' => array('<i class="icon-text_align_top_center"></i>', __('Top Center', $l ) ),
                'top_right' => array('<i class="icon-text_align_top_right"></i>', __('Top Right', $l ) ),
                'bottom_left' => array('<i class="icon-text_align_bottom_left"></i>', __('Bottom Left', $l ) ),
                'bottom_center' => array('<i class="icon-text_align_bottom_center"></i>', __('Bottom Center', $l ) ),
                'bottom_right' => array('<i class="icon-text_align_bottom_right"></i>', __('Bottom Right', $l ) ),
            ),
            'value' => '',
            'input_form' => 'buttons',
            'pro' => true,
            'style' => 'full',
            'disabled' => true,
        ),
         */


    );
    if ($type == 'pro_fields') return $pro_fields;

}
}

?>
