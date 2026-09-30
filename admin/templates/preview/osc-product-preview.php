<# if ( "cards" === data.product.layout ) { #>
	<?php osc_helper()->get_template( 'osc-product-card-preview.php', $productData, OSC_PATH.'/admin/templates/preview' ); ?>
<# }else{ #>
	<?php osc_helper()->get_template( 'osc-product-row-preview.php', $productData, OSC_PATH.'/admin/templates/preview' ); ?>
<# } #>