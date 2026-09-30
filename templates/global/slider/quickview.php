<?php
/**
 * Product quick view
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/slider/quickview.php.
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

?>

<div class="osc-sl-heading">
	<span class="osc-toggle-slider osc-slider-close osc-icon-arrow-thin-right"></span>
	<?php echo  osc_helper()->get_general_option('sct-qv-txt') ?>
</div>

<div class="osc-sl-body">
	
</div>