<?php
/**
 * Shipping Bar
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/header/shipping-bar.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredpiuma/open-side-cart
 * @version 4.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::shipping_bar() );

if( !$showBar || empty( $data ) ) return;

?>

<div class="osc-ship-bar-cont">
	<span class="osc-sb-txt"><?php echo $text; ?></span>
	<div class="osc-sb-bar">
		<span style="width: <?php esc_attr_e( $data['fill_percentage'] ); ?>%"></span>
	</div>
</div>