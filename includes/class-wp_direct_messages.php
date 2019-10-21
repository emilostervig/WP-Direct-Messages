<?php
class WP_direct_messages {

	function __construct(){

	}

    public function enqueue_scripts(){
        wp_register_script('wp-direct-messages-js',  WP_DIRECT_MESSAGES_URL . 'assets/js/wp-direct-messages-frontend.js', array('jquery'), WP_DIRECT_MESSAGES_VERSION, true );
        wp_localize_script( 'wp-direct-messages-js', 'script_data',
        array(
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
            )
        );
    }
    public function enqueue_styles(){
        wp_register_style( 'wp-direct-messages-css', WP_DIRECT_MESSAGES_URL . 'assets/css/wp-direct-messages-frontend.css', array(), WP_DIRECT_MESSAGES_VERSION, 'all' );
    }

	public function init_frontend_class(){
		include_once(plugin_dir_path( __FILE__ ).'class-wp-direct-messages_frontend.php');
	}


	public function run_hooks(){
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));

		add_action('init', array($this, 'init_frontend_class'));
    }



    public function run(){
        $this->run_hooks();
    }

}
