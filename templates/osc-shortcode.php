<?php
/**
 * Basket Shortcode
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-shortcode.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredericomdecastro/open-side-cart
 * @version 4.0
 */


if ( ! defined( 'ABSPATH' ) || !WC() || !WC()->cart ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::cart_shortcode() );
?>


<div class="osc-sc-cont">
	<div class="osc-cart-trigger">

		<?php if( $subtotal === 'yes' ): ?>
			<span class="osc-sc-subt">
				<?php echo WC()->cart->get_cart_subtotal() ?>
			</span>
		<?php endif; ?>


		<div class="osc-sc-bkcont">
			
			<?php if( $icon === 'yes' ): ?>

				<?php if( $customBasketIcon ): ?>
					<span class="osc-sc-bki"><img src="<?php echo esc_url($customBasketIcon) ?>"></span>
				<?php else: ?>
					<span class="osc-sc-bki <?php echo esc_html($basketIcon) ?>"></span>
				<?php endif; ?>

			<?php endif; ?>

			<?php if( $count === 'yes' ): ?>
				<span class="osc-sc-count"><?php echo esc_html( osc_cart()->get_cart_count() ) ?></span>
			<?php endif; ?>

		</div>

		<?php do_action( 'osc_cart_shortcode_content' ); ?>

	</div>
</div>