<?php


function bpgifts_sendgift_updatedb() {

	global $bp;


	$action = __(bp_core_get_userlink($bp->loggedin_user->id).' '. __('send ', 'bp-gifts') . $_POST['gift_name'].' '. __('to ','bp-gifts') . bp_core_get_userlink($bp->displayed_user->id));
	

	$content = '<img class="gift-image" src="'. $_POST['gift_path'] .'" /><a href="'.$bp->displayed_user->domain.'">@'.$bp->displayed_user->userdata->user_login.'</a> '.$_POST['gift_message'];


	bp_gifts_record_activity( array( 'type' => 'new_gifts', 'action' => $action, 'user_id' => $bp->loggedin_user->id, 'item_id' => $bp->displayed_user->id, 'content' => $content) );

	

	echo __('Gifts was sent !', 'bp-gifts');


	//send e-mail to received 

	bp_gifts_send_giftsnotify( $bp->displayed_user->id, $bp->loggedin_user->id );

	
	
	return false;

	

}



add_action( 'wp_ajax_bpgifts_sendgift_updatedb', 'bpgifts_sendgift_updatedb' );



?>