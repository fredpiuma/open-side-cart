<?php

namespace XooWSC\Framework;

class Xoo_Geolocation{

	private static $_instance;

	/**
	 * API endpoints for looking up user IP address.
	 *
	 * @var array
	 */
	private $ip_lookup_apis = array(
		'ipify'             => 'http://api.ipify.org/',
		'ipecho'            => 'http://ipecho.net/plain',
		'ident'             => 'http://ident.me',
		'whatismyipaddress' => 'http://bot.whatismyipaddress.com',
	);


	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	
	/**
	 * Gets user information on the basis of IP
	 * @return array
	*/

	public function get_data( $from_cookie = true ){
		//Check if data is already in cookie
		if( $from_cookie && isset( $_COOKIE['xoo_user_ip_data'] ) && !empty( $_COOKIE['xoo_user_ip_data']) ){
			return json_decode( stripslashes( $_COOKIE['xoo_user_ip_data'] ), true );
		}

		$ip_address = $this->get_default_ip_address();

		if( !$ip_address ){
			$ip_address = $this->get_external_ip_address();
		}

		$mo_data = array(
			'ip_address' 	=> $ip_address,
			'countryCode' 	=> '',
		);

		$mo_data = array_merge( $mo_data, self::geolocate_via_api( $ip_address ) );

		//Setting data to cookie
		@setcookie( 'xoo_user_ip_data', json_encode( $mo_data ) );

		return $mo_data;		
		
	}


	/**
	 * Gets user IP
	 * @return string
	*/
	public function get_ip_address(){
		return $this->get_data()['ip_address'];
	}


	/**
	 * Gets user Country Code
	 * @return string
	*/
	public function get_country_code(){
		$data = $this->get_data();
		if( isset( $data['countryCode'] ) ){
			return $data['countryCode'];
		}
	}

	/**
	 * Gets user Country Phone Code
	 * @return string
	*/
	public function get_phone_code( $country_code = '' ){

		if( !$country_code ){
			$country_code = $this->get_country_code();
		}

		$phoneCodes = (array) xoo_el_get_country_codes();

		if( isset( $phoneCodes[ $country_code ] ) ){
			return $phoneCodes[ $country_code ];
		}
	}


	/**
	 * Gets user defaul IP address from PHP
	 * @return string
	*/
	public function get_default_ip_address(){
		if ( isset( $_SERVER['HTTP_X_REAL_IP'] ) ) { // WPCS: input var ok, CSRF ok.
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REAL_IP'] ) );  // WPCS: input var ok, CSRF ok.
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) { // WPCS: input var ok, CSRF ok.
			// Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
			// Make sure we always only send through the first IP in the list which should always be the client IP.
			$ip = (string) rest_is_ip_address( trim( current( preg_split( '/,/', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) ) ) ) ); // WPCS: input var ok, CSRF ok.
		} elseif ( isset( $_SERVER['REMOTE_ADDR'] ) ) { // @codingStandardsIgnoreLine
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ); // @codingStandardsIgnoreLine
		} else{
			$ip = '';
		}

		$localhostCheck = array(
		    '127.0.0.1',
		    '::1'
		);

		$ip = in_array( $ip , $localhostCheck ) ? '' : $ip;
		
		return $ip;
	}


	/**
	 * Gets user IP address from web services
	 * @return string
	*/
	public function get_external_ip_address(){

		$external_ip_address = false;

		foreach ( $this->ip_lookup_apis as $service_name => $service_ip ) {

			$response = wp_safe_remote_get( $service_ip, array( 'timeout' => 2 ) );
			if ( ! is_wp_error( $response ) && rest_is_ip_address( $response['body'] ) ) {
				$external_ip_address = $response['body'];
				break;
			}

		}

		return $external_ip_address;

	}


	
	/**
	 * Gets user geolocation
	 * @return array
	*/
	private static function geolocate_via_api( $ip_address ){

	    $location = array(
	        'countryCode' => '',
	        'state'   => '',
	        'city'    => '',
	        'source'  => ''
	    );

	    // Step 2: Check if WooCommerce is active
	    if ( class_exists( 'WooCommerce' ) && class_exists( 'WC_Geolocation' ) ) {

	        $wc_location = WC_Geolocation::geolocate_ip( $ip_address );

	        if ( ! empty( $wc_location['countryCode'] ) ) {
	            $location['countryCode'] 	= $wc_location['country'];
	            $location['state']   		= $wc_location['state'] ?? '';
	            $location['city']    		= $wc_location['city'] ?? '';
	            $location['source']  		= 'woocommerce';


	            return $location; // ✅ Use WooCommerce result
	        }
	    }

	    // Step 3: Fallback to external API (ip-api)
	    $response = wp_remote_get( "http://ip-api.com/json/{$ip_address}" );

	    if ( ! is_wp_error( $response ) ) {

	        $data = json_decode( wp_remote_retrieve_body( $response ), true );

	        if ( ! empty( $data['countryCode'] ) ) {
	            $location['countryCode'] 	= $data['countryCode'];
	            $location['state']   		= $data['region'] ?? '';
	            $location['city']    		= $data['city'] ?? '';
	            $location['source']  		= 'ip-api';

	            return $location;
	        }
	    }

	    // Step 4: Final fallback (unknown)
	    $location['source'] = 'none';

	    return $location;
	}

}


function xoo_geolocate(){
	return Xoo_Geolocation::get_instance();
}



?>