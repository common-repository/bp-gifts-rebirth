<?php



/***

 * This file is used to add site administration menus to the WordPress backend.

 *

 * If you need to provide configuration options for your component that can only

 * be modified by a site administrator, this is the best place to do it.

 *

 * However, if your component has settings that need to be configured on a user

 * by user basis - it's best to hook into the front end "Settings" menu.

 */



/**

 * bp_gifts_admin()

 *

 * Checks for form submission, saves component settings and outputs admin screen HTML.

 */

function bp_gifts_admin($message = '', $type = 'error') {

	global $bp;

	

	if ( $message != '' ) {

	echo wp_specialchars( attribute_escape( $message ) );

	}



	/* If the form has been submitted and the admin referrer checks out, save the settings */

	

	/* check submit from edit gift data */

	if ( isset( $_POST['submit'] ) && check_admin_referer('gifts-settings') ) {

		if (isset( $_POST['gift_id'])) {

		$gift = new BP_Gifts($_POST['gift_id']);

		$gift->gift_name = $_POST['gift_name'];

		$gift->category = $_POST['category'];

		$gift->point = $_POST['point'];

		$gift->save();

		$updated = true;

		} elseif (isset( $_FILES['file']) && check_admin_referer('gifts-settings')) { /* check submit from gift upload */ 

		$message = sprintf( __('Gift item was upload successfully! <br/>', 'buddypress'), $type);

		$dir = WP_PLUGIN_DIR.'/bp-gifts-rebirth/includes/images';

		if (file_exists($dir.'/'.$_FILES["file"]["name"])){

			echo "<div id='message' class='updated fade'><p>" . __( 'Gifts Image already exist!!', 'bp-gifts' ) . "</p></div>";

		} elseif (!bp_core_check_avatar_type($_FILES)) {

			echo "<div id='message' class='updated fade'><p>" . __( 'Please upload only JPG GIF or PNG image!!', 'bp-gifts' ) . "</p></div>";

		} else {

		move_uploaded_file($_FILES["file"]["tmp_name"], $dir.'/'.$_FILES["file"]["name"]);

		$giftname = explode(".", $_FILES["file"]["name"]);

		bp_gifts_newgift($giftname[0], $_FILES["file"]["name"]);

		$uploaded = true;

		}


		}



		

	}



?>



<?php

	// check if gift was chosed to edit or delete

	if ( isset($_GET['mode']) && isset($_GET['gift_id'])) {

		$gift = new BP_Gifts($_GET['gift_id']);

		if ($_GET['mode'] == 'delete') {

			if ($gift->delete()){

				$message = sprintf( __('Gift item was deleted successfully!', 'buddypress'), $type);

			} else { $message = sprintf( __('Error !!! Can not delete gift item.', 'buddypress'), $type); }

		unset($_GET['mode']);

		bp_gifts_admin($message);

		} elseif ($_GET['mode'] == 'edit'){

		echo '<h1>'._e( 'Gifts Item Admin', 'bp-gifts' ).'</h1><br/>';

		echo '<img src="' . bp_get_root_domain() .'/wp-content/plugins/bp-gifts-rebirth/includes/images/'. $gift->gift_image .'" />';

		?>

		<form action="<?php echo site_url() . '/wp-admin/admin.php?page=bp-gifts-settings' ?>" name="gifts-settings-form" id="gifts-settings-form" method="post">



			<table class="form-table">

				<tr valign="top">

					<th scope="row"><label for="target_uri"><?php _e( 'Gift name', 'bp-gifts' ) ?></label></th>

					<td>

						<input name="gift_name" type="text" id="gift_name" value="<?php esc_attr_e( $gift->gift_name ); ?>" size="30" />

					</td>

				</tr>

					<th scope="row"><label for="target_uri"><?php _e( 'Category', 'bp-gifts' ) ?></label></th>

					<td>

						<input name="category" type="text" id="point" value="<?php echo esc_attr_e( $gift->category ); ?>" size="30" />

					</td>

				</tr>

					<th scope="row"><label for="target_uri"><?php _e( 'Point', 'bp-gifts' ) ?></label></th>

					<td>

						<input name="point" type="text" id="point" value="<?php echo esc_attr_e( $gift->point ); ?>" size="30" />

					</td>

				</tr>

			</table>

			<input type="hidden" name="gift_id" value="<?php echo $gift->id; ?>" />

			<p class="submit">

				<input type="submit" name="submit" value="<?php _e( 'Save Settings', 'bp-gifts' ) ?>"/>

			</p>



			<?php

			/* This is very important, don't leave it out. */

			wp_nonce_field( 'gifts-settings' );

			?>

		</form>

<?php

		}

	} else {

?>



<!--------------- start main config admin panel -------------->



	<div class="wrap">

		<h2><?php _e( 'Gifts Admin', 'bp-gifts' ) ?></h2>

		<br />



		<?php if ( isset($updated) ) : ?><?php echo "<div id='message' class='updated fade'><p>" . __( 'Settings Updated.', 'bp-gifts' ) . "</p></div>" ?><?php endif; ?>

		<?php if ( isset($uploaded) ) : ?><?php echo "<div id='message' class='updated fade'><p>" . __( 'Gifts Uploaded.', 'bp-gifts' ) . "</p></div>" ?><?php endif; ?>



		



<form action="<?php echo site_url() . '/wp-admin/admin.php?page=bp-gifts-settings' ?>" method="post" enctype="multipart/form-data" name="gift-upload-form" id="gift-upload-form" >			

<br/>

<label><?php _e('Select Gift Image to Upload *', 'bp-gifts' ) ?><br />

<input type="file" name="file" id="file"/></label>

<p class="submit">

				<input type="submit" name="submit" value="<?php _e( 'Upload', 'bp-gifts' ) ?>"/>

			</p>

<input type="hidden" name="action" value="gifts_upload" />

<?php

/* This is very important, don't leave it out. */

wp_nonce_field( 'gifts-settings' );

?>

</form>

<br/>

			

			<?php /* return all gift item */

			echo '<h3>Gift Item Editor :</h3>';

			$allgift = bp_gifts_allgift();

			echo '<div style="width:80%; padding:20px;">';

			foreach ($allgift as $giftitem) {

			echo '<div style="float:left; width:10%; text-align:center;">';

			echo '<img src="' . bp_get_root_domain() .'/wp-content/plugins/bp-gifts-rebirth/includes/images/'. $giftitem->gift_image .'" /><br/>';

			echo '<p style="text-align: center">'.$giftitem->gift_name.'</p>';

			echo '<p style="text-align: center">';

			echo '<a href="'. site_url() . '/wp-admin/admin.php?page=bp-gifts-settings&gift_id='.$giftitem->id.'&mode=edit" /> <img src="'. bp_get_root_domain() .'/wp-content/plugins/bp-gifts-rebirth/includes/images/admin/edit.png" /></a>';

			echo '<a href="'. site_url() . '/wp-admin/admin.php?page=bp-gifts-settings&gift_id='.$giftitem->id.'&mode=delete" /> <img src="'. bp_get_root_domain() .'/wp-content/plugins/bp-gifts-rebirth/includes/images/admin/delete.png" /></a>';

			echo '<p/>';

			echo '</div>';

			}

			echo '</div>';

			?>



			<!--<p class="submit">

				<input type="submit" name="submit" value="<?php //_e( 'Save Settings', 'bp-gifts' ) ?>"/>

			</p>-->



			



	</div>

	<?php

	}

	?>

<?php

}



function bp_gifts_upload_dir() {

	global $bp;



	$user_id = $bp->loggedin_user->id;

	

	$dir = WP_PLUGIN_DIR.'buddypress-gifts-rebirth/includes/images';



	$siteurl = trailingslashit( get_blog_option( 1, 'siteurl' ) );

	$url = str_replace(ABSPATH,$siteurl,$dir);

	

	$bdir = $dir;

	$burl = $url;

	

	$subdir = '/' . $user_id;

	

	$dir .= $subdir;

	$url .= $subdir;



	if ( !file_exists( $dir ) )

		@wp_mkdir_p( $dir );



	return apply_filters( 'bp_gifts_upload_dir', array( 'path' => $dir, 'url' => $url, 'basedir' => $bdir, 'baseurl' => $burl, 'error' => false ) );



}



?>