<?php

$subtotal = wc_price(100);

?>

<# if ( data.informationBoxLocation === 'footer_start' || data.informationBoxLocation === 'mobile_body' ) { #>
	<?php echo $information_box ?>
<# } #>



<# if ( data.totalsLocation !== 'body' ) { #>
	<?php echo $footer_template ?>
<# } #>



<# if ( data.footer.footerTxt ) { #>
<span class="osc-footer-txt">{{{data.footer.footerTxt}}}</span>
<# } #>


<div class="osc-ft-buttons-cont">

	<# _.each( data.footer.buttonsPosition, function( key ) { #>
		<# if( data.footer.buttonsText[key] ){ #>
			<a href="#" class="osc-ft-btn osc-ft-btn-{{key}}">{{{data.footer.buttonsText[key]}}} <# if( key === 'checkout' && data.footer.checkoutTotal === 'yes' ){ #>  -  <?php echo $subtotal; ?> <# } #></a>
		<# } #>
	<# }) #>

</div>

<# if ( data.informationBoxLocation === 'footer_end' ) { #>
	<?php echo $information_box ?>
<# } #>

