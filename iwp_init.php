<?php
/*
Plugin Name: Easy Digital Downloads - IntegralCES payment gateway
Plugin URL: https://github.com/aleph1888/integralces_edd_plugin
Description: IntegralCES Wordpress Plugin Web (IWP): An IntegralCES gateway for Easy Digital Downloads
Version: 0.3.14159265359
Author: enredaos.net
*/

namespace iwp;

use integralCES as i;


function iwp_init() {

	//Load language file.
	load_plugin_textdomain( 'iwp', false,  dirname(plugin_basename(__FILE__)) . '/languages/' );

}
add_action( 'init', 'iwp\iwp_init' );


/**
 *
 * Main plugin initialization.
 */
function iwp_load_gateway() {

	//Define ices API connection global vars importing settings.
	global $edd_options;
	if ( edd_is_test_mode() ) {
		define('IWP_client_id', $edd_options[ 'iwp_test_api_user']);
		define('IWP_password', $edd_options[ 'iwp_test_api_key']);
		define('IWP_base_url', 'http://169.254.226.5/cesinterop');
	} else {
		define('IWP_client_id', $edd_options[ 'iwp_live_api_user']);
		define('IWP_password', $edd_options[ 'iwp_live_api_key']);
		define('IWP_base_url', 'http://169.254.226.5/cesinterop');
	}

	//Load plugin files
	require_once __DIR__ . "/includes/iwp_api.inc";
	require_once __DIR__ . "/includes/iwp_fields.inc";
	require_once __DIR__ . "/includes/iwp_forms.inc";
	require_once __DIR__ . "/includes/iwp_errors.inc";


	//Configuration of ices user info in profile section.
	include (__DIR__ . "/iwp_profile.php");

	//Post user metabox in post edition sidebar.
	include (__DIR__ . "/iwp_post.php");

	//Register gateway in EDD if settings are setted
	include (__DIR__ . "/iwp_gateway.php");

	iwp_init_site();

}
add_action( 'plugins_loaded', 'iwp\iwp_load_gateway' );


/**
 *
 * Manage Downloads/Settings/Gateways plugin fields.
 *
 * @params array $settings
 *
 * @return array
 */
function iwp_add_settings( $settings ) {

	global $edd_options;

	//Load array
	$ices_gateway_settings = array(
		array(
			'id' => 'iwp_integralCES_gateway_settings',
			'name' =>  __( 'IntegralCES Gateway Settings.', 'iwp' ),
			'type' => 'header'
		),
		array(
			'id' => 'iwp_live_api_user',
			'name' => __( 'Live API User', 'iwp' ),
			'desc' => __( 'Enter your live API user', 'iwp' ),
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'iwp_live_api_key',
			'name' => __( 'Live API Key', 'iwp' ),
			'desc' => __( 'Enter your live API key', 'iwp' ),
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'iwp_test_api_user',
			'name' => __( 'Test API User', 'iwp' ),
			'desc' => __( 'Enter your test API user', 'iwp' ),
			'type' => 'text',
			'size' => 'regular'
		),
		array(
			'id' => 'iwp_test_api_key',
			'name' => __( 'Test API Key', 'iwp' ),
			'desc' => __( 'Enter your test API key', 'iwp' ),
			'type' => 'text',
			'size' => 'regular'
		),
	);

	return array_merge( $settings, $ices_gateway_settings );

}
add_filter( 'edd_settings_gateways', 'iwp\iwp_add_settings' );


/**
 *
 * Reads errors and display any existing on key 'init' for admin users, after it will clean stack.
 *
 * @uses iwp_errors class
 * @uses add_settings_error, settings_errors
 */
function iwp_admin_notices() {

	//Print any init errors
	if ( iwp_errors::iwp_errors_get( 'init' ) && wp_get_current_user()->roles[0] == 'administrator' ) {

		$plugin_name = __( 'Easy Digital Downloads - IntegralCES Mangopay Gateway: ', 'iwp' ) . "<br>";

		add_settings_error( 'iwpinit', '', $plugin_name . iwp_errors::iwp_errors_get( 'init' ) , 'error' );
		iwp_errors::iwp_errors_clean( 'init' );

		settings_errors( 'iwpinit', false ) ;
	}

}
add_action( 'admin_notices', 'iwp\iwp_admin_notices' );


