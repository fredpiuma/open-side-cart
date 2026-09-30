<?php
/**
 * Product quick view
 *
 * This template can be overridden by copying it to yourtheme/templates/side-cart-woocommerce/global/slider/quickview.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://docs.xootix.com/side-cart-woocommerce/
 * @version 4.9
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<div class="xoo-wsc-sl-heading">
	<span class="xoo-wsc-toggle-slider xoo-wsc-slider-close xoo-wsc-icon-arrow-thin-right"></span>
	<?php echo  xoo_wsc_helper()->get_general_option('sct-qv-txt') ?>
</div>

<div class="xoo-wsc-sl-body">
	
</div>