<?php
/**
 * Footer Extras
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/footer/extras.php.
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

extract( OSC_Template_Args::footer_extras() );

?>

<?php

//Empty cart link
if( $emptyCartLink && !WC()->cart->is_empty() ){
	echo '<span class="osc-ecl">'.__( 'Empty Cart', 'open-side-cart' ).'</span>';
}

?>

<div class="osc-ft-extras">

	<?php

	//Coupon form
	if( $showCoupon && $couponLoc === 'main' ){
		osc_helper()->get_template( 'global/coupon-form.php' );
	}

	?>


	<?php if( $couponLoc === 'slider' && $showCoupon && !WC()->cart->is_empty() ): ?>

		<div class="osc-ftx-row osc-ftx-coupon">

			<span class="osc-ftx-icon <?php echo $couponIcon; ?>"></span>

			<?php if( WC()->cart->get_coupons() ): ?>

				<div class="osc-ftx-coups">
					<div>
						<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ): ?>
							<div class="osc-remove-coupon" data-code="<?php echo $code ?>"><?php echo $coupon->get_code() ?><span class=" osc-icon-cross"></span></div>
						<?php endforeach; ?>
					</div>
					<span class="osc-toggle-slider" data-slider="coupon"><?php _e( 'Apply', 'open-side-cart' ); ?></span>
				</div>

			<?php else: ?>

				<span class="osc-toggle-slider" data-slider="coupon"><?php _e( 'Have a Promo Code?', 'open-side-cart' ); ?></span>

			<?php endif; ?>

		</div>

	<?php endif; ?>

	<?php do_action( 'osc_extras_content' ); ?>
	
</div>