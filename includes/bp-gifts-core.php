<?php



/* Define a constant that can be checked to see if the component is installed or not. */

define ( 'BP_GIFTS_IS_INSTALLED', 1 );



/* Define a constant that will hold the current version number of the component */

define ( 'BP_GIFTS_VERSION', '1.0' );



/* Define a constant that will hold the database version number that can be used for upgrading the DB
 */

define ( 'BP_GIFTS_DB_VERSION', '1' );



/* Define a slug constant that will be used to view this components pages (http://example.org/SLUG) */

if ( !defined( 'BP_GIFTS_SLUG' ) )

	define ( 'BP_GIFTS_SLUG', 'gifts' );



/*

 * If you want the users of your component to be able to change the values of your other custom constants,

 * you can use this code to allow them to add new definitions to the wp-config.php file and set the value there.

 */






load_textdomain( 'bp-gifts', dirname( __FILE__ ) . '/languages/bp-gifts-' . get_locale() . '.mo' );

/**

 * The next step is to include all the files you need for your component.

 * You should remove or comment out any files that you don't need.

 */



/* The classes file should hold all database access classes and functions */

require ( dirname( __FILE__ ) . '/bp-gifts-classes.php' );



/* The ajax file should hold all functions used in AJAX queries */

require ( dirname( __FILE__ ) . '/bp-gifts-ajax.php' );



/* The cssjs file should set up and enqueue all CSS and JS files used by the component */

require ( dirname( __FILE__ ) . '/bp-gifts-cssjs.php' );


/* The notifications file should contain functions to send email notifications on specific user actions */

require ( dirname( __FILE__ ) . '/bp-gifts-notifications.php' );



/* The filters file should create and apply filters to component output functions. */

require ( dirname( __FILE__ ) . '/bp-gifts-filters.php' );



/**

 * bp_gifts_setup_globals()

 *

 * Sets up global variables for your component.

 */

function bp_gifts_setup_globals() {

	global $bp, $wpdb;



	/* For internal identification */

	$bp->gifts->id = 'gifts';



	$bp->gifts->table_name = $wpdb->base_prefix . 'bp_gifts';

	$bp->gifts->table_name_data = $wpdb->base_prefix . 'bp_gifts_data';

	$bp->gifts->format_notification_function = 'bp_gifts_format_notifications';

	$bp->gifts->slug = BP_GIFTS_SLUG;

	//$bp->gifts->slug = get_site_option( 'bp_album_slug' );

	/* Register this in the active components array */

	$bp->active_components[$bp->gifts->slug] = $bp->gifts->id;

}

/***

 * In versions of BuddyPress 1.2.2 and newer you will be able to use:

 * add_action( 'bp_setup_globals', 'bp_gifts_setup_globals' );

 */
add_action( 'bp_setup_globals', 'bp_gifts_setup_globals' );
add_action( 'wp', 'bp_gifts_setup_globals', 2 );

add_action( 'admin_menu', 'bp_gifts_setup_globals', 2 );
add_action( 'network_admin_menu', 'bp_gifts_setup_globals', 2 );



/**

 * bp_gifts_add_admin_menu()

 *

 * This function will add a WordPress wp-admin admin menu for your component under the

 * "BuddyPress" menu.

 */

function bp_gifts_add_admin_menu() {

	global $bp;
	
	if( is_multisite()  ){
		return;
	}

	else
	{
		if ( !is_super_admin() ){
			return false;
		}

		if ($bp->loggedin_user->is_site_admin)
		{
		require ( dirname( __FILE__ ) . '/bp-gifts-admin.php' );

		add_submenu_page( 'bp-general-settings', __( 'Buddypress Gifts', 'bp-gifts' ), __( 'Gifts Setup', 'bp-gifts' ), 'manage_options', 'bp-gifts-settings', 'bp_gifts_admin' );
		}
		
	}
}
add_action( 'admin_menu', 'bp_gifts_add_admin_menu' );


function bp_gifts_add_admin_menu_network() {

		global $bp;

		if ( is_super_admin() ){
			
			require ( dirname( __FILE__ ) . '/bp-gifts-admin-network.php' );

			add_submenu_page( 'bp-general-settings', __( 'Buddypress Gifts', 'bp-gifts' ), __( 'Gifts Setup', 'bp-gifts' ), 'manage_options', 'bp-gifts-settings', 'bp_gifts_admin' );
		}
		else {
			
			return false;
		}
	

}

add_action( 'network_admin_menu', 'bp_gifts_add_admin_menu_network' );



/**

 * bp_gifts_setup_nav()

 *

 * Sets up the user profile navigation items for the component. This adds the top level nav

 * item and all the sub level nav items to the navigation array. This is then

 * rendered in the template.

 */

