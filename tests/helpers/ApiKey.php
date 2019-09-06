<?php

use WeglotWP\Services\Option_Service_Weglot;

class ApiKey  {

	protected static $api_key = null;

	public static function get_api_key(){
		if(self::$api_key){
			return self::$api_key;
		}

		$api_key = getenv( 'API_KEY' );

		$option_service = new Option_Service_Weglot();

		$response = $option_service->get_options_from_api_with_api_key( $api_key );

		if($response['success']){
			self::$api_key = $response['result']['api_key'];
		}

		return $api_key;
	}
}
