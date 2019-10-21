<?php

class WP_direct_messages_db
{
    private $user = false;

    function __construct($user = false)
    {
        global $wpdb;
        $this->user = $user;
    }



    public function get_user_conversations(){
		global $wpdb;
		$user = $this->user;
        $query = "SELECT DISTINCT conversation_id, sender_id, receiver_id FROM {$wpdb->prefix}dm_message
            WHERE sender_id = {$user}
                OR receiver_id = {$user}
            GROUP BY is_read
			ORDER BY created_at DESC ";
        $results = $wpdb->get_results($query, ARRAY_A);

        if(null == $results){
            return false;
        } else{
            return $results;
        }
    }
	public function get_user_unread_conversations(){
		global $wpdb;
		$user = $this->user;
        $query = "SELECT DISTINCT conversation_id, sender_id, receiver_id,
			(
			SELECT max(created_at) from wp_dm_message where sender_id = {$user} or receiver_id = {$user}
			) as created_at
			FROM {$wpdb->prefix}dm_message
            WHERE receiver_id = {$user}
			AND is_read = 0
			ORDER BY created_at DESC ";
        $results = $wpdb->get_results($query, ARRAY_A);
        if(null == $results){
            return false;
        } else{
            return $results;
        }
	}

	public function get_user_read_conversations(){
		global $wpdb;
		$user = $this->user;
        $query = "SELECT DISTINCT conversation_id, sender_id, receiver_id,
			(
    		SELECT max(created_at) from wp_dm_message where sender_id = {$user} or receiver_id = {$user}
    		) as created_at
		 	FROM {$wpdb->prefix}dm_message
            WHERE (receiver_id = {$user}
			AND is_read = 1)
			OR sender_id = {$user}
			ORDER BY created_at DESC ";
        $results = $wpdb->get_results($query, ARRAY_A);

        if(is_null($results)  ){
            return false;
        } else{
            return $results;
        }
	}

    public function get_conversation_messages($conversation = 0){
		global $wpdb;
        $query = "SELECT * FROM {$wpdb->prefix}dm_message
            WHERE conversation_id = {$conversation}
            ORDER BY created_at DESC";
        $results = $wpdb->get_results($query, OBJECT);

        if(null == $results){
            return false;
        } else{
            return $results;
        }
    }

    public function get_unread_conversation_messages($conversation = 0){
		global $wpdb;
		$user = $this->user;
        $query = "SELECT * FROM {$wpdb->prefix}dm_message
            WHERE ID = {$conversation}
            AND is_read = false
            AND receiver_id = {$user}
            ORDER BY created_at DESC";
        $results = $wpdb->get_results($query, OBJECT);

        if(null == results){
            return false;
        } else{
            return $results;
        }
    }

    public function set_conversation_read( $conversation = 0){
		global $wpdb;
		$user = $this->user;
        $table = $wpdb->prefix."dm_message";
        $data = array(
            'is_read' => false,
        );
        $where = array(
            'receiver_id' => $user,
            'conversation_id' => $conversation
        );

        $results = $wpdb->update(
            $table,
            $data,
            $where
        );

        return $results;

    }

    private function delete_conversation($conversation = 0){
		global $wpdb;
        // delete conversation
        $wpdb->delete( $wpdb->prefix . 'dm_conversations', array('ID' => $conversation) );

        // delete all messages
        $wpdb->delete( $wpdb->prefix . 'dm_message', array('conversation_id' => $conversation) );
    }
}
