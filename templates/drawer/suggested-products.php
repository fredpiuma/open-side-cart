<?php
/**
 * Suggested products Drawer
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/drawer/suggested-products.php.
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

extract( OSC_Template_Args::suggested_products_drawer() );

if( $enable !== 'yes' || wp_is_mobile()  ) return;

?>

<div class="osc-dr-content osc-dr-sp" data-drawer="suggested-products">

	<?php osc_helper()->get_template( 'drawer/header.php', array( 'heading' => $heading ) ); ?>

	<div class="osc-dr-body">

		<?php osc_helper()->get_template( 'global/footer/suggested-products.php' ); ?>

	</div>

</div>