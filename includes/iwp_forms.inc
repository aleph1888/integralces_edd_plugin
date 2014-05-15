<?php

/**
 * forms
 *
 * Provides methods to display integralCES\User form.
 *
 * @package		iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 * @uses		iwp_print.inc
 */
 
namespace iwp;

require_once (  __DIR__ . '/iwp_print.inc' );


class iwp_forms {

	/**
	 *
	 * Returns echoable TABLE with two columns and one TR for every field representing ices\user input data form.
	 *
	 * @params iwp_user $defaults
	 * @params bool $display style.visibility If False a button to toggle visibility will be shown
	 *
	 * @uses iwp_fields.inc\iwp_get_fields()
	 * @uses iwp_print.inc\iwp_print_button()
	 * @uses iwp_print.inc\iwp_print_section()
	 *
	 * @return string
	 */	 
	static function iwp_show_user_section ( $defaults, $display = true ) {

		// main block
		$display_block = TRUE;
		$yFields = iwp_get_fields( 'User' );
		$output .= iwp_print_section( "integralCES_user", $defaults, $yFields, $display_block );

		return $output;

	}


	/**
	 *
	 * Shows Login and Register links, if need
	 *
	 * @return string
	 */
	static function iwp_show_wordpress_login() {
		if ( ! is_user_logged_in() ) {
			return	iwp_print_link ( __("Login", 'iwp'), wp_login_url() ) . "<br>" .
				iwp_print_link ( __("Register", 'iwp'), wp_registration_url() );
		}
	}
}

?>
