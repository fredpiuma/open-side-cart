<script type="text/html" id="tmpl-osc-fw-as-chkpoint">

	<?php $id = $base_id.'[checkpoints][%#]' ?>

	<div class="osc-bar-chkpoint osc-accordion" data-type="{{data.type}}">

		<div class="osc-acc-head"><span class="dashicons dashicons-plus-alt2"></span><span class="dashicons dashicons-minus"></span> <div class="osc-chkpoint-title">{{data.title}}</div> <span class="dashicons dashicons-trash osc-checkpoint-delete"></span></div>

		<div class="osc-acc-cont osc-chkpoint-settings">

			<input type="hidden" name="<?php echo $id ?>[type]" value="{{data.type}}">


			<# if ( data.type === "freeshipping" ) { #>
			<div class="osc-fw-scbhk-ship-title">
				<i>The checkpoint amount is fetched from Free shipping method ( woocommerce shipping settings ).<br> Please make sure you have a free shipping method available for customers' location.<br><a href="https://github.com/fredericomdecastro/open-side-cart" target="__blank">Read more</a></i><br>
			</div>
			<# } #>

			<div class="osc-chkpoint-setting">
				<input type="hidden" name="<?php echo $id ?>[enable]" value="no">
				<label><input type="checkbox" value="yes" name="<?php echo $id ?>[enable]" {{ data.enable == 'yes' ? 'checked' : '' }}> Enable</label>
			</div>

			<# if ( data.type === "discount" ) { #>

				<div class="osc-chkpoint-setting">
					<label>Type</label>
					<select name="<?php echo $id ?>[discount_type]">
						<option value="percentage" {{ data.discount_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
						<option value="amount" {{ data.discount_type == 'amount' ? 'selected' : '' }}>Fixed Amount</option>
					</select>
				</div>

			<# } #>

			<div class="osc-chkpoint-setting">
				<label>Title</label>
				<input type="text" value="{{data.title}}" name="<?php echo $id ?>[title]" class="osc-chkpoint-title-input">
			</div>

			<div class="osc-chkpoint-setting">
				<label>Remaining Text</label>
				<input type="text" value="{{data.remaining}}" name="<?php echo $id ?>[remaining]">
				<span class="osc-fw-scbhk-desc">[value] is the remaining value to unlock this checkpoint</span>
			</div>

			

			<# if ( data.type !== "freeshipping" ) { #>
			<div class="osc-chkpoint-setting">
				<label>Checkpoint Value</label>
				<input type="number" value="{{data.amount}}" step="any" name="<?php echo $id ?>[amount]">
				<span class="osc-fw-scbhk-desc">Value required to achieve this reward</span>
			</div>
			<# } #>

			

			<# if ( data.type === "gift" ) { #>

				<div class="osc-chkpoint-setting osc-bar-prodsearch">

					<label>Free Gift Products</label>

					<select class="wc-product-search" multiple="multiple" name="<?php echo $id ?>[gift_ids][]" data-placeholder="<?php esc_attr_e( 'Search for a product&hellip;', 'woocommerce' ); ?>" data-action="woocommerce_json_search_products_and_variations">
					</select>

					<div class="osc-barpsearch-defaults">
						<# _.each( data.gift_ids , function(option_value, index) { #>
							<input type="hidden" name="<?php echo $id ?>[gift_ids][]" value="{{option_value}}">
						<# }) #>
					</div>

					<span class="osc-fw-scbhk-desc">Add gift products</span>

				</div>


				<div class="osc-chkpoint-setting">
					<label>Gift Quantity</label>
					<input type="number" value="{{data.gift_qty}}" step="any" name="<?php echo $id ?>[gift_qty]">
				</div>

				<div class="osc-chkpoint-setting">
					<input type="hidden" name="<?php echo $id ?>[showcase]" value="no">
					<label><input type="checkbox" value="yes" name="<?php echo $id ?>[showcase]" {{ data.showcase == 'yes' ? 'checked' : '' }}> Showcase Gifts<span class="osc-fw-scbhk-desc"> (If disabled, products will be kept as a suprise)</span></label>

				</div>


			<# } #>


			<# if ( data.type === "discount" ) { #>
				<div class="osc-chkpoint-setting">
					<label>Discount</label>
					<input type="number" value="{{data.discount}}" step="any" name="<?php echo $id ?>[discount]">
				</div>
			<# } #>
				

			<div class="osc-bar-setgroup osc-barset-full">

				<div class="osc-chkpoint-setting">
					<label>Icon</label>
					<div>
						<input type="text" value="{{data.icon}}" name="<?php echo $id ?>[icon]" class="osc-bar-icon">
						<i></i>
					</div>
				</div>

				<div class="osc-chkpoint-setting">
					<label>Checkpoint Achieved Icon</label>
					<div>
						<input type="text" value="{{data.iconFilled}}" name="<?php echo $id ?>[iconFilled]" class="osc-bar-icon">
						<i></i>
					</div>
				</div>

			</div>

			
		</div>

	</div>

</script>