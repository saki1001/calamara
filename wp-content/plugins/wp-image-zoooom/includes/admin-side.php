<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * ImageZoooom_Admin 
 */
class ImageZoooom_Admin {

    public $messages = array();
    private $tab = 'general';
    public $plugin; 

    /**
     * Constructor
     */
    public function __construct() {

        if ( ! function_exists( 'is_plugin_active' ) ) {
            require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        } 

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
        add_action( 'admin_head', array( $this, 'iz_add_tinymce_button' ) );
        $this->plugin = wp_image_zoooom_settings('plugin'); 
        $this->warnings();
    }

    /**
     * Add menu items
     */
    public function admin_menu() {
        add_menu_page(
            __( 'WP Image Zoom', 'wp-image-zoooom' ),
            __( 'WP Image Zoom', 'wp-image-zoooom' ),
            'administrator',
            'zoooom_settings',
            array( $this, 'admin_settings_page' ),
            IMAGE_ZOOM_URL . 'assets/images/icon.svg'
        );
    }

    /**
     * Load the javascript and css scripts
     */
    public function admin_enqueue_scripts( $hook ) {
        if ( $hook != 'toplevel_page_zoooom_settings' )
            return false;

        $url = IMAGE_ZOOM_URL. 'assets/';
        $frm_url = IMAGE_ZOOM_URL. 'includes/frm/assets/';
        $v = IMAGE_ZOOM_VERSION;

        // Register the javascript files
        if ( $this->plugin['testing'] == true ) {
            wp_register_script( 'bootstrap', $frm_url. 'bootstrap.3.2.0.min.js' , array( 'jquery' ), $v, true  );
            wp_register_script( 'image_zoooom', $url.'js/jquery.image_zoom.js' , array( 'jquery' ), $v, true );
            if ( !isset($_GET['tab']) || $_GET['tab'] == 'settings' ) {
                wp_register_script( 'zoooom-settings', $url. 'js/image_zoom.settings.free.js', array( 'image_zoooom' ), $v, true );
            }
        } else {
            wp_register_script( 'bootstrap', $frm_url.'bootstrap.3.2.0.min.js', array( 'jquery' ), $v, true  );
            wp_register_script( 'image_zoooom', $url.'js/jquery.image_zoom.min.js', array( 'jquery' ), $v, true );
            if ( !isset($_GET['tab']) || $_GET['tab'] == 'settings' ) {
                wp_register_script( 'zoooom-settings', $url.'js/image_zoom.settings.min.js', array( 'image_zoooom' ), $v, true );
            }
        }

        // Enqueue the javascript files
        wp_enqueue_script( 'bootstrap' );
        wp_enqueue_script( 'image_zoooom' );
        wp_enqueue_script( 'zoooom-settings' );

        // Register the css files
        wp_register_style( 'bootstrap', $frm_url.'bootstrap.min.css', array(), $v);
        if ( $this->plugin['testing'] == true ) {
            wp_register_style( 'zoooom', $url.'css/style.css', array(), $v);
        } else {
            wp_register_style( 'zoooom', $url.'css/style.min.css', array(), $v);
        }

        // Enqueue the css files
        wp_enqueue_style( 'bootstrap' );
        wp_enqueue_style( 'zoooom' );
    }

    /**
     * Build an array with settings that will be used in the form
     * @access public
     */
    public function get_settings( $id  = '' ) {
        $settings = wp_image_zoooom_settings('settings'); 
        $pro_fields = wp_image_zoooom_settings('pro_fields'); 

        $settings = array_merge( $settings, $pro_fields );

        if ( isset( $settings[$id] ) ) {
            $settings[$id]['name'] = $id;
            return $settings[$id];
        } elseif ( empty( $id ) ) {
            return $settings;
        }
        return false;
    }

