<?php
/**
 * Side Cart Slider
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-slider.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredericomdecastro/open-side-cart
 * @version 4.9
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::slider() );

?>
<div class="osc-slider">

	<?php osc_cart()->print_notices_html(); ?>

	<?php if( $showShipping ): ?>

		<div class="osc-sl-content osc-sl-shipping" data-slider="shipping">

			<?php do_action( 'osc_slider_shipping_start' ); ?>

			<?php osc_helper()->get_template( 'global/slider/calculate-shipping.php' ); ?>

			<?php do_action( 'osc_slider_shipping_end' ); ?>

		</div>

	<?php endif; ?>

	<?php if( $showCoupon ): ?>

		<div class="osc-sl-content osc-sl-coupon"  data-slider="coupon">

			<?php do_action( 'osc_slider_coupon_start' ); ?>

			<?php osc_helper()->get_template( 'global/slider/apply-coupon.php' ); ?>

			<?php do_action( 'osc_slider_coupon_end' ); ?>

		</div>

	<?php endif; ?>

	<?php if( $showSaveLater ): ?>

		<div class="osc-sl-content osc-sl-savelater"  data-slider="savelater">

			<?php do_action( 'osc_slider_savelater_start' ); ?>

			<?php osc_helper()->get_template( 'global/slider/save-for-later.php' ); ?>

			<?php do_action( 'osc_slider_savelater_end' ); ?>

		</div>

	<?php endif; ?>


	<?php if( true ): ?>

		<div class="osc-sl-content osc-sl-quickview"  data-slider="quickview">

			<?php do_action( 'osc_slider_quickview_start' ); ?>

			<?php osc_helper()->get_template( 'global/slider/quickview.php' ); ?>

			<?php do_action( 'osc_slider_quickview_end' ); ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'osc_slider_end' ); ?>
	
	<?php osc_helper()->get_template( 'global/preloader.php' ); ?>
	
</div>