<?php 

/**
 * Errors
 *
 * This class is for manage errors through EDD_Session class
 *
 * @package     iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 */

namespace iwp;

class iwp_errors {

	/**
	 *
	 * Clears errors stack.
	 *
	 * @params string $str_key Prefix for error in stack. 	 
	 */
	static public function iwp_errors_clean( $str_key ) {

		iwp_errors::iwp_errors_add( $str_key , "" );

	}

	/**
	 * Appends a new error to errors stack
	 *
	 * @param string $str_error
	 * @params string $str_key Prefix for error in stack. 	 
	 *
	 * @uses EDD_Session::set()
	 */
	static public function iwp_errors_append( $str_key, $str_error ) {

		$str_error = EDD()->session->get( 'iwp_' . $str_key ) . "<br>" . $str_error;
		EDD()->session->set( 'iwp_' . $str_key, $str_error ); 

	}

	/**
	 * Adds a new error to errors stack, erasing all previous errors.
	 *
	 * @param string $str_key
	 * @param string $str_error
	 *
	 * @uses EDD_Session::set()
	 */
	static public function iwp_errors_add( $str_key, $str_error ) {

		EDD()->session->set( 'iwp_' . $str_key , $str_error ); 

	}

	/**
	 *
	 * Get all errors from errors stack.
	 *
	 *
	 * @params string $str_key Prefix for error in stack. 
	 *
	 * @return string
	 *
	 * @uses EDD_Session::get()
	 */
	static public function iwp_errors_get( $str_key ) {

		return EDD()->session->get( 'iwp_' . $str_key );

	}

	/**
	 *
	 * Returns nice <div> block with errors stored on stack.
	 *
	 * @params string $str_key Prefix for error in stack. 
	 *
	 * @return string
	 */
	static public function iwp_errors_print( $str_key ) {

		echo "<br><div class='message'>" . 
				htmlspecialchars( self::iwp_errors_get( $str_key ) ) .
			"</div>";

	}	

}

?>
