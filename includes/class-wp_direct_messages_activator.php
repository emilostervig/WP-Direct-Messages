<?php


class WP_direct_messages_activator{

    function __construct(){
        // TODO: check if i can run this require in constructor
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


    }


    private function add_conversation_table(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'dm_conversations';
        $sql = "CREATE TABLE $table_name (
            ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            /*user_id bigint(20) UNSIGNED NOT NULL, */
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            PRIMARY KEY (ID),
        ) $charset_collate;";

        dbDelta( $sql );
    }

    private function add_message_table(){
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'dm_message';
        $sql = "CREATE TABLE $table_name (
            ID bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            conversation_id bigint(20) UNSIGNED NOT NULL,
            sender_id bigint(20) UNSIGNED NOT NULL,
            receiver_id bigint(20) UNSIGNED NOT NULL,
            created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            is_read boolean DEFAULT FALSE NOT NULL,
            message nvarchar(250) NOT NULL,
            PRIMARY KEY (ID),
        ) $charset_collate;";

        dbDelta( $sql );
    }

    function run(){
        this.add_conversation_table();
        this.add_message_table();
    }

}
