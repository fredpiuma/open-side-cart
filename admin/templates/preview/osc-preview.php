<?php

$products = wc_get_products( array(
	'limit' => 2,
) );


$productHTML = empty( $products ) ? 'Please create a product' :  '';

$numberOfProducts 	= count($products);
$totalQty 			= 0;
$totalSavings 		= 0;

	foreach ($products as $product) {

		$variable 		= false;
		$meta 			= '';

		if( $product->is_type('variable')  ){

			if( empty( $product->get_available_variations() ) ) continue;

			$variation 	= wc_get_product( $product->get_available_variations()[0]['variation_id'] );
			if( $variation ){
				$product 	= $variation;
				$meta 		= wc_get_formatted_variation($product);
			}
			
		}

		$qty = rand(2,10);

		$totalQty += $qty;

		$productData = array(
			'product_thumbnail' => $product->get_image(),
			'product_name' 		=> $product->get_title(),
			'product_price' 	=> wc_price( $product->get_price() ),
			'product_sale_price'=> $product->get_price_html(),
			'product_quantity' 	=> $qty,
			'product_subtotal' 	=>  wc_price( $qty * (int) $product->get_price() ),
			'product_meta' 		=> '',
			'sales_count' 		=> 500,
			'product' 			=> $product,
		);

		$regular_price 					= $product->get_regular_price();
		$sale_price 					= $product->get_price();

		$savings_data_in_amount 		= osc_cart()->get_savings_data( $sale_price, $regular_price, 'amount' );

		if( !empty( $savings_data_in_amount ) ){

			$savings_data_in_percent 		= osc_cart()->get_savings_data( $sale_price, $regular_price, 'perc' );
			$total_savings_data_in_amount 	= osc_cart()->get_savings_data( $sale_price * $qty, $regular_price * $qty, 'amount' );

			$productData['savings'] = array(
				'price_amount' 	=> $savings_data_in_amount['text'],
				'price_perc' 	=> $savings_data_in_percent['text'],
				'total_amount' 	=> $total_savings_data_in_amount['text']
			);

			$totalSavings += $total_savings_data_in_amount['value'];

		}

		$productHTML .= osc_helper()->get_template( 'osc-product-preview.php', array( 'productData' => $productData ), OSC_PATH.'/admin/templates/preview', true );

	}



$footer_template = osc_helper()->get_template( 'osc-footer-totals.php', array( 'total_savings' => $totalSavings ), OSC_PATH.'/admin/templates/preview', true );

$information_box = '<div class="osc-info-cont">{{{data.informationBox}}}</div>';

?>

<div class="osc-fw-as-preview-style"></div>
<div class="osc-fw-as-preview"></div>

<script type="text/html" id="tmpl-osc-fw-as-preview">

	<div class="osc-markup osc-align-{{data.openFrom}}">
		<div class="osc-modal">
			<div class="osc-container">

				<# if ( data.basket.show ) { #>

					<div class="osc-basket">



						<span class="osc-items-count">
							<# if ( data.basket.countType === "quantity" ) { #>
								<?php echo $totalQty; ?>
							<# }else{ #>
								<?php echo $numberOfProducts; ?>
							<# } #>
						</span>
						
						<span class="osc-bki {{{data.basket.icon}}}"></span>

					</div>

				<# } #>

				<div class="osc-header">
					<?php osc_helper()->get_template( 'osc-header-preview.php', array(), OSC_PATH.'/admin/templates/preview' ); ?>
				</div>

				<div class="osc-body">

					<# if ( data.informationBoxLocation === 'body_start' ) { #>
						<?php echo $information_box ?>
					<# } #>

					<div class="osc-products osc-pattern-<# if (data.product.layout === 'cards') { #>card<# } else { #>row<# } #>">
						<?php echo $productHTML; ?>
					</div>

					<# if ( data.totalsLocation === 'body' ) { #>
						<?php echo $footer_template; ?>
					<# } #>

					<# if ( data.informationBoxLocation === 'body_end' || data.informationBoxLocation === 'body_end_stick' ) { #>
						<?php echo $information_box ?>
					<# } #>


				</div>

				<div class="osc-footer">
					<?php osc_helper()->get_template( 'osc-footer-preview.php', array( 'footer_template' => $footer_template, 'information_box' => $information_box ), OSC_PATH.'/admin/templates/preview' ); ?>
				</div>
				
			</div>

</script>