    /**
     * Output the admin page
     * @access public
     */
    public function admin_settings_page() {

        if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'general' ) {
            if ( ! empty( $_POST ) ) {
                check_admin_referer('iz_general');
                $new_settings = $this->validate_general( $_POST );
                update_option( 'zoooom_general', $new_settings );
                $this->add_message( 'success', '<b>'.__('Your settings have been saved.', 'wp-image-zoooom') . '</b>' );
            }

            $template = IMAGE_ZOOM_PATH . "/includes/image-zoom-admin-general.php";
            load_template( $template );

            $this->tab = 'general';

            return;
        }

        if ( ! empty( $_POST ) ) {
            check_admin_referer('iz_template');
            $new_settings = $this->validate_settings( $_POST );
            // $new_settings_js = $this->generate_js_settings( $new_settings );
            update_option( 'zoooom_settings', $new_settings );
            // update_option( 'zoooom_settings_js', $new_settings_js );
            $this->add_message( 'success', '<b>'.__('Your settings have been saved.', 'wp-image-zoooom') . '</b>' );
        }

        $template = IMAGE_ZOOM_PATH . "/includes/image-zoom-admin-template.php";
        load_template( $template );

        $this->tab = 'settings';
    }

    /**
     * Build the jquery.image_zoom.js options and save them directly in the database
     * @access private
     */
    /*
    private function generate_js_settings( $settings ) {
        $options = array();
        switch ( $settings['lensShape'] ) {
            case 'none' : 
                $options[] = 'zoomType : "inner"';
                $options[] = 'cursor: "'.$settings['cursorType'].'"';
                $options[] = 'easingAmount: '.$settings['zwEasing'];
                break;
            case 'square' :
            case 'round' :
                $options[] = 'lensShape     : "' .$settings['lensShape'].'"';
                $options[] = 'zoomType      : "lens"';
                $options[] = 'lensSize      : "' .$settings['lensSize'].'"';
                $options[] = 'borderSize    : "' .$settings['borderThickness'].'"'; 
                $options[] = 'borderColour  : "' .$settings['borderColor'].'"';
                $options[] = 'cursor        : "' .$settings['cursorType'].'"';
                $options[] = 'lensFadeIn    : "' .$settings['lensFade'].'"';
                $options[] = 'lensFadeOut   : "' .$settings['lensFade'].'"';
                if ( $settings['tint'] == true ) {
                    $options[] = 'tint     : true';
                    $options[] = 'tintColour:  "' . $settings['tintColor'] . '"';
                    $options[] = 'tintOpacity:  "' . $settings['tintOpacity'] . '"';
                }
 
                break;
            case 'square' :
                break;
            case 'zoom_window' :
               $options[] = 'lensShape       : "square"';
               $options[] = 'lensSize        : "' .$settings['lensSize'].'"'; 
               $options[] = 'lensBorderSize  : "' .$settings['borderThickness'].'"'; 
               $options[] = 'lensBorderColour: "' .$settings['borderColor'].'"'; 
               $options[] = 'borderRadius    : "' .$settings['zwBorderRadius'].'"'; 
               $options[] = 'cursor          : "' .$settings['cursorType'].'"';
               $options[] = 'zoomWindowWidth : "' .$settings['zwWidth'].'"';
               $options[] = 'zoomWindowHeight: "' .$settings['zwHeight'].'"';
               $options[] = 'zoomWindowOffsetx: "' .$settings['zwPadding'].'"';
               $options[] = 'borderSize      : "' .$settings['zwBorderThickness'].'"';
               $options[] = 'borderColour    : "' .$settings['zwBorderColor'].'"';
               $options[] = 'zoomWindowShadow : "' .$settings['zwShadow'].'"';
               $options[] = 'lensFadeIn      : "' .$settings['lensFade'].'"';
               $options[] = 'lensFadeOut     : "' .$settings['lensFade'].'"';
               $options[] = 'zoomWindowFadeIn  :"' .$settings['zwFade'].'"';
               $options[] = 'zoomWindowFadeOut :"' .$settings['zwFade'].'"';
               $options[] = 'easingAmount  : "'.$settings['zwEasing'].'"';
                if ( $settings['tint'] == true ) {
                    $options[] = 'tint     : true';
                    $options[] = 'tintColour:  "' . $settings['tintColor'] . '"';
                    $options[] = 'tintOpacity:  "' . $settings['tintOpacity'] . '"';
                }

                break;
        }
        if (count($options) == 0) return false;

        $options = implode(', ', $options);

        return $options;
    }
     */


    /**
     * Check the validity of the settings. The validity has to be the same as the javascript validation in image-zoom.settings.js
     * @access public
     */
    public function validate_settings( $post ) {
        $settings = $this->get_settings();

        $new_settings = array();
        foreach ( $settings as $_key => $_value ) {
            if ( isset( $post[$_key] ) && $post[$_key] != $_value['value'] ) {
                $new_settings[$_key] = $post[$_key]; 
            } else {
                $new_settings[$_key] = $_value['value'];
            } 
        }

        $new_settings['lensShape'] = $this->validateValuesSet('lensShape', $new_settings['lensShape']);
        $new_settings['cursorType'] = $this->validateValuesSet('cursorType', $new_settings['cursorType']);
        $new_settings['zwEasing'] = $this->validateRange('zwEasing', $new_settings['zwEasing'], 'int', 0, 200);
        $new_settings['lensSize'] = $this->validateRange('lensSize', $new_settings['lensSize'], 'int', 20, 2000);
        $new_settings['borderThickness'] = $this->validateRange('borderThickness', $new_settings['borderThickness'], 'int', 0, 200);
        $new_settings['borderColor'] = $this->validateColor('borderColor', $new_settings['borderColor']);
        $new_settings['lensFade'] = $this->validateRange('lensFade', $new_settings['lensFade'], 'float', 0, 10);
        $new_settings['tint'] = $this->validateCheckbox('tint', $new_settings['tint']);
        $new_settings['tintColor'] = $this->validateColor('tintColor', $new_settings['tintColor']);
        $new_settings['tintOpacity'] = $this->validateRange('tintOpacity', $new_settings['tintOpacity'], 'float', 0, 1);
        $new_settings['zwWidth'] = $this->validateRange('zwWidth', $new_settings['zwWidth'], 'int', 0, 2000);
        $new_settings['zwHeight'] = $this->validateRange('zwHeight', $new_settings['zwHeight'], 'int', 0, 2000);
        $new_settings['zwPadding'] = $this->validateRange('zwPadding', $new_settings['zwPadding'], 'int', 0, 200 );
        $new_settings['zwBorderThickness'] = $this->validateRange('zwBorderThickness', $new_settings['zwBorderThickness'], 'int', 0, 200);
        $new_settings['zwBorderRadius'] = $this->validateRange('zwBorderRadius', $new_settings['zwBorderRadius'], 'int', 0, 500);
        $new_settings['zwShadow'] = $this->validateRange('zwShadow', $new_settings['zwShadow'], 'int', 0, 500);
        $new_settings['zwFade'] = $this->validateRange('zwFade', $new_settings['zwFade'], 'float', 0, 10);

        return $new_settings; 
    }

    public function validate_general( $post = null) {
        $settings = $this->get_settings();

        if( $post == null ) {
            return array(
                'enable_woocommerce' => true,
                'exchange_thumbnails' => true,
                'enable_mobile' => false,
                'woo_cat' => false,
                'force_woocommerce' => true,
            );
        }

        if ( ! isset( $post['enable_woocommerce'] ) ) 
            $post['enable_woocommerce'] = false;
        if ( ! isset( $post['exchange_thumbnails'] ) ) 
            $post['exchange_thumbnails'] = false;
        if ( ! isset( $post['enable_mobile'] ) ) 
            $post['enable_mobile'] = false;
        if ( ! isset( $post['woo_cat'] ) ) 
            $post['woo_cat'] = false;
        if ( ! isset( $post['force_woocommerce'] ) ) 
            $post['force_woocommerce'] = false;

        $new_settings = array(
            'enable_woocommerce' => $this->validateCheckbox('enable_woocommerce', $post['enable_woocommerce']),
            'exchange_thumbnails' => $this->validateCheckbox('exchange_thumbnails', $post['exchange_thumbnails']),
            'enable_mobile' => $this->validateCheckbox('enable_mobile', $post['enable_mobile']),
            'woo_cat' => $this->validateCheckbox('woo_cat', $post['woo_cat']),
            'force_woocommerce' => $this->validateCheckbox('force_woocommerce', $post['force_woocommerce']),
        );

        return $new_settings;
    }

    /**
     * Helper to validate a checkbox
     * @access private
     */
    private function validateCheckbox( $id, $value ) {
        $settings = $this->get_settings();

        if ( $value == 'on' ) $value = true;

        if ( !is_bool($value) ) {
            $value = $settings[$id]['value'];
            $this->add_message('info', __('Unrecognized <b>'.$settings[$id]['label'].'</b>. The value was reset to default', 'wp-image-zoooom') );
        } else {
        }
        return $value;
    }

    /**
     * Helper to validate a color
     * @access private
     */
    private function validateColor( $id, $value ) {
        $settings = $this->get_settings();

        if ( !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value) ) {
            $value = $settings[$id]['value'];
            $message = __('Unrecognized <b>%1$s</b>. The value was reset to <b>%2$s</b>', 'wp-image-zoooom');
            $message = wp_kses($message, array('b' => array()));
            $message = sprintf($message, $settings[$id]['label'], $settings[$id]['value']);
            $this->add_message('info', $message);
        }
        return $value;
    }

    /**
     * Helper to validate the value out of a set of values
     * @access private
     */
    private function validateValuesSet( $id, $value ) {
        $settings = $this->get_settings();

        if ( !array_key_exists($value, $settings[$id]['values']) ) {
            $value = $settings[$id]['value'];
            $message = __('Unrecognized <b>%1$s</b>. The value was reset to <b>%2$s</b>', 'wp-image-zoooom');
            $message = wp_kses($message, array('b' => array()));
            $message = sprintf($message, $settings[$id]['label'], $settings[$id]['value']);
            $this->add_message('info', $message);
        }
        return $value;
    }

    /**
     * Helper to validate an integer of a float
     * @access private
     */
    private function validateRange( $id, $value, $type, $min, $max ) {
        $settings = $this->get_settings();

        if ( $type == 'int' ) $new_value = (int)$value;
        if ( $type == 'float' ) $new_value = (float)$value;

        if ( !is_numeric($value) || $new_value < $min || $new_value > $max ) {
            $new_value = $settings[$id]['value'];
            $message = __('<b>%1$s</b> accepts values between %2$s and %3$s. Your value was reset to <b>%4$s</b>', 'wp-image-zoooom');
            $message = wp_kses($message, array('b' => array()));
            $message = sprintf($message, $settings[$id]['label'], $settings[$id]['value']);
            $this->add_message('info', $message);
        }
        return $new_value;
    }


    /**
     * Add a message to the $this->messages array
     * @type    accepted types: success, error, info, block
     * @access private
     */
    private function add_message( $type = 'success', $text ) {
        global $comment;
        $messages = $this->messages;
        $messages[] = array('type' => $type, 'text' => $text);
        $comment[] = array('type' => $type, 'text' => $text);
        $this->messages = $messages;
    }

    /**
     * Output the form messages
     * @access public
     */
    public function show_messages() {
        global $comment;
        if ( !$comment || sizeof( $comment ) == 0 ) return;
        $output = '';
        foreach ( $comment as $message ) {
            $output .= '<div class="alert alert-'.$message['type'].'">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  '. $message['text'] .'</div>';
        }
        return $output;
    }


    /**
     * Add a button to the TinyMCE toolbar
     * @access public
     */
    function iz_add_tinymce_button() {
        global $typenow;

        if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
            return;
        }

        $allowed_types = array( 'post', 'page' );

        if ( defined('LEARNDASH_VERSION') ) {
            $learndash_types = array( 'sfwd-courses', 'sfwd-lessons', 'sfwd-topic', 'sfwd-quiz', 'sfwd-certificates', 'sfwd-assignment'); 
            $allowed_types = array_merge( $allowed_types, $learndash_types );

        }
        /*
        if( ! in_array( $typenow, $allowed_types ) )
            return;
         */

        if ( isset( $_GET['page'] ) && $_GET['page'] == 'wplister-templates' ) 
            return;

        if ( get_user_option('rich_editing') != 'true') 
            return;

        add_filter('mce_external_plugins', array( $this, 'iz_add_tinymce_plugin' ) );
        add_filter('mce_buttons', array( $this, 'iz_register_tinymce_button' ) );
    }

    /**
     * Register the plugin with the TinyMCE plugins manager
     * @access public
     */
    function iz_add_tinymce_plugin($plugin_array) {
        $plugin_array['image_zoom_button'] = IMAGE_ZOOM_URL . 'assets/js/tinyMCE-button.js'; 
        return $plugin_array;
    }

    /**
     * Register the button with the TinyMCE manager
     */
    function iz_register_tinymce_button($buttons) {
        array_push($buttons, 'image_zoom_button');
        return $buttons;
    }


    /**
     * Show admin warnings
     */
    function warnings() {
        
        require_once( 'frm/warnings.php' );

        $allowed_actions = array(
            'iz_dismiss_ajax_product_filters',
            'iz_dismiss_jetpack',
            'iz_dismiss_bwp_minify',
            'iz_dismiss_avada',
            'iz_dismiss_shopkeeper',
            'iz_dismiss_bridge',
            'iz_dismiss_wooswipe',
        );

        $w = new SilkyPress_Warnings($allowed_actions); 

        if ( !$w->is_url('zoooom_settings') ) {
            return;
        }

        // Warning about AJAX product filter plugins
        $this->iz_dismiss_ajax_product_filters($w);


        // Check if Jetpack Photon module is active
        if ( defined('JETPACK__VERSION' ) && Jetpack::is_module_active( 'photon' ) ) { 
            $message = __( 'WP Image Zoom plugin is not compatible with the <a href="admin.php?page=jetpack">Jetpack Photon</a> module. If you find that the zoom is not working, try to deactivate the Photon module and see if that solves it.', 'wp-image-zoooom-pro' );
            $w->add_notice( 'iz_dismiss_jetpack', $message );
        }

        // Warning about BWF settings 
        if ( is_plugin_active( 'bwp-minify/bwp-minify.php' ) ) { 
            $message = __( '<b>If the zoom does not show up</b> on your website, it could be because you need to add the “image_zoooom-init” and the “image_zoooom” to the “Scripts to NOT minify” option in the BWP Minify settings, as shown in <a href="https://www.silkypress.com/wp-content/uploads/2016/09/image-zoom-bwp.png" target="_blank">this screenshot</a>.', 'wp-image-zoooom-pro' );
            $w->add_notice( 'iz_dismiss_bwp_minify', $message );
        }


        // Check if the Avada theme is active
        if ( strpos( strtolower(get_template()), 'avada') !== false && is_plugin_active('woocommerce/woocommerce.php')) { 
            $flexslider_url = 'https://woocommerce.com/flexslider/';
            $pro_url = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
            $message = sprintf( __( 'The WP Image Zoom plugin <b>will not work</b> on the WooCommerce products gallery with the Avada theme. The Avada theme changes entirely the default WooCommerce gallery with the <a href="%1$s" target="_blank">Flexslider gallery</a> and the zoom plugin does not support the Flexslider gallery. Please check the <a href="%2$s" target="_blank">PRO version</a> of the plugin for compatibility with the Flexslider gallery.', 'wp-image-zoooom' ), $flexslider_url, $pro_url );
            $w->add_notice( 'iz_dismiss_avada', $message );
        }


        // Check if the Shopkeeper theme is active 
        if ( strpos( strtolower(get_template()), 'shopkeeper') !== false && is_plugin_active('woocommerce/woocommerce.php')) { 
            $pro_url = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
            $message = sprintf( __( 'The WP Image Zoom plugin <b>will not work</b> on the WooCommerce products gallery with the Shopkeeper theme. The Shopkeeper theme changes entirely the default WooCommerce gallery with a custom made gallery not supported by the free version of the WP Image Zoom plugin. Please check the <a href="%1$s" target="_blank">PRO version</a> of the plugin for compatibility with the Shopkeeper\'s gallery.', 'wp-image-zoooom' ), $pro_url );
            $w->add_notice( 'iz_dismiss_shopkeeper', $message, 'updated settings-error notice is-dismissible' );
        }


        // Check if the Bridge theme is active 
        if ( strpos( strtolower(get_template()), 'bridge') !== false && is_plugin_active('woocommerce/woocommerce.php')) { 
            $message = __( 'The <b>Bridge</b> theme replaces the default WooCommerce product gallery with its own. The <b>WP Image Zoom</b> plugin will not work with this replaced gallery. But if you set the "Enable Default WooCommerce Product Gallery Features" option to "Yes" on the <a href="'.admin_url( 'admin.php?page=qode_theme_menu_tab_woocommerce' ).'">WP Admin -> Qode Options -> WooCommerce</a> page, then the zoom will work as expected on the product gallery.', 'wp-image-zoooom' );
            // Note: This works for Bridge 16.7, but not for Bridge 14.1
            $w->add_notice( 'iz_dismiss_bridge', $message, 'updated settings-error notice is-dismissible' );
        }


        // Warning about WooSwipe plugin 
        if ( is_plugin_active( 'wooswipe/wooswipe.php' ) ) { 
            $pro_url = 'https://www.silkypress.com/wp-image-zoom-plugin/?utm_source=wordpress&utm_campaign=iz_free&utm_medium=banner';
            $wooswipe_url = 'https://wordpress.org/plugins/wooswipe/';
            $message = sprintf( __( 'WP Image Zoom plugin is <b>not compatible with the <a href="%1$s">WooSwipe WooCommerce Gallery</a> plugin</b>. You can try the zoom plugin with the default WooCommerce gallery by deactivating the WooSwipe plugin. Alternatively, you can upgrade to the WP Image Zoom Pro version, where the issue with the WooSwipe plugin is fixed.' ), $wooswipe_url, $pro_url);
            $w->add_notice( 'iz_dismiss_wooswipe', $message );
        }

        $w->show_warnings();
    }


    /**
     * Warning about AJAX product filter plugins
     */
    function iz_dismiss_ajax_product_filters($w) {
        $continue = false;

        $general = get_option('zoooom_general');
        if ( isset($_POST['tab'] )) {
            $general['woo_cat'] = (isset($_POST['woo_cat'])) ? true : false;
        }
        if ( ! isset($general['woo_cat']) || $general['woo_cat'] != true ) return false;

        if ( is_plugin_active( 'woocommerce-ajax-filters/woocommerce-filters.php' ) ) $continue = true;
        if ( is_plugin_active( 'load-more-products-for-woocommerce/load-more-products.php' ) ) $continue = true;
        if ( is_plugin_active( 'wc-ajax-product-filter/wcapf.php' ) ) $continue = true;

        if ( !$continue ) return false;

        $article_url = 'https://www.silkypress.com/wp-image-zoom/zoom-woocommerce-category-page-ajax/';
        $message = sprintf(__( 'You are using the zoom on WooCommerce shop pages in combination with a plugin that loads more products with AJAX (a product filter plugin or a "load more" products plugin). You\'ll notice that the zoom isn\'t applied after new products are loaded with AJAX. Please read <a href="%1$s" target="_blank">this article for a solution</a>.', 'wp-image-zoooom' ), $article_url);

        $w->add_notice( 'iz_dismiss_ajax_product_filters', $message );
    }





}


return new ImageZoooom_Admin();
