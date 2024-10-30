<?php get_header() ?>



	<div id="content">

		<div class="padder">



			<div id="item-header">

				<?php locate_template( array( 'members/single/member-header.php' ), true ) ?>

			</div>



			<div id="item-nav">

				<div class="item-list-tabs no-ajax" id="object-nav">

					<ul>

						<?php bp_get_displayed_user_nav() ?>

					</ul>

				</div>

			</div>



			<div id="item-body">



				<div class="item-list-tabs no-ajax" id="subnav">

					<ul>

						<?php bp_get_options_nav() ?>

					</ul>

				</div>



				<h4><?php echo bp_word_or_name( __('Your Gifts', 'bp-gifts' ), __( "%s' Gifts", 'bp-gifts' )  ,false,false) ?></h4>
				<div style="height: 30px; clear: both; display: block; height: 30px;"><!--  Divider --></div>
				

<?php if (is_user_logged_in() && !bp_is_my_profile()) {

	// Check if Cubepoints and cubepoints buddypress integration is alredy installed
	 if ( function_exists( 'my_bp_gift_given_add_cppoints' ) ) {
	
		$Mypoint = (int)cp_getPoints(cp_currentUser());		
		$Giftpoints = (int)get_option('bp_gift_given_cp_bp');
		
		$CheckMyPoints = $Mypoint + $Giftpoints;
		$PointNeedToSendGift = $Giftpoints - 2*$Giftpoints;
	
			if($CheckMyPoints<0){
			?>
				<div id="message" class="info">
					
				
				<h4><?php _e('We are very sorry but it seems that you do not have enough points to sent a gift:','bp-gifts') ?></h4>
				<p><?php _e('You need at least: ','bp-gifts'); echo $PointNeedToSendGift; _e(' Points','bp-gifts');?> </p>
				
				</div>
				<div style="height: 30px; clear: both; display: block; height: 30px;"><!--  Divider --></div>
			<?php	
			}
		
			else
			{ ?>

				<div id="bpgifts-waiting" style="display:none"><img src="<?php echo plugins_url('/bp-gifts-rebirth/includes/templates/css/loading.gif') ?>" /></div>
				<div id="bpgifts-alert"></div><br />
				<div class="sendgift-panel">

				<div class="carousel">

				<ul id="mycarousel" class="jcarousel-skin-tango">

   					<!-- The content goes in here -->

   					<?php

   					$allgift = bp_gifts_allgift();

   					foreach ($allgift as $giftitem) 
   						{

    					echo '<li><img class="giftitem" id="'.$giftitem->id.'" name="'.$giftitem->gift_name.'" src="'.plugins_url('/bp-gifts-rebirth/includes/images/').$giftitem->gift_image.'" alt="" /></li>';

						}?>

				</ul>

				</div>

				<br/>

				<div class="sendgift-box">

				<div class="giftbox">
				
				<img class="giftbox" id="999" name="emptybox" src="<?php echo plugins_url('/bp-gifts-rebirth/includes/images/admin/emptybox.png') ?>" style="float:left; padding: 20px;"/>

				</div>



				<div id="gift-message">

					<div id="gift-textarea">
						
						<textarea name="gift-message" id="giftms" value="" cols="68" rows="5" ></textarea>

					</div>



					<div id="gift-button">

						<span class="highlight button" id="sendgift-button" >Send Gift</span>

					</div>

				</div>

				</div>

			</div>
			<div style="height: 30px; clear: both; display: block; height: 30px;"><!--  Divider --></div>
			<br class="clear" />
		<?php } ?>
			
	<?PHP  } //end of checking function 
	else 
	{	?>		
	
		<div id="bpgifts-waiting" style="display:none"><img src="<?php echo plugins_url('/bp-gifts-rebirth/includes/templates/css/loading.gif') ?>" /></div>
		<div id="bpgifts-alert"></div><br />

				

		<div class="sendgift-panel">

		<div class="carousel">

		<ul id="mycarousel" class="jcarousel-skin-tango">

   		<!-- The content goes in here -->

   		<?php

   		$allgift = bp_gifts_allgift();

   		foreach ($allgift as $giftitem) {

    		echo '<li><img class="giftitem" id="'.$giftitem->id.'" name="'.$giftitem->gift_name.'" src="'.plugins_url('/bp-gifts-rebirth/includes/images/').$giftitem->gift_image.'" alt="" /></li>';

			}

			?>

		</ul>

		</div>

			<br/>

			<div class="sendgift-box">
		
			<div class="giftbox">
			
			<img class="giftbox" id="999" name="emptybox" src="<?php echo plugins_url('/bp-gifts-rebirth/includes/images/admin/emptybox.png') ?>" style="float:left; padding: 20px;"/>

			</div>



			<div id="gift-message">

				<div id="gift-textarea">
					
					<textarea name="gift-message" id="giftms" value="" style="overflow:hidden" cols="68" rows="5" ></textarea>

				</div>



				<div id="gift-button">

					<span class="bp-gift-highlight button" id="sendgift-button" >Send Gift</span>

				</div>

			</div>

			</div>

		</div>
		<div style="height: 30px; clear: both; display: block; height: 30px;"><!--  Divider --></div>
		<br class="clear" />
	
	
	<?php } ?>
	
	<?php } ?>

