<?php





/**


 * NOTE: You should always use the wp_enqueue_script() and wp_enqueue_style() functions to include


 * javascript and css files.


 */





/**


 * bp_gifts_add_js()


 *


 * This function will enqueue the components javascript file, so that you can make


 * use of any javascript you bundle with your component within your interface screens.


 */


function bp_gifts_add_js() {

	global $bp;

	if ( $bp->current_component == $bp->gifts->slug ) {


		wp_enqueue_script( 'bpgift-general',WP_PLUGIN_URL.'/bp-gifts-rebirth/includes/js/general.js'); 

		wp_enqueue_script( 'bpgift-jcarousel',WP_PLUGIN_URL.'/bp-gifts-rebirth/includes/js/jquery.jcarousel.pack.js'); 

	}

}
add_action( 'wp_enqueue_scripts', 'bp_gifts_add_js' );


function bp_gifts_add_css() {
    
	global $bp;
	
	if ( $bp->current_component == $bp->gifts->slug ) {
		
		wp_enqueue_style( 'bpgift-jcarousel', WP_PLUGIN_URL .'/bp-gifts-rebirth/includes/templates/css/jquery.jcarousel.css' );
		wp_enqueue_style( 'bpgift-jcarousel-skin', WP_PLUGIN_URL .'/bp-gifts-rebirth/includes/templates/css/skin.css' );
		wp_print_styles();	
	}
	
}

add_action( 'wp_head', 'bp_gifts_add_css' );



?>