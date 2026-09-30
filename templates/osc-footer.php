<?php
/**
 * Side Cart Footer
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-footer.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredericomdecastro/open-side-cart
 * @version 4.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::cart_footer() );

/*
 * @hooked osc_footer_extras_html 	- 10
 * @hooked osc_footer_totals_html 	- 20
 * @hooked osc_footer_text_html 	- 30
 * @hooked osc_footer_buttons_html  - 40
*/


do_action( 'osc_footer_content' );

?>