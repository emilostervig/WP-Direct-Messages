<?php

class WP_direct_messages_db
{
    private int $user = false;


    function __construct($user = false)
    {
        public $wpdb;
        this->$user = $user;
    }



    public function get_user_conversations($user = $this->$user){
        $query = "SELECT DISTINCT ID FROM {$wpdb->prefix}dm_message
            WHERE sender_id = {$user}
                OR receiver_id = {$user}
            ORDER BY created_date DESC
            GROUP BY is_read ";
        $results = $wpdb->get_results($query, ARRAY_N);

        if(null == results){
            return false;
        } else{
            return $results;
        }
    }

    public function get_conversation_messages($conversation = 0){
        $query = "SELECT * FROM {$wpdb->prefix}dm_message
            WHERE ID = {$conversation}
            ORDER BY created_date DESC";
        $results = $wpdb->get_results($query, OBJECT);

        if(null == results){
            return false;
        } else{
            return $results;
        }
    }

    public function get_unread_conversation_messages($user = $this->$user, $conversation = 0){
        $query = "SELECT * FROM {$wpdb->prefix}dm_message
            WHERE ID = {$conversation}
            AND is_read = false
            AND receiver_id = {$user}
            ORDER BY created_date DESC";
        $results = $wpdb->get_results($query, OBJECT);

        if(null == results){
            return false;
        } else{
            return $results;
        }
    }

    public function set_conversation_read($user = $this->$user, $conversation = 0){
        $table = $wpdb->prefix."dm_message";
        $data = array(
            'is_read' => false,
        );
        $where = array(
            'receiver_id' => $user,
            'conversation_id' => $conversation
        );

        $results = $wpdb->update
            $table,
            $data,
            $where
        );

        return $results;

    }

    private function delete_conversation($conversation = 0){
        // delete conversation
        $wpdb->delete( $wpdb->prefix . 'dm_conversations', array('ID' => $conversation) );

        // delete all messages
        $wpdb->delete( $wpdb->prefix . 'dm_message', array('conversation_id' => $conversation) );
    }
}
