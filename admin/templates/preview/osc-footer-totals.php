<?php

$totals = array();

if( $total_savings ){
	$totals['savings'] = array(
		'{{data.footer.savingLabel}}', wc_price( $total_savings ), 'less'
	);
}

$totals = array_merge( $totals, array(
	'subtotal' 		=> array(
		'{{data.footer.subtotalLabel}}', wc_price( 100 ), 'add'
	),
	'shipping' 		=> array(
		__( 'Shipping', 'open-side-cart' ), wc_price( 50 ), 'add'
	),
	'total' 		=> array(
		__( 'Total', 'open-side-cart' ), wc_price( 150 ), 'add'
	),
) );




?>

<div class="osc-ft-totals">

	<?php foreach ($totals as $key => $data ): ?>
		<# if( data.footer.totals.<?php echo $key ?> ){ #>
			<div class="osc-ft-amt osc-ft-amt-<?php echo $key ?> osc-<?php echo $data[2] ?>">
				<span class="osc-ft-amt-label"><?php echo $data[0] ?></span>
				<span class="osc-ft-amt-value"><?php echo $data[1] ?></span>
			</div>
		<# } #>
	<?php endforeach; ?>

</div>
