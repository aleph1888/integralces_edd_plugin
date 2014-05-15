<?php

/**
 *
 * Provides some auxiliar functions related to ices fields management.
 *
 * @package     iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 */

/**
 *
 * This is for automatized form generation.
 *
 * Returns array of strings containing integralCES\{User} minimum required fields.
 *
 * Also provide localization in proper languages/iwpXXXX.po file, noting that every field
 * should be together with a {msgid = "fieldname_description"} translation. Set space if you don't need it.
 *
 * @params string $str_type a value in ( 'Account' )
 *
 * @return array
 */
function iwp_get_fields( $str_type ) {

	switch ( $str_type ) {
		case "User":
			$yFields =  array( "ices_user_account" );
			break;
	}

	return $yFields;

}


/**
 *
 * Return true or false wether any of the specified fields has changed between $values and $entity
 *
 * @params array $yFields [see iwp_get_fields]
 * @params object $entity  All fields presents in $yFields will be expected in this object $entity
 * @params array or object $values Function will manage an object(like iwp_user) or an array(like $_POST).
 *				   Notice: If is_array, then will expect keys prefixed by 'iwp' literal
 *
 * @return bool
 */
function iwp_has_changed_fields( $yFields, $entity, $values ) {

	if ( is_array( $values ) ) {

		foreach ( $yFields as $field ) {
			$has_changed = ( $values["iwp_{$field}"] && ( $values["iwp_{$field}"] != $entity->$field ) );
			if ( $has_changed )
				return true;

		}
	}
	else {

		foreach ( $yFields as $field ) {
			$has_changed = ( $values->$field && ( $values->$field != $entity->$field ) );
			if ( $has_changed )
				return true;
		}

	}

	return false;
}

?>
