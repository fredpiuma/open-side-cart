<?php
/**
 * Apply Coupon
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/slider/apply-coupon.php.
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

?>

<div class="osc-sl-heading">
	<span class="osc-toggle-slider osc-slider-close osc-icon-arrow-thin-right"></span>
	<?php _e( 'Apply Coupon', 'open-side-cart' ); ?>
</div>

<div class="osc-sl-body">

	<?php

	//Coupon form
	if( osc_helper()->get_style_option('scf-coup-display') === 'slider' ){
		osc_helper()->get_template( 'global/coupon-form.php' );
	}


	$listHTML = '<div class="osc-clist-cont">%s</div>';;

	$sections = '';

	foreach (  osc_cart()->get_coupons() as $section => $coupons ){

		if( empty( $coupons ) ) continue;

		$sectionContainer = '<div class="osc-clist-section osc-clist-section-%1$s">%2$s</div>';
		
		$label 	= sprintf( '<span class="osc-clist-label">%s</span>', $section === "valid" ? __( 'Available Coupons', 'open-side-cart' ) : __( 'Unavailable Coupons', 'open-side-cart' ) );

		$rows = '';

		ob_start();

		?>

		<?php foreach ( $coupons as $coupon_data ): ?>

			<?php $coupon = $coupon_data['coupon']; ?>

			<div class="osc-coupon-row">
				<span class="osc-cr-code"><?php echo $coupon->get_code(); ?></span>
				<span class="osc-cr-off"><?php printf( __( 'Get %s off', 'open-side-cart' ), $coupon_data['off_value'] )  ?></span>
				<span class="osc-cr-desc"><?php echo $coupon->get_description() ?></span>
				<?php if( $section === 'valid' ): ?>
					<button class="osc-coupon-apply-btn <?php echo osc_frontend()->get_button_classes('text') ?>" value="<?php echo $coupon->get_code() ?>"><?php _e( 'Apply Coupon', 'open-side-cart' ); ?></button>
				<?php endif; ?>
			</div>

		<?php endforeach; ?>

		<?php

		$rows .= ob_get_clean();

		$sections .= sprintf( $sectionContainer, $section, $label.$rows );

	}

	printf( $listHTML, $sections );

	?>

</div>