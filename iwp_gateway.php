<?php

/**
 * Gateway
 *
 * This file manages integralCES Payment, hooking EDD Gateways required actions and filters.
 * Getinfo on: http://pippinsplugins.com/create-custom-payment-gateway-for-easy-digital-downloads/
 *
 * @package     iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 */

namespace iwp;

use integralCES as i;


/**
 *
 * Registers the gateway in EDD system; filter for edd_payment_gateways()
 * Will be triggered after iwp_init_site()
 *
 * @params array $gateways
 *
 * @return array
 */
function iwp_register_gateway( $gateways ) {

	$gateways['ices_gateway'] = array(
				'admin_label' => 'IntegralCES',
				'checkout_label' => 'IntegralCES'
				);
	return $gateways;

}


/**
 *
 * Checks if there is a context connection with IntegralCES server
 *
 * @return bool Could init or not.
 */
function iwp_init_site() {

	require_once( 'includes/iwp_api.inc' );
	$api = iwp_api::get_instance();
	$context = $api->check_client_connection();

	if ( ! $context )
		iwp_errors::iwp_errors_add( 'init', __( "Your site is not well configured to use integralCES gateway. Visit Campaigns|Settings|Gateways|IntegralCES section", 'iwp' ) );
	else
		add_filter( 'edd_payment_gateways', 'iwp\iwp_register_gateway' );

	return $context;

}


/**
 *
 * Get logged user account or input text
 */
function iwp_ices_gateway_cc_form() {

	require_once ( __DIR__ . "/includes/iwp_pay.inc");
	require_once ( __DIR__ . "/includes/iwp_api.inc");
	require_once ( __DIR__ . "/includes/iwp_errors.inc");

	//Retrieves user through mwpw_pay object.
	$wp_user = wp_get_current_user();

	//Display User form
	$output .= '<div>';

		//Ask for register (if user don't, we don't keep information, but do the process creating a user in MangoPay)
		$output .= iwp_forms::iwp_show_wordpress_login();

		//Display user data
		$output .= iwp_forms::iwp_show_user_section( $wp_user, ! $wp_user->ices_user_account );

		//Hide post_type=download id where paying is done
		$output .=  "<input type='hidden' name ='mwp_post_id' value='{$_REQUEST['iwp_post_id']}'>";

	$output .= "</div>";

	echo $output;

}
add_action( 'edd_ices_gateway_cc_form', 'iwp\iwp_ices_gateway_cc_form');


/**
 *
 * Create a IntegralCES\Payment object and redirect to integralCES server if not logged user.
 * Set a listener to get result callback.
 *
 * @param array $purchase_data
 */
function iwp_process_payment( $purchase_data ) {

	/**********************************
	* Purchase data comes in like this:

	    $purchase_data = array(
        	'downloads'     => array of download IDs,
	        'tax' 		=> taxed amount on shopping cart
	        'fees' 		=> array of arbitrary cart fees
	        'discount' 	=> discounted amount, if any
	        'subtotal'	=> total price before tax
	        'price'         => total price of cart contents after taxes,
	        'purchase_key'  =>  // Random key
	        'user_email'    => $user_email,
	        'date'          => date( 'Y-m-d H:i:s' ),
	        'user_id'       => $user_id,
        	'post_data'     => $_POST,
	        'user_info'     => array of user's information and used discount code
	        'cart_details'  => array of cart details,
	);
    	*/

	//Check for any stored errors
	$errors = edd_get_errors();

	if ( ! $errors ) {

		$purchase_summary = edd_get_purchase_summary( $purchase_data );

		/****************************************
		* setup the payment details to be stored
		****************************************/

		$payment = array(
			'price'        => $purchase_data['price'],
			'date'         => $purchase_data['date'],
			'user_email'   => $purchase_data['user_email'],
			'purchase_key' => $purchase_data['purchase_key'],
			'currency'     => $edd_options['currency'],
			'downloads'    => $purchase_data['downloads'],
			'cart_details' => $purchase_data['cart_details'],
			'user_info'    => $purchase_data['user_info'],
			'status'       => 'pending'
		);


                $errors = "";
                $missing_account = __( "Item: %s. In order to be contributed, %s must have configured an integralCES account on its post edit page. " .
						"Use another payment method or contact admin to suggest configuraton.\n", 'iwp' );
                //Process redistribution to wallets
                foreach ( $payment['cart_details'] as $item ) {
                        //Fetch related post
                        $wp_post = get_post( $item["id"] );

                        if ( ! $wp_post->ices_post_account )
                                $errors .= sprintf( $missing_account, $item["id"], $wp_post->post_title );
		}

		if ( $errors ) {
			edd_set_error( 'Gateway', $errors );
	       		// if errors are present, send the user back to the purchase page so they can be corrected
        		edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );
			die;
		}

		// record the pending payment
		$edd_payment_id = edd_insert_payment( $payment );

		$merchant_payment_confirmed = false;

		$error = false;

		// instantiate gateway object
		require_once ( __DIR__ . "/includes/iwp_api.inc");
		require_once ( __DIR__ . "/includes/iwp_pay.inc");

		$api = iwp_api::get_instance();
		$user = $api->Users->get_logged_user();

		// We have a logged user, we can process pay
		if ( $user ) {
			$payment = new iwp\iwp_pay();
			$payment->iwp_distribute_funds( $edd_payment_id, $user->ices_user_account );
		} else {
			// check if accounts is a valid
			$user_account = $account = $api->Accounts->get( $_POST["iwp_ices_user_account"] );

			if ( ! $user_account )
				edd_set_error( 'Gateway', $user_account . ' is not a valid integralCES account.' );
			else {
				// redirect to login
				$listener_url = trailingslashit( home_url( 'index.php' ) ) . '?edd-listener=icesIPN&pid=' . $edd_payment_id . '&uid=' . $user_account->name;
				$merchant_url = $api->get_authorization_url( $listener_url );

				// redirect to server
				if ( $merchant_url ) {
					wp_redirect ( $merchant_url );
					die;
				} else
					edd_set_error( 'Gateway', mwpw_errors::mwpw_errors_get( 'gateway' ) );
			}

		}
	}

	// if errors are present, send the user back to the purchase page so they can be corrected
	edd_send_back_to_checkout( '?payment-mode=' . $purchase_data['post_data']['edd-gateway'] );

}
add_action( 'edd_gateway_ices_gateway', 'iwp\iwp_process_payment' );


/**
 *
 * Listens for EDDlistener authorization, edd_payment id on $_GET["pid"] and account $_GET["uid"]
 */
function iwp_listen_for_ices_ipn() {

	if ( isset( $_GET['edd-listener'] ) && $_GET['edd-listener'] == 'icesIPN' ) {

		if ( ! edd_check_for_existing_payment( $_GET["pid"] ) ) {

			$status = edd_get_payment_status( $_GET["pid"] );

			// fetch PayIn object
			require_once ( __DIR__ . "/includes/iwp_pay.inc");
			$payment = new iwp_pay();

			$errors = $payment->iwp_distribute_funds( $_GET["pid"], $_GET["uid"] );

			if ( ! $errors ) {
				edd_update_payment_status( $_GET["pid"], 'complete' );
				edd_insert_payment_note( $_GET["pid"], __( "Payment has been done", 'iwp' ) );
				edd_send_to_success_page();
			} else {
				edd_set_error( 'Gateway', $errors );
			        edd_send_back_to_checkout( '?payment-mode=ices_gateway' );
			}

		} else {
			wp_redirect( network_site_url( '/' ) ) ;
		}

	}
}
add_action( 'init', 'iwp\iwp_listen_for_ices_ipn' );
~
