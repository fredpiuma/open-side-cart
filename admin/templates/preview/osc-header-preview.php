<?php

$headingHTML = $basketHTML = $saveHTML = $closeHTML = '';

?>


<?php ob_start(); ?>

<!-- Heading Icon -->
<# if ( data.header.showBasketIcon ) { #>

<div class="osch-basket">
	<span class="osch-bki {{data.basket.icon}} osch-icon"></span>
	<span class="osch-items-count">5</span>
</div>

<# } #>

<?php $basketHTML = ob_get_clean(); ?>


<!-- Heading -->
<?php ob_start(); ?>

<# if ( data.header.heading ) { #>
	<span class="osch-text">{{data.header.heading}}</span>
<# } #>

<?php $headingHTML = ob_get_clean(); ?>



<!-- Save Later -->
<?php ob_start(); ?>

<# if ( data.saveForLater.enabled && data.header.showSaveLaterIcon ) { #>
	<div class="osc-tooltip-cont">
		<div class="osch-savelater osc-has-tooltip osc-toggle-slider" data-slider="savelater">
			<span class="osch-save-count">2</span>
			<span class="osch-save-icon {{data.saveForLater.icon}} osch-icon"></span>
		</div>
		<span class="osc-tooltip">{{data.saveForLater.heading}}</span>
	</div>
<# } #>

<?php $saveHTML = ob_get_clean(); ?>


<!-- Close Icon -->
<?php ob_start(); ?>

<# if ( data.header.showCloseIcon ) { #>
	<span class="osch-close {{data.header.closeIcon}} osch-icon"></span>
<# } #>

<?php $closeHTML = ob_get_clean(); ?>



<div class="osch-top">
	
	<# _.each( data.header.layout, function( elements, section ) { #>

		<div class="osch-section osch-sec-{{section}}">
			<# _.each( elements, function( element ) { #>

				<# if( element === "basket" ){ #>
					<?php echo $basketHTML; ?>
				<# } #>

				<# if( element === "save" ){ #>
					<?php echo $saveHTML; ?>
				<# } #>

				<# if( element === "close" ){ #>
					<?php echo $closeHTML; ?>
				<# } #>

				<# if( element === "heading" ){ #>
					<?php echo $headingHTML; ?>
				<# } #>


			<# }) #>
			
		</div>

	<# }) #>
	
	

</div>