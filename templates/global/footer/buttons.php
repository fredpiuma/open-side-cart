<?php
/**
 * Footer Buttons
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/footer/buttons.php.
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


extract( OSC_Template_Args::footer_buttons() );

do_action( 'osc_before_footer_btns' );

$buttonHTML = '<a href="%1$s" class="%2$s" %4$s>%3$s</a>';

?>
<div class="osc-ft-buttons-cont">

	<?php foreach ( $buttons as $key => $button_data ){

		if( !$button_data['label'] ) continue;
		$button_data['class'][] = 'osc-ft-btn-'.$key;

		printf(
			$buttonHTML,
			esc_attr( $button_data['url'] ),
			implode( ' ', $button_data['class'] ),
			wp_kses_post( $button_data['label'] ),
			isset( $button_data['data'] ) ? $button_data['data'] : '',
		);

	} ?>

</div>

<?php do_action( 'osc_after_footer_btns' ); ?>

<div class="osc-payment-btns">
	<?php do_action( 'osc_payment_buttons' ); ?>
</div>