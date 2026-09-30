<?php

use OpenSideCart\Framework\OSC_FW_Helper;

class OSC_Helper extends OSC_FW_Helper{

	protected static $_instance = null;

	public static function get_instance( $slug, $path, $helperArgs = array() ){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $slug, $path, $helperArgs );
		}
		return self::$_instance;
	}

	public function __construct(...$args){
		parent::__construct(...$args);
	}


	public function get_general_option( $subkey = '' ){
		return $this->get_option( 'osc-gl-options', $subkey );
	}

	public function get_style_option( $subkey = '' ){
		return $this->get_option( 'osc-sy-options', $subkey );
	}

	public function get_advanced_option( $subkey = '' ){
		return $this->get_option( 'osc-av-options', $subkey );
	}

	public function get_rewards_option( $subkey = '' ){
		return $this->get_option( 'osc-rewards-options', $subkey );
	}


	public function register_string_for_translation( $string, $string_name ){

		//WPML
		if( class_exists( 'SitePress' ) ){
			do_action(
				'wpml_register_single_string',
				$this->slug,
				$this->slug.'-'.$string_name,
				$string
			);
		}

		//Polylang
		if( function_exists('pll_register_string') ){
			pll_register_string( $string_name, $string, $this->slug );
		}
	}

	public function translate_registered_string( $string, $string_name ){

		//WPML
		if( class_exists( 'SitePress' ) ){
			return apply_filters(
				'wpml_translate_single_string',
				$string,
				$this->slug,
				$this->slug.'-'.$string_name
			);
		}

		//Polylang
		if( function_exists( 'pll__' ) ){
			return pll__( $string );
		}
		

		return $string;
	}

	public function get_default_button_themes(){
		return array(
			'theme_default1' => osc_helper()->get_button_values( array(
				'theme_id' => 'theme_default1',
				'title' => 'Default Theme #1'
			) ),
			'theme_default2' => osc_helper()->get_button_values( array(
				'theme_id' => 'theme_default2',
				'title' 	=> 'Default Theme #2',
				'bgColor' 	=> '#dde6ed',
				'txtColor' 	=> '#27374d',
				'size_type' => 'auto',
				'border' 	=> array(
					'size' => 2,
					'color' => '#27374d'
				),
				'hover' => array(
					'bgColor' 	=> '#27374d',
					'txtColor' 	=> '#dde6ed',
					'border' 	=> array(
						'size' => 2,
						'color' => '#dde6ed'
					),
				)
			) ),
			'theme_default3' => osc_helper()->get_button_values( array(
				'theme_id' 		=> 'theme_default3',
				'title' 		=> 'Auto width #3',
				'size_type' 	=> 'auto',
				'text' 			=> array(
					'fontSize' 	=> 14,
				),
				'padding_v' 	=> 8,
				'padding_h' 	=> 15
			) ),

			'theme_default4' => osc_helper()->get_button_values( array(
				'theme_id' 		=> 'theme_default4',
				'title' 		=> 'Banner Theme #4',
				'size_type' 	=> 'auto',
				'text' 			=> array(
					'fontSize' 	=> 13,
				),
				'padding_v' 	=> 4,
				'padding_h' 	=> 10
			) ),
		);
	}

}

function osc_helper(){
	return OSC_Helper::get_instance( 'open-side-cart', OSC_PATH );
}
osc_helper();

?>