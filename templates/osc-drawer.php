<?php
/**
 * Side Cart Slider
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-drawer.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredpiuma/open-side-cart
 * @version 4.9
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::drawer() );

?>
<div class="osc-drawer">

	<?php osc_helper()->get_template( 'drawer/suggested-products.php' ); ?>

	<span class="osc-icon-chevron-<?php echo $drawerChevron; ?> osc-toggle-drawer osc-dtg-icon"></span>

	<?php osc_helper()->get_template( 'global/preloader.php' ); ?>
	
</div>