<?php
/**
 * Side Cart Footer
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/coupon-form.php.
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

if( WC()->cart->is_empty() ) return;

?>

<form class="osc-sl-apply-coupon">
	<input type="text" name="osc-slcf-input" placeholder="<?php _e( 'Enter Promo Code', 'open-side-cart' ); ?>">
	<button class="<?php echo osc_frontend()->get_button_classes('text') ?>" type="submit"><?php _e( 'Submit', 'open-side-cart' ); ?></button>
</form>

<?php if( !empty( WC()->cart->get_coupons() ) ): ?>
	<div class="osc-sl-applied">
		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ): ?>
			<div>
				<span class="osc-slc-saved"><?php  echo __( 'Saved', 'open-side-cart' ). ' '. wc_price( WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax ) ) ?></span>
				<span class="osc-slc-remove">
					<?php echo $code ?>
					<span class="osc-remove-coupon" data-code="<?php echo $code ?>"><?php _e( '[Remove]', 'open-side-cart' ) ?></span>
				</span>
			</div>
		<?php endforeach; ?>
	</div>
<?php endif; ?>