<!-- View Link HTML  -->
<?php ob_start(); ?>
<?php printf( '<a class="osc-smr-link" href="#">%1$s</a>', '<i class="osc-icon-external-link"></i>'. __( 'View', 'open-side-cart' ) ); ?>
<?php $viewLinkHTML = ob_get_clean(); ?>

<!-- Price HTML  -->
<?php ob_start(); ?>
<# if ( data.product.showPprice && !data.product.oneLiner) { #>
	<?php printf( '<span class="osc-card-price">%1$s</span>', __( 'Price: ', 'open-side-cart' ) . $product_price ); ?>
<# } #>
<?php $priceHTML = ob_get_clean(); ?>

<!-- Total HTML -->
<?php ob_start(); ?>
<# if ( data.product.showPtotal && !data.product.oneLiner ) { #>
	<span class="osc-card-ptotal"><?php echo $product_subtotal ?></span>
<# } #>
<?php $totalHTML = ob_get_clean(); ?>


<!-- Name HTML -->
<?php ob_start(); ?>
<# if ( data.product.showPname ) { #>
	<span class="osc-pname"><?php echo $product_name; ?></span>
<# } #>
<?php $nameHTML = ob_get_clean(); ?>


<!-- Meta HTML -->
<?php ob_start(); ?>
<# if ( data.product.showPmeta ) { #>
	<?php echo $product_meta ?>
<# } #>
<?php $metaHTML = ob_get_clean(); ?>


<!-- Quantity HTML -->
<?php ob_start(); ?>

<div class="osc-qty-box-cont">

	<# if ( data.product.showPqty && data.product.updateQty ) { #>
		<div class="osc-qty-box osc-qtb-{{{data.product.qtyDesign}}}">

			<span class="osc-minus osc-chng">-</span>

			<input type="number" class="osc-qty" value="<?php echo esc_attr( $product_quantity ); ?>" />

			<span class="osc-plus osc-chng">+</span>

		</div>

	<# }else{ #>


		<# if ( data.product.oneLiner ) { #>
			<div class="osc-qty-price">
				<span><?php echo $product_quantity; ?></span>
				<span>X</span>
				<span>
					<# if( data.product.priceType === "actual" ){ #>
						<?php echo $product_price; ?>
					<# }else{ #>
						<?php echo $product_sale_price ?>
					<# } #>
				</span>
				<span>=</span>
				<span><?php echo $product_subtotal ?></span>
			</div>

		<# }else{ #>

			<# if ( data.product.showPqty && !data.product.updateQty ) { #>
				<span class="osc-sml-qty"><?php _e( 'Qty:', 'open-side-cart' ) ?> <?php echo $product_quantity; ?></span>
			<# } #>

		<# } #>

	<# } #>

	<?php echo $totalHTML ?>

</div>

<?php $qtyHTML = ob_get_clean(); ?>


<div class="osc-product-cont <# if (data.card.hasBack) { #>osc-has-back<# } #>">

	<div class="osc-product">

		<div class="osc-card-cont">

			<div class="osc-card-actionbar">

				<# if ( data.saveForLater.enabled ) { #>

					<div class="osc-tooltip-cont">

						<span class="osc-save {{data.saveForLater.icon}} osc-has-tooltip"></span>
						
						<span class="osc-tooltip"><?php _e( 'Save for Later', 'open-side-cart' ) ?></span>

					</div>

				<# } #>

				<# if ( data.product.showPdel ) { #>
					<# if ( "icon" === data.product.deleteType ) { #>
						<div class="osc-tooltip-cont osc-del-cont">
							<span class="osc-smr-del {{data.product.deleteIcon}} osc-has-tooltip"></span>
							<span class="osc-tooltip">{{data.product.deleteText}}</span>
						</div>
					<# }else{ #>
						<span class="osc-smr-del osc-del-txt">{{data.product.deleteText}}</span>
					<# } #>

				<# } #>

				</div>


			<div class="osc-img-col magictime">

				<# if ( data.product.showPImage ) { #>
					<?php echo $product_thumbnail; ?>
				<# } #>

			</div>


			<# if ( data.card.hasBack ) { #>

			<div class="osc-sm-back-cont">

				<div class="osc-sm-back">

					<# if ( data.card.backShow.name ) { #>
						<?php echo $nameHTML; ?>
					<# } #>

					<# if ( data.card.backShow.meta ) { #>
						<?php echo $metaHTML; ?>
					<# } #>

					<# if ( data.card.backShow.link ) { #>
						<?php echo $viewLinkHTML; ?>
					<# } #>


					<# if ( data.card.backShow.price ) { #>
						<?php echo $priceHTML; ?>
					<# } #>

					<# if ( data.card.backShow.qty ) { #>
						<?php echo $qtyHTML; ?>
					<# } #>

				</div>

			</div>

			<# } #>
			
		</div>


		<div class="osc-sm-front">

			<span class="osc-sm-emp"></span>

			<# if ( !data.card.backShow.name || data.card.visibility === 'all_on_front' ) { #>
				<?php echo $nameHTML; ?>
			<# } #>

			<# if ( !data.card.backShow.price || data.card.visibility === 'all_on_front' ) { #>
				<?php echo $priceHTML; ?>
			<# } #>

			<# if ( !data.card.backShow.meta || data.card.visibility === 'all_on_front' ) { #>
				<?php echo $metaHTML; ?>
			<# } #>

			<# if ( !data.card.backShow.qty || data.card.visibility === 'all_on_front' ) { #>
				<?php echo $qtyHTML; ?>
			<# } #>


		</div>

	</div>

</div>