<?php
/**
 * Suggested Products
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/footer/suggested-products.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredpiuma/open-side-cart
 * @version 4.9.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::suggested_products() );

if( $disable ) return;


$container = '<div class="osc-sp-container osc-sp-'.$style.'"><span class="osc-sp-heading">'.$heading.'</span><ul class="osc-sp-slider">%s</ul></div>';

ob_start();

while ( $products->have_posts() ) : $products->the_post();

	global $product;
	
	$product_permalink 	= $product->is_visible() ? $product->get_permalink() : '';
	$thumbnail 			= apply_filters( 'osc_suggested_product_thumbnail', $product->get_image(), $product );
	$thumbnail 			= $product_permalink && $showPLink ? sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ) : $thumbnail;
	$product_name 		= $product_permalink && $showPLink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $product->get_name() ) : $product->get_name();
	$product_price 		= $priceFormat === 'sale' ? $product->get_price_html() : wc_price( $product->get_price() );
	$product_price 		= apply_filters( 'osc_suggested_product_price', $product_price, $product );
	$is_variable 		= $product->get_type() === 'variable';
?>

<li class="osc-sp-prod-cont">

	<div class="osc-sp-product">

		<div class="osc-sp-left-col">
			<?php if( $showImage ) echo $thumbnail ?>
		</div>

		<div class="osc-sp-right-col">

			<?php do_action( 'osc_sp_start', $product ); ?>

			<div class="osc-sp-rc-top">

				<?php if( $showTitle ): ?>
					<span class="osc-sp-title"><?php echo $product_name; ?></span>
				<?php endif; ?>

			</div>

			<div class="osc-sp-rc-bottom">

				

				<?php if( $showPrice ): ?>
					<span class="osc-sp-price"><?php echo $product_price; ?></span>
				<?php endif; ?>

				<?php if( $showATC ): ?>

					<?php if( $is_variable && $quickView ): ?>
						<span class="osc-sp-atc osc-toggle-slider" data-slider="quickview" data-product_id="<?php echo $product->get_id(); ?>"><?php woocommerce_template_loop_add_to_cart( array( 'is_osc_sp' => 'yes' ) ) ?></span>
					<?php else: ?>
						<span class="osc-sp-atc"><?php woocommerce_template_loop_add_to_cart( array( 'is_osc_sp' => 'yes' ) ) ?></span>
					<?php endif; ?>
					
				<?php endif; ?>

			</div>


			<?php do_action( 'osc_sp_end', $product ); ?>

		</div>

	</div>

	
</li>

<?php endwhile; ?>

<?php wp_reset_postdata(); ?>

<?php printf( $container, ob_get_clean() ); ?>