function bp_gifts_setup_nav() {

	global $bp;



	/* Add 'Gifts' to the main user profile navigation */

	bp_core_new_nav_item( array(

		'name' => __( 'Gifts', 'bp-gifts' ),

		'slug' => $bp->gifts->slug,

		'position' => 80,

		'screen_function' => 'bp_gifts_screen',

		'default_subnav_slug' => 'screen-one'

	) );



	$gifts_link = $bp->loggedin_user->domain . $bp->gifts->slug . '/';



	/* Create two sub nav items for this component */

	bp_core_new_subnav_item( array(

		'name' => __( '', 'bp-gifts' ),

		'slug' => 'screen-one',

		'parent_slug' => $bp->gifts->slug,

		'parent_url' => $gifts_link,

		'screen_function' => 'bp_gifts_screen',

		'position' => 10

	) );

}



/***

 * In versions of BuddyPress 1.2.2 and newer you will be able to use:

 * add_action( 'bp_setup_nav', 'bp_gifts_setup_nav' );

 */

add_action( 'bp_setup_nav', 'bp_gifts_setup_nav' );
add_action( 'wp', 'bp_gifts_setup_nav', 2 );

add_action( 'admin_menu', 'bp_gifts_setup_nav', 2 );
add_action( 'network_admin_menu', 'bp_gifts_setup_nav', 2 );



/**

 * bp_gifts_load_template_filter()

 *

 * You can define a custom load template filter for your component. This will allow

 * you to store and load template files from your plugin directory.

 */

function bp_gifts_load_template_filter( $found_template, $templates ) {

	global $bp;



	/**

	 * Only filter the template location when we're on the gifts component pages.

	 */

	if ( $bp->current_component != $bp->gifts->slug )

		return $found_template;



	foreach ( (array) $templates as $template ) {

		if ( file_exists( STYLESHEETPATH . '/' . $template ) )

			$filtered_templates[] = STYLESHEETPATH . '/' . $template;

		else

			$filtered_templates[] = dirname( __FILE__ ) . '/templates/' . $template;

	}



	$found_template = $filtered_templates[0];



	return apply_filters( 'bp_gifts_load_template_filter', $found_template );

}

add_filter( 'bp_located_template', 'bp_gifts_load_template_filter', 10, 2 );





/********************************************************************************

 * Screen Functions

 *

 * Screen functions are the controllers of BuddyPress. They will execute when their

 * specific URL is caught. They will first save or manipulate data using business

 * functions, then pass on the user to a template file.

 */


function bp_gifts_screen() {

	global $bp;


	 /* remove all gift component notification */

	 if ($bp->loggedin_user->id == $bp->displayed_user->id) {

	 bp_core_delete_notifications_for_user_by_type($bp->displayed_user->id,'gifts','new_gifts');

	 }

	 
	/* Add a do action here, so your component can be extended by others. */

	do_action( 'bp_gifts_screen_one' );



	/****

	 * Displaying Content

	 */



	bp_core_load_template( apply_filters( 'bp_gifts_template_screen', 'gifts/screen' ) );


}


function bp_gifts_screen_settings_menu() {

	global $bp, $current_user, $bp_settings_updated, $pass_error;



	if ( isset( $_POST['submit'] ) ) {

		/* Check the nonce */

		check_admin_referer('bp-gifts-admin');



		$bp_settings_updated = true;



		/**

		 * This is when the user has hit the save button on their settings.

		 * The best place to store these settings is in wp_usermeta.

		 */

		update_usermeta( $bp->loggedin_user->id, 'bp-gifts-option-one', attribute_escape( $_POST['bp-gifts-option-one'] ) );

	}



	add_action( 'bp_template_content_header', 'bp_gifts_screen_settings_menu_header' );

	add_action( 'bp_template_title', 'bp_gifts_screen_settings_menu_title' );

	add_action( 'bp_template_content', 'bp_gifts_screen_settings_menu_content' );



	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'plugin-template' ) );

}



	function bp_gifts_screen_settings_menu_header() {

		_e( 'Gifts Settings Header', 'bp-gifts' );

	}



	function bp_gifts_screen_settings_menu_title() {

		_e( 'Gifts Settings', 'bp-gifts' );

	}



	function bp_gifts_screen_settings_menu_content() {

		global $bp, $bp_settings_updated; ?>



		<?php if ( $bp_settings_updated ) { ?>

			<div id="message" class="updated fade">

				<p><?php _e( 'Changes Saved.', 'bp-gifts' ) ?></p>

			</div>

		<?php } ?>



		<form action="<?php echo $bp->loggedin_user->domain . 'settings/gifts-admin'; ?>" name="bp-gifts-admin-form" id="account-delete-form" class="bp-gifts-admin-form" method="post">



			<input type="checkbox" name="bp-gifts-option-one" id="bp-gifts-option-one" value="1"<?php if ( '1' == get_usermeta( $bp->loggedin_user->id, 'bp-gifts-option-one' ) ) : ?> checked="checked"<?php endif; ?> /> <?php _e( 'Do you love clicking checkboxes?', 'bp-gifts' ); ?>

			<p class="submit">

				<input type="submit" value="<?php _e( 'Save Settings', 'bp-gifts' ) ?> &raquo;" id="submit" name="submit" />

			</p>



			<?php

			/* This is very important, don't leave it out. */

			wp_nonce_field( 'bp-gifts-admin' );

			?>



		</form>

	<?php

	}





