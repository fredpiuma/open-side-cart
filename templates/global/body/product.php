<?php
/**
 * Product
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/body/product.php.
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

$productClasses = apply_filters( 'osc_product_class', $productClasses );
$oneLiner  		= $qtyPriceDisplay === 'one_liner' && $showPprice && $showPtotal && $showPqty && !$updateQty;

?>

<?php ob_start(); ?>

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

<?php $deleteHTML = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php if( $priceSavingsText ): ?>

	<div class="osc-psavings">
		<?php echo $priceSavingsText ?>
	</div>

<?php endif; ?>

<?php $priceSavingsHTML = ob_get_clean(); ?>

<?php ob_start(); ?>

<?php if( $totalSavingsText ): ?>

	<div class="osc-psavings">
		<?php echo $totalSavingsText ?>
	</div>

<?php endif; ?>

<?php $totalSavingsHTML = ob_get_clean(); ?>


<div data-key="<?php echo $cart_item_key ?>" class="<?php echo implode( ' ', $productClasses ) ?>">

	<?php do_action( 'osc_product_start', $_product, $cart_item_key ); ?>

		<?php if( $showPimage ): ?>

			<div class="osc-img-col">
				
				<?php echo $thumbnail; ?>

				<?php if( $deletePosition === 'image' ): ?>

					<?php echo $deleteHTML ?>

				<?php endif; ?>


				<?php do_action( 'osc_product_image_col', $_product, $cart_item_key ); ?>

			</div>

		<?php endif; ?>


	<div class="osc-sum-col">

		<?php do_action( 'osc_product_summary_col_start', $_product, $cart_item_key ); ?>

		<?php if( $showSalesCount && $sales_count > 0 ): ?>
			<div class="osc-sm-sales">
				<?php echo $sales_count.'+ ' ?><?php _e('shoppers have bought this','open-side-cart'); ?>
			</div>
		<?php endif; ?>

		<div class="osc-sm-info">

			<div class="osc-sm-left">

				<?php if( $showPname ): ?>
					<span class="osc-pname"><?php echo $product_name; ?></span>
				<?php endif; ?>
				
				<?php if( $showPmeta ) echo $product_meta ?>

				<?php if( $quickView && $_product->get_type() === 'variation' ): ?>
					<span class="osc-toggle-slider osc-toggle-qv" data-slider="quickview" data-cart_key="<?php echo $cart_item_key; ?>"><?php echo $quickViewTxt ?></span>
				<?php endif; ?>

				<!-- Quantity -->

				<?php if( $oneLiner ): ?>

					<div class="osc-qty-price">
						<span><?php echo $cart_item['quantity']; ?></span>
						<span>X</span>
						<span><?php echo $product_price; ?></span>
						<span>=</span>
						<span><?php echo $product_subtotal ?></span>
					</div>

					<?php if( !$isGift ) echo $totalSavingsHTML; ?>

				<?php else: ?>

					<?php if( $showPqty && !$updateQty ): ?>
						<span class="osc-sml-qty"><?php _e( 'Qty:', 'open-side-cart' ) ?> <?php echo $cart_item['quantity']; ?></span>
					<?php endif; ?>

					<div class="osc-priceBox">

						<?php if( !$updateQty ): ?>
							<?php echo $priceSavingsHTML; ?>
						<?php endif; ?>


						<?php if( $showPprice ): ?>
							<div class="osc-pprice">
								<?php echo __( 'Price: ', 'open-side-cart' ) . $product_price ?>
							</div>
						<?php endif; ?>

						<?php if( $updateQty ): ?>
							<?php echo $priceSavingsHTML; ?>
						<?php endif; ?>


					</div>

				<?php endif; ?>

			

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

				<?php endif; ?>

				<!-- End Quantity -->

				

			</div>

			

		

			<div class="osc-sm-right">

				<?php if( isset( $cart_item['osc_gift'] ) ): ?>

					<span class="osc-gift-ban"><?php _e( 'Free Gift', 'open-side-cart' ) ?></span>

				<?php endif; ?>

				<div class="osc-sm-right-tools">

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

					<?php if( $showPdel && $deletePosition === 'default' ): ?>

						<?php echo $deleteHTML ?>

					<?php endif; ?>

				</div>

				<?php if( !$oneLiner || $isGift ): ?>
					<?php echo $totalSavingsHTML; ?>
				<?php endif; ?>

				<?php if( $showPtotal && !$oneLiner ): ?>
					<span class="osc-smr-ptotal"><?php echo $product_subtotal ?></span>
				<?php endif; ?>

				

			</div>

		</div>

		<?php if( $notEligibleForRewardsTxt ): ?>
			<div class="osc-not-eligbforreward">
				<?php echo $notEligibleForRewardsTxt ?>
			</div>
		<?php endif; ?>

		<?php do_action( 'osc_product_summary_col_end', $_product, $cart_item_key ); ?>

	</div>

	<?php do_action( 'osc_product_end', $_product, $cart_item_key ); ?>

</div>