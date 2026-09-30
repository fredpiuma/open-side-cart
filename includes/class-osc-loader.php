<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class OSC_Loader{

	protected static $_instance = null;
	
	public $isSideCartPage;

	public $updatedFrom;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	public function __construct(){
		$this->includes();
		$this->hooks();
	}


	public function define( $constant_name, $constant_value ){
		if( !defined( $constant_name ) ){
			define( $constant_name, $constant_value );
		}
	}

	/**
	 * File Includes
	*/
	public function includes(){

		//framework
		require_once OSC_PATH.'/includes/osc-framework/osc-framework.php';
		require_once OSC_PATH.'/includes/class-osc-helper.php';

		require_once OSC_PATH.'/includes/osc-functions.php';
		require_once OSC_PATH.'/includes/class-osc-template-args.php';

		if( $this->is_request( 'frontend' ) ){
			require_once OSC_PATH.'/includes/class-osc-frontend.php';
		}

		if( $this->is_request( 'admin' ) ) {
			require_once OSC_PATH.'/admin/class-osc-admin-settings.php';
		}

		require_once OSC_PATH.'/includes/class-osc-bars.php';

		require_once OSC_PATH.'/includes/class-osc-cart.php';

	}


	/**
	 * Hooks
	*/
	public function hooks(){
		$this->on_install();
	}


	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin':
				return is_admin();
			case 'ajax':
				return defined( 'DOING_AJAX' );
			case 'cron':
				return defined( 'DOING_CRON' );
			case 'frontend':
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}


	/**
	* On install
	*/
	public function on_install(){

		$version_option = 'osc-version';
		$db_version 	= get_option( $version_option );

		// Upstream (4.x) settings migrations were dropped: the fork uses its own option names,
		// so there is nothing to migrate. Add fork migrations here, keyed on Open Side Cart versions.

		if( version_compare( $db_version, OSC_VERSION, '<') ){
			$this->updatedFrom = $db_version;
			//Update to current version
			update_option( $version_option, OSC_VERSION);
		}



		
	}


	public function isSideCartPage(){

		if( !trim(osc_helper()->get_general_option('m-hide-cart')) ){
			$hidePages = array();
		}
		else{
			$hidePages = array_map( 'trim', explode( ',', osc_helper()->get_general_option('m-hide-cart') ) );
		}

		if( !isset( $this->isSideCartPage ) ){
			
			$this->isSideCartPage = !( !empty( $hidePages ) && ( ( in_array( 'no-woocommerce', $hidePages )  && !is_woocommerce() && !is_cart() && !is_checkout() ) || is_page( $hidePages ) ) || ( is_product() && in_array( get_the_id() , $hidePages ) ) );

			foreach ( $hidePages as $page_id ) {
				if( is_single( $page_id ) ){
					$this->isSideCartPage = false;
					break;
				}
			}
		
		}


		return apply_filters( 'osc_is_sidecart_page', $this->isSideCartPage, $hidePages );
	}

}

?>