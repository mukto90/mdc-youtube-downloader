<?php
class MDC_TinyMCE_buttons{
    
    public function __construct(){
        add_action( 'admin_enqueue_scripts', array($this, 'mdc_admin_enqueue_scripts'  ));
        add_action( 'admin_head', array($this, 'mdc_add_mce_button' ));
    }

    public function mdc_admin_enqueue_scripts() {
        wp_enqueue_script( 'mdc-yt-scipt', plugins_url('../js/admin.js',__FILE__));

        // localize script
        $wp_ulrs = array( 'yt_icon' => plugins_url('../images/icon.png',__FILE__) );
        wp_localize_script( 'mdc-yt-scipt', 'wp_ulrs', $wp_ulrs );
    }

    /*Hooks your functions into the correct filters*/
    public function mdc_add_mce_button() {
        if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
            return;
        }
        if ( 'true' == get_user_option( 'rich_editing' ) ) {
            add_filter( 'mce_external_plugins', array($this, 'mdc_add_tinymce_plugin' ));
            add_filter( 'mce_buttons', array($this, 'mdc_register_mce_button' ));
        }
    }

    /*Declare script for new button*/
    public function mdc_add_tinymce_plugin( $plugin_array ) {
        $plugin_array['my_mce_button_for_post'] = plugins_url('../js/tinymce-mdc-youtube-downloader.js',__FILE__);
        return $plugin_array;
    }

    /*Register new button in the editor*/
    public function mdc_register_mce_button( $buttons ) {
        array_push( $buttons, 'my_mce_button_for_post' );
        array_push( $buttons, 'my_mce_button_for_form' );
        return $buttons;
    }
}

new MDC_TinyMCE_buttons;