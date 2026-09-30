<?php
/**
 * Product Quickview
 *
 * This template can be overridden by copying it to yourtheme/templates/side-cart-woocommerce/global/quickview-product.php.
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


$qv_show = apply_filters( 'xoo_qv_data_to_show', array( 'images', 'title', 'price', 'add_to_cart', 'meta' ) );

?>

<div class="xoo-wsc-qv-container">

	<?php do_action( 'xoo_wsc_qv_start', $product ); ?>

	<?php if ( in_array( 'images', $qv_show, true ) ): ?> 
	
		<div class="xoo-wsc-qv-img">
			
			<?php

				echo wp_get_attachment_image(
				    $product->get_image_id(),
				    'woocommerce_single',
				    false,
				    array(
				        'class' => 'xoo-qv-product-image',
				        'alt'   => $product->get_name(),
				    )
				);
			?>

		</div>

	<?php endif; ?>


	<?php if ( in_array( 'images', $qv_show, true ) ): ?>

		<div class="xoo-wsc-qv-title"><?php echo $product->get_name() ?></div>

	<?php endif; ?>

	<?php if ( in_array( 'price', $qv_show, true ) ): ?>

		<div class="xoo-wsc-qv-price"><?php echo $product->get_price_html(); ?></div>

	<?php endif; ?>


	<?php if ( in_array( 'add_to_cart', $qv_show, true ) ): ?>
	
		<div class="xoo-wsc-qv-atc"><?php woocommerce_template_single_add_to_cart(); ?></div>

	<?php endif; ?>

	<?php if ( in_array( 'meta', $qv_show, true ) ): ?>

		<div class="xoo-wsc-qv-meta"><?php woocommerce_template_single_meta(); ?></div>

	<?php endif; ?>

	<?php do_action( 'xoo_wsc_qv_end', $product ); ?> 

	

</div>