/********************************************************************************

 * Activity & Notification Functions

 *

 * These functions handle the recording, deleting and formatting of activity and

 * notifications for the user and for this specific component.

 */





/**

 * bp_example_screen_notification_settings()

 *

 * Adds notification settings for the component, so that a user can turn off email

 * notifications set on specific component actions.

 */

function bp_gifts_screen_notification_settings() {

	global $current_user;


	?>

	<table class="notification-settings" id="bp-gifts-notification-settings">
	<thead>
		<tr>

			<th class="icon"></th>

			<th class="title"><?php _e( 'Gifts', 'bp-gifts' ) ?></th>

			<th class="yes"><?php _e( 'Yes', 'bp-gifts' ) ?></th>

			<th class="no"><?php _e( 'No', 'bp-gifts' )?></th>

		</tr>
	</thead>
	<tbody>
		<tr>

			<td></td>

			<td><?php _e( 'You received new gifts', 'bp-gifts' ) ?></td>

			<td class="yes"><input type="radio" name="notifications[notification_gifts_received]" value="yes" <?php if ( !get_usermeta( $current_user->id,'notification_gifts_received') || 'yes' == get_usermeta( $current_user->id,'notification_gifts_received') ) { ?>checked="checked" <?php } ?>/></td>

			<td class="no"><input type="radio" name="notifications[notification_gifts_received]" value="no" <?php if ( get_usermeta( $current_user->id,'notification_gifts_received') == 'no' ) { ?>checked="checked" <?php } ?>/></td>

		</tr>
	</tbody>


		<?php do_action( 'bp_gifts_notification_settings' ); ?>

	</table>

<?php

}

add_action( 'bp_notification_settings', 'bp_gifts_screen_notification_settings' );



function bp_gifts_record_activity( $args = '' ) {

	global $bp;



	if ( !function_exists( 'bp_activity_add' ) )

		return false;

	

	$defaults = array(

		'id' => false,

		'user_id' => $bp->loggedin_user->id,

		'action' => '',

		'content' => '',

		'primary_link' => '',

		'component' => 'gifts',

		'type' => false,

		'item_id' => false,

		'secondary_item_id' => false,

		'recorded_time' => gmdate( "Y-m-d H:i:s" ),

		'hide_sitewide' => false

	);



	$r = wp_parse_args( $args, $defaults );

	extract( $r );



	return bp_activity_add( array( 'id' => $id, 'user_id' => $user_id, 'action' => $action, 'content' => $content, 'primary_link' => $primary_link, 'component' => $component, 'type' => $type, 'item_id' => $item_id, 'secondary_item_id' => $secondary_item_id, 'recorded_time' => $recorded_time, 'hide_sitewide' => $hide_sitewide ) );

}





function bp_gifts_format_notifications( $action, $item_id, $secondary_item_id, $total_items ) {

	global $bp;



	switch ( $action ) {

		case 'new_gifts':

		

			if ( (int)$total_items > 1 ) {

				return apply_filters( 'bp_gifts_multiple_newgifts_notifications', '<a href="' . $bp->loggedin_user->domain . $bp->gifts->slug . '/" title="' . __( 'Multiple gifts', 'bp-gifts' ) . '">' . sprintf( __( '%d new gifts, multi-gifts!', 'bp-gifts' ), (int)$total_items ) . '</a>', $total_items );

			} else {

				$user_fullname = bp_core_get_user_displayname( $item_id, false );

				$user_url = bp_core_get_userlink( $item_id, true );

				return apply_filters( 'bp_gifts_single_newgifts_notifications', '<a href="' . 'gifts' . '?new" title="' . $user_fullname .'\'s profile">' . sprintf( __( '%s sent you a high-five!', 'bp-gifts' ), $user_fullname ) . '</a>', $user_fullname );

				

			}

		break;

	}



	do_action( 'bp_gifts_format_notifications', $action, $item_id, $secondary_item_id, $total_items );



	return false;

}





/***

 * From now on you will want to add your own functions that are specific to the component you are developing.

 * For example, in this section in the friends component, there would be functions like:

 */



function bp_gifts_send_giftsnotify( $to_user_id, $from_user_id ) {

	global $bp;



	

	bp_core_add_notification( $from_user_id, $to_user_id, 'gifts', 'new_gifts' );



	do_action( 'bp_gifts_send_gifts', $to_user_id, $from_user_id );



	return true;

}


function bp_gifts_remove_data( $user_id ) {

	/* You'll want to run a function here that will delete all information from any component tables

	   for this $user_id */



	/* Remember to remove usermeta for this component for the user being deleted */

	delete_usermeta( $user_id, 'notification_gifts_received' );



	do_action( 'bp_gifts_remove_data', $user_id );

}

add_action( 'wpmu_delete_user', 'bp_gifts_remove_data', 1 );

add_action( 'delete_user', 'bp_gifts_remove_data', 1 );


function bp_gifts_root_slug() {
	echo bp_get_gifts_root_slug();
}
/**
 * Return the gifts component root slug
 *
 */
function bp_get_gifts_root_slug() {
	global $bp;
	return apply_filters( 'bp_get_gifts_root_slug', $bp->gifts->slug );
}


?>