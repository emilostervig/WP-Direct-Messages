<?php
class WP_direct_messages {

	function __construct(){

	}

    public function enqueue_scripts(){
        wp_register_script('wp-direct-messages-js',  plugin_dir_path( __FILE__ ) . '/assets/js/wp-direct-messages-frontend.js', array('jquery'), WP_DIRECT_MESSAGES_VERSION, true );
        wp_localize_script( 'wp-direct-messages-js', 'script_data',
        array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
    public function enqueue_styles(){
        wp_enqueue_style( 'wp-direct-messages-css', plugin_dir_path( __FILE__ ) . '/assets/css/wp-direct-messages-frontend.css', array(), WP_DIRECT_MESSAGES_VERSION, 'all' )
    }

    public function run_hooks(){
        add_action('wp_enqueue_scripts' array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts' array($this, 'enqueue_styles'));
    }

    public function run(){
        $this->run_hooks();
    }

}