<!--------------------- display gift activity loop --------------------------------->



<?php if ( bp_has_activities('scope=mentions&action=new_gifts&display_comments=threaded') ) : ?>



	<div class="pagination">

		<div class="pag-count"><?php bp_activity_pagination_count() ?></div>

		<div class="pagination-links"><?php bp_activity_pagination_links() ?></div>

	</div>



	<ul id="activity-stream" class="activity-list item-list">



	<?php while ( bp_activities() ) : bp_the_activity(); ?>



		<li class="<?php bp_activity_css_class(); ?>" id="activity-<?php bp_activity_id(); ?>">


			
            <div class="activity-avatar">
				<a href="<?php bp_activity_user_link(); ?>">

				<?php bp_activity_avatar(); ?>

				</a>
			</div>


			<div class="activity-content">



				<div class="activity-header">

				<?php bp_activity_action(); ?>

				</div>
                
                <?php if ( 'activity_comment' == bp_get_activity_type() ) : ?>

				<div class="activity-inreplyto">
				<strong><?php _e( 'In reply to: ', 'buddypress' ); ?></strong><?php bp_activity_parent_content(); ?> <a href="<?php bp_activity_thread_permalink(); ?>" class="view" title="<?php _e( 'View Thread / Permalink', 'buddypress' ); ?>"><?php _e( 'View', 'buddypress' ); ?></a>
			</div>

				<?php endif; ?>



				<?php if ( bp_activity_has_content() ) : ?>

				<div class="activity-inner">

				<?php bp_activity_content_body(); ?>

			</div>

				<?php endif; ?>



				<?php do_action( 'bp_activity_entry_content' ) ?>

				

				

			</div>

				
				
				
			
			
			
			
			
			
			<!-- activiti comment -->
			
			<?php do_action( 'bp_before_activity_entry_comments' ); ?>

			<?php if ( ( is_user_logged_in() && bp_activity_can_comment() ) || bp_activity_get_comment_count() ) : ?>

			<div class="activity-comments">

			<?php bp_activity_comments(); ?>

			<?php if ( is_user_logged_in() ) : ?>

				<form action="<?php bp_activity_comment_form_action(); ?>" method="post" id="ac-form-<?php bp_activity_id(); ?>" class="ac-form"<?php bp_activity_comment_form_nojs_display(); ?>>
					<div class="ac-reply-avatar"><?php bp_loggedin_user_avatar( 'width=' . BP_AVATAR_THUMB_WIDTH . '&height=' . BP_AVATAR_THUMB_HEIGHT ); ?></div>
					<div class="ac-reply-content">
						<div class="ac-textarea">
							<textarea id="ac-input-<?php bp_activity_id(); ?>" class="ac-input" name="ac_input_<?php bp_activity_id(); ?>"></textarea>
						</div>
						<input type="submit" name="ac_form_submit" value="<?php _e( 'Post', 'buddypress' ); ?>" /> &nbsp; <?php _e( 'or press esc to cancel.', 'buddypress' ); ?>
						<input type="hidden" name="comment_form_id" value="<?php bp_activity_id(); ?>" />
					</div>

					<?php do_action( 'bp_activity_entry_comments' ); ?>

					<?php wp_nonce_field( 'new_activity_comment', '_wpnonce_new_activity_comment' ); ?>

				</form>

			<?php endif; ?>

		</div>

			<?php endif; ?>

			<?php do_action( 'bp_after_activity_entry_comments' ); ?>

			

			

		</li>



	<?php endwhile; ?>



	</ul>



<?php else : ?>

	<div id="message" class="info">

		<p><?php echo bp_word_or_name( __('You still don\'t have any gift yet!', 'bp-gifts' ), __( "Either %s hasn't received any gift yet or they have restricted access", 'bp-gifts' )  ,false,false) ?></p>

	</div>

<?php endif; ?>





<!-------------------------------------------------------------------->





			</div><!-- #item-body -->



		</div><!-- .padder -->

	</div><!-- #content -->



	<?php locate_template( array( 'sidebar.php' ), true ) ?>



<?php get_footer() ?>