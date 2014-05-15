<?php

/**
 * Pay
 *
 * This class is for manage IntegralCES pay process
 *
 * @package     iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 */
 
namespace iwp;

use integralCES as i;

class iwp_pay {


	/**
	*
	* Will transfer funds from $user_id to post_type='download' 
	* according to edd_payment cart. 
	*
	* @param integer $edd_payment_id
	*/
	public function iwp_distribute_funds( $edd_payment_id, $ices_user_account ) {

		//Get all items in cart
		$edd_payments = edd_get_payment_meta_cart_details( $edd_payment_id, FALSE );

		$api = iwp_api::get_instance();

		$errors = "";

		foreach ( $edd_payments as $item ) {
			// create payment
			$payment = new i\Payment();

			//Fetch related post
			$wp_post = get_post( $item["id"] );

			
			$payment->buyer_account = $api->Accounts->get( $wp_post->ices_post_account );
			$payment->seller_account = $api->Accounts->get( $ices_user_account );

			// fill data
			$payment->amount = (int) $item["item_price"] * 100;
			$payment->concept = $wp_post->post_title;
			$payment = $api->Payments->create( $payment );

			if ( $payment->state == 11 )
				$errors .= $payment->result;

		}

		return $errors;

	}

}
?>
