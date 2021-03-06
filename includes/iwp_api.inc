<?php

/**
 * Api
 *
 * Singleton class for plugin to access icesSDK0 extending integralCES\cesApi.
 *
 * @package     iwp
 * @copyleft    Copyleft (l) 2014, Enredaos.net
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0
 */

namespace iwp;

include 'icesSDKv0/cesApi.inc';

use integralCES as i;


class iwp_api extends i\cesApi {

	static private $instance;

	public static function get_instance() {

		if ( self::$instance == null ) {

			self::$instance = new iwp_api;

			self::$instance->Config->consumer_key = IWP_client_id;
			self::$instance->Config->consumer_secret = IWP_password;
			self::$instance->Config->base_url = IWP_base_url;

		}

		return self::$instance;

	}

}




