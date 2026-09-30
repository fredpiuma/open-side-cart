<!-- Delete HTML -->
<?php ob_start(); ?>

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

<?php $deleteHTML = ob_get_clean(); ?>


<?php $priceSavingsHTML = $totalSavingsHTML = ''; ?>

<?php if( isset( $savings ) ): ?>

	<?php ob_start(); ?>

	<# if ( data.product.showPriceSavings ) { #>

		<div class="osc-psavings">
			<# if ( data.product.savingsUnit === "amount" ) { #>
				<?php echo $savings['price_amount'] ?>
			<# }else{ #>
				<?php echo $savings['price_perc'] ?>
			<# } #>
		</div>

	<# } #>

	<?php $priceSavingsHTML = ob_get_clean(); ?>


	<?php ob_start(); ?>

	<# if ( data.product.showTotalSavings ) { #>

		<div class="osc-psavings">
			<# if ( data.product.savingsUnit === "amount" ) { #>
				<?php echo $savings['total_amount'] ?>
			<# }else{ #>
				<?php echo $savings['price_perc'] ?>
			<# } #>
		</div>

	<# } #>

	<?php $totalSavingsHTML = ob_get_clean(); ?>

<?php endif; ?>



<div class="osc-product">

	<div class="osc-img-col">

		<# if ( data.product.showPImage ) { #>
			<?php echo $product_thumbnail; ?>
		<# } #>

		<# if ( "image" === data.product.deletePosition ) { #>

			<?php echo $deleteHTML; ?>

		<# } #>

	</div>



	<div class="osc-sum-col">

		<# if( data.product.showSalesCount && <?php echo $sales_count > 0  ?>){ #>
			<div class="osc-sm-sales">
				<?php echo $sales_count.'+ ' ?><?php _e('shoppers have bought this','open-side-cart'); ?>
			</div>
		<# } #>

		<div class="osc-sm-info">

			<div class="osc-sm-left">

				<# if ( data.product.showPname ) { #>
					<span class="osc-pname"><?php echo $product_name; ?></span>
				<# } #>
				
				<# if ( data.product.showPmeta ) { #>
					<?php echo $product_meta ?>
				<# } #>


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

					<?php echo $totalSavingsHTML; ?>

				<# }else{ #>

					<# if ( data.product.showPqty && !data.product.updateQty ) { #>
						<span class="osc-sml-qty"><?php _e( 'Qty:', 'open-side-cart' ) ?> <?php echo $product_quantity; ?></span>
					<# } #>


					<div class="osc-priceBox">

						<# if ( !data.product.updateQty ) { #>
							<?php echo $priceSavingsHTML; ?>
						<# } #>	

						<# if ( data.product.showPprice ) { #>
							<div class="osc-pprice">
								<?php echo __( 'Price: ', 'open-side-cart' ); ?>
									<# if( data.product.priceType === "actual" ){ #>
									<?php echo $product_price; ?>
								<# }else{ #>
									<?php echo $product_sale_price ?>
								<# } #>
							</div>
						<# } #>

						<# if ( data.product.updateQty ) { #>
							<?php echo $priceSavingsHTML; ?>
						<# } #>	

					</div>

				<# } #>



				<!-- Quantity -->
				<# if ( data.product.showPqty && data.product.updateQty ) { #>
					<div class="osc-qty-box osc-qtb-{{{data.product.qtyDesign}}}">

						<span class="osc-minus osc-chng">-</span>

						<input type="number" class="osc-qty" value="<?php echo esc_attr( $product_quantity ); ?>" />

						<span class="osc-plus osc-chng">+</span>

					</div>
				<# } #>
				

			</div>

			<!-- End Quantity -->


			<div class="osc-sm-right">

				<div class="osc-sm-right-tools">

					<# if ( data.saveForLater.enabled ) { #>

						<div class="osc-tooltip-cont">

							<span class="osc-save {{data.saveForLater.icon}} osc-has-tooltip"></span>
							
							<span class="osc-tooltip"><?php _e( 'Save for Later', 'open-side-cart' ) ?></span>

						</div>

					<# } #>

					<# if ( "default" === data.product.deletePosition ) { #>

						<?php echo $deleteHTML; ?>

					<# } #>

				</div>

				<# if ( !data.product.oneLiner ) { #>
					<?php echo $totalSavingsHTML; ?>
				<# } #>
					

				<# if ( data.product.showPtotal && !data.product.oneLiner ) { #>
					<span class="osc-smr-ptotal"><?php echo $product_subtotal ?></span>
				<# } #>


			</div>

		</div>

	</div>

</div>