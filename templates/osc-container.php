<?php
/**
 * Side Cart Container
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-container.php.
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

extract( OSC_Template_Args::cart_container() );

?>



<div class="osc-container">


	<?php osc_cart()->print_notices_html(); ?>
	

	<?php if( $showBasket !== 'always_hide' ): ?>

	<div class="osc-basket">

		<?php if( $showCount === "yes" ): ?>
			<span class="osc-items-count"><?php echo osc_cart()->get_cart_count() ?></span>
		<?php endif; ?>

		<?php if( $customBasketIcon ): ?>
			<span class="osc-bki"><img src="<?php echo $customBasketIcon ?>"></span>
		<?php else: ?>
			<span class="osc-bki <?php echo $basketIcon ?>"></span>
		<?php endif; ?>

		<?php do_action( 'osc_basket_content' ); ?>

	</div>

	<?php endif; ?>


	<div class="osc-header">

		<?php do_action( 'osc_header_start' ); ?>

		<?php osc_helper()->get_template( 'osc-header.php' ); ?>

		<?php

		/*
		* @hooked osc_progress_bar  - 40
		*/

		do_action( 'osc_header_end' );

		?>

	</div>


	<div class="osc-body">

		<?php do_action( 'osc_body_start' ); ?>

		<?php osc_helper()->get_template( 'osc-body.php' ); ?>

		<?php do_action( 'osc_body_end' ); ?>

	</div>

	<div class="osc-footer">

		<?php do_action( 'osc_footer_start' ); ?>

		<?php osc_helper()->get_template( 'osc-footer.php' ); ?>

		<?php do_action( 'osc_footer_end' ); ?>

	</div>

	<?php osc_helper()->get_template( 'global/preloader.php' ); ?>

	<?php if( !$isDrawerEmpty && !wp_is_mobile() ): ?>
		<span class="osc-icon-chevron-<?php echo $drawerChevron; ?> osc-toggle-drawer osc-dtg-icon"></span>
	<?php endif; ?>

</div>