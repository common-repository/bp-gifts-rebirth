<?php



/**

 * Notification functions are used to send email notifications to users on specific events

 * They will check to see the users notification settings first, if the user has the notifications

 * turned on, they will be sent a formatted email notification. 

 *

 * You should use your own custom actions to determine when an email notification should be sent.

 */



function bp_gifts_send_gifts_notification( $to_user_id, $from_user_id ) {

	global $bp;

	

	/* Let's grab both user's names to use in the email. */

	$sender_name = bp_core_get_user_displayname( $from_user_id, false );

	$reciever_name = bp_core_get_user_displayname( $to_user_id, false );


	if ( 'no' == get_usermeta( (int)$to_user_id, 'notification_gifts_received' ) )

		return false;

	

	/* Get the userdata for the reciever and sender, this will include usernames and emails that we need. */

	$reciever_ud = get_userdata( $to_user_id );

	$sender_ud = get_userdata( $from_user_id );

	

	/* Now we need to construct the URL's that we are going to use in the email */

	//$sender_profile_link = site_url( BP_MEMBERS_SLUG . '/' . $sender_ud->user_login . '/' . $bp->profile->slug );
	
	$sender_profile_link = trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() . '/' . $sender_ud->user_login . '/' . $bp->profile->slug );
	
	
	$sender_gifts_link = trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() . '/'. $sender_ud->user_login . '/'. 'gifts' );

	
	$reciever_gifts_link = trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() . '/' . $reciever_ud->user_login . '/' . 'gifts' );

	
	$reciever_settings_link= trailingslashit( bp_get_root_domain() . '/' . bp_get_members_root_slug() );
	$reciever_settings_link .= $reciever_ud->user_login . '/settings/notifications' ;


	/* Set up and send the message */

	$to = $reciever_ud->user_email;

	$subject = '[' . get_blog_option( 1, 'blogname' ) . '] ' . sprintf( __( '%s send gifts to you!', 'bp-gifts' ), stripslashes($sender_name) );



	$message = sprintf( __( 

'%s sent you a new gifts! Why not send one back?



Check your new gifts: %s





To see %s\'s profile: %s



To send %s a gifts: %s



---------------------

', 'bp-gifts' ), $sender_name, $reciever_gifts_link, $sender_name, $sender_profile_link, $sender_name, $sender_gifts_link );



	$message .= sprintf( __( 'To disable these notifications please log in and go to: %s', 'bp-gifts' ), $reciever_settings_link );



	// Send it!

	wp_mail( $to, $subject, $message );

}

add_action( 'bp_gifts_send_gifts', 'bp_gifts_send_gifts_notification', 10, 2 );



?>