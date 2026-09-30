<?php
/**
* Plugin Name: Open Side Cart for WooCommerce
* Plugin URI: https://github.com/fredericomdecastro/open-side-cart
* Description: Manage your cart from just a click away. Open source fork of Woocommerce Side Cart Premium 4.9.1 by XootiX.
* Version: 1.0.0
* Author: Frederico de Castro
* Author URI: https://github.com/fredericomdecastro
* License: GPL-2.0-or-later
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: open-side-cart
* Domain Path: /languages
* Requires Plugins: woocommerce
* Tags: floating cart, cart popup, woocommerce, cart
*
* This program is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the Free Software
* Foundation; either version 2 of the License, or (at your option) any later version.
*
* Based on Woocommerce Side Cart Premium, Copyright (C) XootiX.
* Modifications Copyright (C) 2026 Frederico de Castro.
*/


//Exit if accessed directly
if( !defined('ABSPATH') ){
	return;
}


define( 'OSC_PLUGIN_FILE', __FILE__ );
define( "OSC_PATH", plugin_dir_path( __FILE__ ) ); // Plugin path
define( "OSC_PLUGIN_BASENAME", plugin_basename( __FILE__ ) );
define( "OSC_URL", untrailingslashit( plugins_url( '', __FILE__ ) ) ); // plugin url
define( "OSC_VERSION", "1.0.0" ); //Plugin version


require_once OSC_PATH.'/includes/osc-framework/osc-framework.php';

if ( !function_exists('osc_init') ) {
	
	/**
	 * Initialize
	 *
	 * @since    1.0.0
	 */
	function osc_init(){
		
		if( !class_exists( 'woocommerce' ) ) return;

		do_action( 'osc_before_plugin_activation' );

		if ( ! class_exists( 'OSC_Loader' ) ) {
			require_once 'includes/class-osc-loader.php';
		}

		osc();

		
	}
	add_action( 'plugins_loaded','osc_init', 14 );

	function osc(){
		return OSC_Loader::get_instance();
	}

}