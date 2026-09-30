<?php
/**
 * Totals
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/footer/totals.php.
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

extract( OSC_Template_Args::footer_totals() );

?>

<?php if( WC()->cart->is_empty() ) return; ?>

<div class="osc-ft-totals">
	<?php foreach( $totals as $key => $data ): ?>
		<div class="osc-ft-amt osc-ft-amt-<?php echo $key; ?> <?php echo isset( $data['action'] ) ? 'osc-'.$data['action'] : '' ?>">
			<span class="osc-ft-amt-label"><?php echo $data['label'] ?></span>
			<span class="osc-ft-amt-value"><?php echo $data['value'] ?></span>
		</div>
	<?php endforeach; ?>

	<?php do_action( 'osc_totals_end' ); ?>

</div>