<?php

/**
 * Profile
 *
 * This file is for manage User form on profile section.
 * Adds IntegralCES user section at the end of user profile to set integralCES/Account
 *
 * @package     iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 */

namespace iwp;


/**
 *
 * Show messages on profile box by catching 'Profile updated' &  'User updated.' translations.
 */
function iwp_display_errors ($translated_text, $untranslated_text, $text){

	$str_errors .= iwp_errors::iwp_errors_get( 'users' );

	if ( $str_error && ( $translated_text == 'Profile updated.' ||  $translated_text == 'User updated.') ) {

		iwp_errors::iwp_errors_clean( 'users' );

		return __ ( 'save_profile_error', 'iwp' ) . '<br>' . $str_errors ;
	}

	return $translated_text;

}
add_filter( 'gettext', 'iwp\iwp_display_errors', 10, 3 );


/**
 *
 * Echoes section, whenever a user profile is required.
 *
 */
function iwp_show_profile_forms() {

	//Get editing user from $_GET or current loggedin user
	$wp_user_id = $_GET['user_id'];
	if ( $wp_user_id ) {
		$wp_user = get_user_by('id', $wp_user_id);
	}
	if ( ! $wp_user ) {
		$wp_user = wp_get_current_user();
	}

	//Construct iwp_user
	echo iwp_forms::iwp_show_user_section( $wp_user );

	//Show errors
	iwp_admin_notices_profile();

}
add_action( 'show_user_profile', 'iwp\iwp_show_profile_forms' );
add_action( 'edit_user_profile', 'iwp\iwp_show_profile_forms' );


/**
 *
 * Manage saving ices's user account, whenever a user profile is required.
 */
function iwp_save_profile_forms() {

	$wp_user_id = $_POST['user_id'];
	if ( $wp_user_id )
		$wp_user = get_user_by ('id', $wp_user_id );

	if ( ! $wp_user )
		$wp_user = wp_get_current_user();

	//Gatekeeper
	if ( ! current_user_can( 'edit_user', $wp_user->ID ) )
		return false;

	$api = iwp_api::get_instance();
	$account = $api->Accounts->get( $_POST["iwp_ices_user_account"] );

	if ( $account )
		update_user_meta( $wp_user_id, 'ices_user_account', $account->name );
	else
		mwpw_errors::mwpw_errors_add( 'users', __( 'Missing or invalid account: ' . $_POST["iwp_ices_user_account"], 'iwp' ) );

}
add_action( 'personal_options_update', 'iwp\iwp_save_profile_forms' );
add_action( 'edit_user_profile_update', 'iwp\iwp_save_profile_forms' );


/**
 *
 * Reads errors for key 'profile' and display any existing one for admin users, after cleans stack.
 * Called in iwp_show_profile_forms()
 *
 * @uses iwp_errors class
 * @uses add_settings_error, settings_errors
 */
function iwp_admin_notices_profile() {

	//Print any init errors
	$str_errors = iwp_errors::iwp_errors_get( 'users' );

	if (  $str_errors ) {

		$plugin_name = __( 'Easy Digital Downloads - IntegralCES Gateway', 'iwp' ) . "<br>";

		add_settings_error( 'iwpprofile', '', $plugin_name . $str_errors , 'error' );
		iwp_errors::iwp_errors_clean( 'users' );

		settings_errors( 'iwpprofile', false ) ;
	}

}
