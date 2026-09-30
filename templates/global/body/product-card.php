<?php
/**
 * Product
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/body/product-card.php.
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

$productClasses = apply_filters( 'osc_product_class', $productClasses );

$visible 		= osc_helper()->get_style_option('scbp-card-visible');
$details 		= osc_helper()->get_style_option('scbp-card-back'); 

$delHTML = $qtyHTML = $totalHTML = $nameHTML = $metaHTML = $imageHTML = $priceHTML = '';

$imageHTML 		= $showPimage ? $thumbnail : '';
$nameHTML 		= $showPname ? sprintf( '<span class="osc-pname">%1$s</span>', $product_name ) : '';
$totalHTML 		= $showPtotal && !$oneLiner ? sprintf( '<span class="osc-card-ptotal">%1$s</span>', $product_subtotal ) : '';
$metaHTML 		= $showPmeta ? $product_meta : '';
$viewLinkHTML 	= sprintf( '<a class="osc-smr-link" href="%1$s">%2$s</a>', $product_permalink, '<i class="osc-icon-external-link"></i>'. __( 'View', 'open-side-cart' ) );
$priceHTML 		= $showPprice && !$oneLiner ? sprintf( '<span class="osc-card-price">%1$s</span>', __( 'Price: ', 'open-side-cart' ) . $product_price ) : '';

?>


<?php ob_start(); //Delete HTML ?>

<?php if( $showPdel ): ?>

	<?php if( $deleteType === 'icon' ): ?>
		<div class="osc-tooltip-cont osc-del-cont">
			<span class="osc-smr-del <?php echo $delete_icon ?> osc-has-tooltip"></span>
			<span class="osc-tooltip"><?php echo $deleteText ?></span>
		</div>
	<?php else: ?>
		<span class="osc-smr-del osc-del-txt"><?php echo $deleteText ?></span>
	<?php endif; ?>

<?php endif; ?>

<?php $delHTML = ob_get_clean(); ?>


<?php ob_start(); // Quantity & Price HTML ?>


<div class="osc-qty-box-cont">

	<?php if( $showPqty && $updateQty ): ?>

		<?php

		osc_quantity_input(
			array(
				'input_value'  	=> $cart_item['quantity'],
				'quantity'  	=> $cart_item['quantity'],
				'max_value'    	=> $_product->get_max_purchase_quantity(),
				'min_value'    	=> '0',
				'product_name' 	=> $_product->get_name(),
			),
			$_product
		);

		?>
		
	<?php else: ?>

		<?php if( $oneLiner ): ?>

			<div class="osc-qty-price">
				<span><?php echo $cart_item['quantity']; ?></span>
				<span>X</span>
				<span><?php echo $product_price; ?></span>
				<span>=</span>
				<span><?php echo $product_subtotal ?></span>
			</div>
			

		<?php else: ?>

			<?php if( $showPqty ): ?>
				<div class="osc-sml-qty"><?php _e( 'Qty:', 'open-side-cart' ) ?> <span><?php echo $cart_item['quantity']; ?></span></div>
			<?php endif; ?>

		<?php endif; ?>


	<?php endif; ?>

	<?php echo $totalHTML ?>

</div>

<?php $qtyHTML = ob_get_clean(); ?>

<?php ob_start(); ?>
<?php echo in_array( 'name', $details ) ? $nameHTML : '' ?>
<?php echo in_array( 'meta', $details ) ? $metaHTML : '' ?>
<?php echo in_array( 'link', $details ) ? $viewLinkHTML : '' ?>
<?php echo in_array( 'price', $details ) ? $priceHTML : '' ?>
<?php echo in_array( 'qty', $details ) ? $qtyHTML : '' ?>
<?php do_action( 'osc_product_card_back', $_product, $cart_item_key ); ?>
<?php $backHTML = ob_get_clean(); ?>

<?php

$hasBack 		= $visible !== 'all_on_front' && trim($backHTML);
$allFront 		= $visible === 'all_on_front';

if( $hasBack ){
	$productClasses[] = 'osc-has-back';
}
$productClasses 	= apply_filters( 'osc_product_class', $productClasses, $_product );


?>




<div data-key="<?php echo $cart_item_key ?>" data-product_id="<?php echo $product_id ?>" class="<?php echo implode( ' ', $productClasses ) ?>">

	<?php do_action( 'osc_product_start', $_product, $cart_item_key ); ?>

	<div class="osc-card-cont">

		<div class="osc-card-actionbar">

			<?php if( $saveforLaterEnabled ): ?>

				<div class="osc-savl-tooltip osc-tooltip-cont <?php if( OSC_Template_Args::$isSaveForLaterLoginSlider ) echo 'xoo-el-login-tgr' ?>">

					<?php if( OSC_Template_Args::$saveForLaterNeedsLogin && !OSC_Template_Args::$isSaveForLaterLoginSlider ): ?>
						<a class="<?php echo $save_icon; ?> osc-has-tooltip" href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"></a>
					<?php else: ?>
						<span class="osc-save <?php echo $save_icon; ?> osc-has-tooltip"></span>
					<?php endif; ?>

					<span class="osc-tooltip"><?php _e( 'Save for Later', 'open-side-cart' ) ?></span>
				</div>

			<?php endif; ?>

			<?php echo $delHTML ?>

		</div>

		<div class="osc-img-col magictime">

			<?php echo $imageHTML ?>

			<?php do_action( 'osc_product_image_col', $_product, $cart_item_key ); ?>

		</div>


		<?php if( $hasBack ): ?>

		<div class="osc-sm-back-cont">

			<div class="osc-sm-back">

				<?php echo $backHTML ?>

			</div>

		</div>

		<?php endif; ?>
		
	</div>


	<div class="osc-sm-front">

		<span class="osc-sm-emp"></span>

		<?php echo $allFront || !in_array( 'name', $details ) ? $nameHTML : '' ?>
		<?php echo $allFront || !in_array( 'price', $details ) ? $priceHTML : '' ?>
		<?php echo $allFront || !in_array( 'meta', $details ) ? $metaHTML : '' ?>
		<?php echo $allFront || !in_array( 'qty', $details ) ? $qtyHTML : '' ?>

		<?php do_action( 'osc_product_card_front', $_product, $cart_item_key ); ?>
		
	</div>


	<?php if( isset( $cart_item['osc_gift'] ) ): ?>

		<span class="osc-gift-ban"><?php _e( 'Free Gift', 'open-side-cart' ) ?></span>

	<?php endif; ?>


	<?php do_action( 'osc_product_end', $_product, $cart_item_key ); ?>

</div>