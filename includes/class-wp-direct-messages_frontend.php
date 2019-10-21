<?php
Class WP_direct_messages_frontend
{

    private $user;

    function __construct($user = false){
		require_once(plugin_dir_path(__FILE__).'/class-wp_direct_messages_db.php');
		wp_enqueue_script('wp-direct-messages-js');
		wp_enqueue_style('wp-direct-messages-css');
		if($user == false){
			$user = get_current_user_id();
		}
		$this->user = $user;
		$this->db = new WP_direct_messages_db($user);


		add_action('wp_footer', array($this, 'add_footer_modal'));
    }


	public function add_footer_modal(){

		?>
		<div id="wp-dm-chat-window" data-receiver-id="" style="display:none;">

			<span class="close">
			</span>
			<div class="inside-modal">
				<div class="modal-header">
					<div class="recepient">
					</div>
				</div>
				<div class="modal-messages">

				</div>
				<div class="modal-new-message">
					<input type="text" id="new_message">
					<button class="send-message">
						<?php _e('send', 'bbh'); ?>
					</button>
				</div>
			</div>
		</div>


		<?php

	}

	private function get_user_name($user_id = null){
		$user_info = new WP_User( $user_id );
		if ( $user_info->first_name ) {
			if ( $user_info->last_name ) {
				return $user_info->first_name . ' ' . $user_info->last_name;
			}
			return $user_info->first_name;
		}
		return $user_info->display_name;
	}

	private function message_card($user, $user2, $date){
		$user2Name = $this->get_user_name($user2);
		$time = strtotime($date);
		$time = date('U', $time);
		$time = strftime('%e. %b.', $time);
		ob_start();
		?>
			<div class="message-card message-list-item" data-user-id="<?php echo $user2; ?>">
				<h3 class="title">
					<?php echo $user2Name ?>
				</h3>
				<div class="date">
					<?php echo $time; ?>
				</div>
			</div>
		<?php
		return ob_get_clean();
	}

    public function get_unread_message_list(){

		$conversations = $this->db->get_user_unread_conversations();
		if($conversations){
			$html = '';
			foreach($conversations as $conversation):
				$sender = $conversation['sender_id'];
				$time = $conversation['created_at'];
				$html .= $this->message_card($this->user, $sender, $time);
			endforeach;
			return $html;
		} else{
			return false;
		}
    }

	public function get_read_message_list(){
		$conversations = $this->db->get_user_read_conversations();
		if($conversations){
			$html = '';
			foreach($conversations as $conversation):
				$sender = $conversation['sender_id'];
				if($sender == $this->user){
					$sender = $conversation['receiver_id'];
				}

				$time = $conversation['created_at'];

				$html .= $this->message_card($this->user, $sender, $time);
			endforeach;
			return $html;
		} else{
			return false;
		}
	}



}
