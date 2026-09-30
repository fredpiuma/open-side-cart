<?php
/**
 * Save for Later
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/slider/save-for-later.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredpiuma/open-side-cart
 * @version 4.6
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::saved_for_later() );

?>

<div class="osc-sl-heading">
	<span class="osc-toggle-slider osc-slider-close osc-icon-arrow-thin-right"></span>
	<span class="osc-slh-txt"><?php echo $heading ?></span>
</div>

<div class="osc-sl-body">

	<?php if( OSC_Template_Args::$saveForLaterNeedsLogin ): ?>

		<div class="osc-savl-login">

			<?php if( OSC_Template_Args::$isSaveForLaterLoginSlider ){
				$loginHTMLarg1 = '<span class="xoo-el-login-tgr">';
				$loginHTMLarg2 = '</span>';
			}
			else{
				$loginHTMLarg1 = '<a href="'.get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ).'">';
				$loginHTMLarg2 = '</a>';
			}

			?>

			<?php printf( __( 'Please %1$slogin%2$s to see your saved products.', 'open-side-cart' ), $loginHTMLarg1, $loginHTMLarg2 ); ?>

		</div>


	<?php else: ?>

		<?php if( empty( $savedItems ) ): ?>

			<span class="osc-savl-empty">No items to show</span>

		<?php else: ?>	

			<div class="osc-savl-container osc-savl-<?php echo $style ?>">

				<?php foreach ( $savedItems  as $cart_key => $item ): ?>

					<?php

					$product 			= $item['data'];

					$product_permalink 	= $product->is_visible() ? $product->get_permalink() : '';

					$thumbnail 			= apply_filters( 'osc_saved_for_later_product_thumbnail', $product->get_image(), $product );

					$thumbnail 			= $product_permalink && $showPLink ? sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ) : $thumbnail;

					$product_name 		= $product_permalink && $showPLink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $product->get_name() ) : $product->get_name();

					$product_price 		= apply_filters( 'osc_saved_for_later_product_price', wc_price( $product->get_price() ), $product );

					$product_name 		= $product->get_name();

					if( $product->get_type() === 'variation' ){
						if( $pnameVariation === "no" ){
							$product_name = $product->get_title();
							$item['data']->set_name( $product->get_title() );
						}
					}
					
					$product_name 		= $product_permalink && $showPLink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $product_name ) : $product_name;

					$product_meta 		= wc_get_formatted_cart_item_data( $item );

					?>

					<div class="osc-savl-prod-cont">

						<div class="osc-savl-product" data-ckey="<?php echo $cart_key ?>">

							<?php if( $showImage ): ?>
								<div class="osc-savl-left-col">
									<?php echo $thumbnail ?>
								</div>
							<?php endif; ?>

							<div class="osc-savl-right-col">

								<?php do_action( 'osc_savl_start', $product ); ?>

								<div class="osc-savl-rc-top">

									<?php if( $showTitle ): ?>
										<span class="osc-savl-title"><?php echo $product_name; ?></span>
									<?php endif; ?>


									<?php if( $deleteType === 'icon' ): ?>
										<div class="osc-tooltip-cont osc-savl-del-cont">
											<span class="osc-savl-del <?php echo $delete_icon ?> osc-has-tooltip"></span>
											<span class="osc-tooltip"><?php echo $deleteText ?></span>
										</div>
									<?php else: ?>
										<span class="osc-savl-del osc-savl-deltxt"><?php echo $deleteText ?></span>
									<?php endif; ?>

								</div>

								<?php echo $product_meta ?>

								<div class="osc-savl-rc-bottom">

									<?php if( $showPrice ): ?>
										<span class="osc-savl-price"><?php echo $product_price; ?></span>
									<?php endif; ?>

									<?php if( $showATC ): ?>
										<div class="osc-savl-atc osc-btn"><span class="osc-icon-cart-plus"></span><?php _e( 'Add to Cart', 'open-side-cart' ); ?></div>
									<?php endif; ?>

								</div>


								<?php do_action( 'osc_savl_end', $product ); ?>

							</div>

						</div>

					</div>

				<?php endforeach; ?>

			</div>

		<?php endif; ?>

	<?php endif; ?>

</div>