<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class OSC_Cart{

	protected static $_instance = null;

	public $notices 						= array();
	public $coupons 						= array();
	public $addedToCart 					= false;
	public $bundleItems 					= array();
	public $suggestedProducts 				= false;
	public $handlingGift 					= false;
	public $ajaxAddTocartFail 				= false;
	public $barsWithActiveDiscountPoints 	= array();
	public $totalRewardsDiscount  			= array();
	public $allowedGiftIDs  				= array();
	public $barValues  						= array();
	public $glSettings;
	public $barCheckPoints;
	public $bars;
	public $noticeHTML;


	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	
	public function __construct(){
		$this->glSettings = osc_helper()->get_general_option();
		$this->hooks();
	}


	public function hooks(){

		add_action( 'wc_ajax_osc_update_item_quantity', array( $this, 'update_item_quantity' ) );

		add_action( 'wc_ajax_osc_undo_item', array( $this, 'undo_item' ) );

		add_action( 'wc_ajax_osc_refresh_fragments', array( $this, 'get_refreshed_fragments' ) );

		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'set_ajax_fragments' ) );
		
		add_filter( 'woocommerce_update_order_review_fragments', array( $this, 'set_ajax_fragments' ) );

		add_action( 'wc_ajax_osc_calculate_shipping', array( $this, 'calculate_shipping' ) );

		add_action( 'wc_ajax_osc_update_shipping_method', array( $this, 'update_shipping_method' ) );

		add_action( 'wc_ajax_osc_apply_coupon', array( $this, 'apply_coupon' ) );

		add_action( 'wc_ajax_osc_remove_coupon', array( $this, 'remove_coupon' ) );

		add_action( 'wc_ajax_osc_add_to_cart', array( $this, 'add_to_cart' ), 99 );

		add_action( 'wc_ajax_osc_empty_cart', array( $this, 'empty_cart' ) );

		add_action( 'woocommerce_add_to_cart', array( $this, 'added_to_cart' ), 10, 6 );

		add_filter( 'pre_option_woocommerce_cart_redirect_after_add', array( $this, 'prevent_cart_redirect' ), 20 );

		add_action( 'woocommerce_check_cart_items', array( $this, 'handle_free_gifts' ) );

		add_action( 'woocommerce_check_cart_items', array( $this, 'remove_not_allowed_gift_ids' ) );

		add_action( 'woocommerce_cart_item_removed', array( $this, 'track_gift_product_removal' ), 10, 2 );

		add_filter( 'woocommerce_get_cart_contents', array( $this, 'adjust_gift_location' ) );

		add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'check_failed_add_to_cart' ), PHP_INT_MAX );

		add_action( 'woocommerce_cart_calculate_fees', array( $this, 'checkpoint_apply_cart_total_discount' ) );


		if( isset( $this->glSettings['sl-enable'] ) && $this->glSettings['sl-enable'] === "yes" ){

			add_action( 'wc_ajax_osc_save_for_later', array( $this, 'save_for_later' ) );

			add_action( 'wc_ajax_osc_delete_save_for_later_item', array( $this, 'delete_save_for_later_item' ) );

			add_action( 'wc_ajax_osc_move_save_for_later_item', array( $this, 'move_save_for_later_item_to_cart' ) );

			add_action( 'osc_saved_for_later_item_deleted', array( $this, 'persistent_save_for_later_update' ) );
			add_action( 'osc_saved_for_later_item_added', array( $this, 'persistent_save_for_later_update' ) );
			add_action( 'osc_saved_for_later_item_moved', array( $this, 'persistent_save_for_later_update' ) );

			add_action( 'wp_login', array( $this, 'user_logged_in' ), 10, 2 );

			add_action( 'template_redirect', array( $this, 'sync_saved_for_later_list_for_logged_in_user' ) );

		}


		add_action( 'wc_ajax_osc_load_quickview', array( $this, 'load_quickview' ) );

		//Use these filters only when quick view add to cart form.
		if ( isset( $_REQUEST['wc-ajax'] ) && 'osc_load_quickview' === $_REQUEST['wc-ajax'] ) {

			//If updating current variable options
			if( isset( $_POST['cart_key'] ) && !empty( $_POST['cart_key'] ) ){
				add_filter( 'woocommerce_dropdown_variation_attribute_options_args', array( $this, 'quickview_set_variation' ), 999 );
				add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'quickview_set_atc_text' ), 999 );
				add_filter( 'woocommerce_quantity_input_args', array( $this, 'quickview_set_quantity' ), 99999 );
				add_action( 'woocommerce_before_variations_form', array( $this, 'quickview_variation_form_add' ) );
			}

		}


		if( isset( $_POST['osc_qv_atc'] ) && isset( $_POST['osc_qv_cart_key'] ) ){
			add_action( 'wc_ajax_osc_add_to_cart', array( $this, 'quickview_on_add_to_cart' ) );
			add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'quickview_remove_previous_cart_item' ), 9999 );
		}
		
		

	}


	public function quickview_remove_previous_cart_item( $validation_passed ){

		//Since we are updating cart item, we need to remove the previously added item and we only do it if the current add to cart request validation is passed
		if( $validation_passed ){
			WC()->cart->remove_cart_item( sanitize_text_field( wp_unslash( $_POST['osc_qv_cart_key'] ) ) );
		}
		return $validation_passed;

	}

	public function quickview_on_add_to_cart(){

		if( !$this->addedToCart ){ // if quickview added to cart failed, restore previously removed cart item
			WC()->cart->restore_cart_item( sanitize_text_field( wp_unslash( $_POST['osc_qv_cart_key'] ) ) );
		}
		else{ // success
			unset( $this->notices['addtocart'] );
			$this->set_notice( __( 'Item updated', 'open-side-cart' ), 'success', 'quickview_update' );
		}
	}

	public function quickview_variation_form_add(){
		?>
		<input type="hidden" name="osc_qv_atc" value="1">
		<input type="hidden" name="osc_qv_cart_key" value="<?php echo sanitize_text_field( wp_unslash( $_POST['cart_key'] ) ) ?>">
		<?php
	}

	public function quickview_set_quantity( $args ){
	
		$cart_item = WC()->cart->get_cart_item( sanitize_text_field( wp_unslash( $_POST['cart_key'] ) ) );

		if ( $cart_item ) {
			$args['input_value'] = $cart_item['quantity'];
		}

		return $args;

	}



	public function quickview_set_atc_text(){
		return 'Update';
	}


	public function quickview_set_variation( $args ) {

		if ( empty( $_POST['cart_key'] ) ) {
			return $args;
		}


		$cart_item = WC()->cart->get_cart_item( sanitize_text_field( wp_unslash( $_POST['cart_key'] ) ) );

		if ( !$cart_item || empty( $cart_item['variation_id'] ) ) {
			return $args;
		}


		$variation = $cart_item['data'];

		if ( !$variation ) {
			return $args;
		}

		$variation_attributes = $variation->get_attributes();

		/*
		 * Try to determine the attribute slug used by the dropdown.
		 *
		 * Examples:
		 * Color      => color
		 * pa_color   => pa_color
		 * attribute_pa_color => pa_color
		 */
		$attribute_slug = '';

		if ( ! empty( $args['name'] ) ) {

			$attribute_slug = str_replace(
				'attribute_',
				'',
				$args['name']
			);

		} elseif ( ! empty( $args['attribute'] ) ) {

			$attribute_slug = sanitize_title( $args['attribute'] );

		}


		if ( $attribute_slug && isset( $variation_attributes[ $attribute_slug ] ) ) {
			$args['selected'] = $variation_attributes[ $attribute_slug ];
		}

		return $args;
	}

	public function load_quickview(){

		global $product;

		$product_id = null;

		if( isset( $_POST['cart_key'] ) ){

			$cart_item_key = sanitize_text_field( $_POST['cart_key'] );

			// Get cart item
			$cart_item = WC()->cart->get_cart_item( $cart_item_key );

			if ( !$cart_item ) return;

			$product = $cart_item['data'];

			// Product ID
			$product_id = $cart_item['product_id'];
		}
		else if( isset( $_POST['product_id'] ) ){
			$product_id = (int) $_POST['product_id'];
		}

		$product = wc_get_product( $product_id );

		if( !$product ) return;

		osc_helper()->get_template('/global/quickview-product.php', array( 'product' => $product ));
		
	}

	
	public function remove_not_allowed_gift_ids(){

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if( !isset( $cart_item['osc_gift'] ) ) continue;
			if( !$cart_item['osc_gift'] || !in_array( $cart_item['osc_gift'] , $this->allowedGiftIDs ) ){
				WC()->cart->remove_cart_item( $cart_item_key );
			}
		}

	}


	public function track_gift_product_removal( $cart_item_key, $cart ){

		if( osc_helper()->get_rewards_option('scbar-fg-en-delete') !== "yes" ) return;

		 $item = $cart->removed_cart_contents[ $cart_item_key ] ?? null;

	    if ( ! $item || empty( $item['osc_gift'] ) ) {
	        return;
	    }

	    $gift_id = $item['osc_gift'];

	    $opt_out = WC()->session->get( 'osc_free_gift_opted_out', [] );
	    $opt_out[ $gift_id ] = true;

	    WC()->session->set( 'osc_free_gift_opted_out', $opt_out );

	}


	public function check_failed_add_to_cart( $success ){
		$this->ajaxAddTocartFail = wp_doing_ajax() && !$success;
		return $success;
	}


	public function checkpoint_apply_cart_total_discount() {

		$cart_total = WC()->cart->get_cart_contents_total();
		$cart_count = WC()->cart->get_cart_contents_count();

		if( osc_helper()->get_rewards_option('scbar-en') !== 'yes' || $cart_total <= 0 || $cart_total <= 0 ) return;

		$bars = osc_bars()->get_bars();

		$siteMaxDiscount = 0;

		$activeDiscountCheckPoints = array();

		foreach ( $bars as $index => $bar ) {

			if( !isset( $bar['checkpoints'] ) ) continue;
		
			$points 				= $bar['checkpoints'];
			$overrideDiscounts 		= $bar['settings']['overrideDiscount'] === "yes";
			$limitToHighestReward 	= $bar['settings']['highestReward'] === "yes"; 

			$cartValue 		= $bar['settings']['barValue'] === 'total' ? $this->bar_cart_value( $bar, 'subtotal_tax' ) :  $this->bar_cart_value( $bar ); // since cart total is not available yet, we will switch to subtotal_tax
			$barMaxDiscount = 0;

			$barAchievedCheckpoint = osc_bars()->get_bar_last_achieved_checkpoint( $bar['id'] );

			//Get the maximum discount of this bar.
			foreach ( $points as $point_index => $point ) {

				if( $point['type'] !== 'discount' ) continue;

				$pointAmount 	= $point['amount'];
				$discountVal 	= $point['discount'];

				// If setting is only highest checkpoint is rewarded. 
				if( $limitToHighestReward && $barAchievedCheckpoint && $pointAmount < $barAchievedCheckpoint['amount'] ) continue;

				$discount_amount 					= $point['discount_type'] === 'percentage' ? ( $cart_total * ($discountVal/100) ) : $discountVal;
				$point['discount_amount'] 			= $discount_amount;

				//If cart value crosses the checkpoint required amount
				// Apply only highest discount
				if( $cartValue >= $pointAmount && $discount_amount && $discount_amount > $barMaxDiscount ){
					$barMaxDiscount 			= $discount_amount;
					$maxDiscountCheckpoint  	= $point;
				}

			}

			if( isset( $maxDiscountCheckpoint ) ){
				$activeDiscountCheckPoints[ 'bar_'.$index ] = $maxDiscountCheckpoint;
			}

			if( $barMaxDiscount > $siteMaxDiscount ){
				$siteMaxDiscount = $barMaxDiscount;
			}

		}



		//Apply bars discount to cart
		foreach ( $activeDiscountCheckPoints as $bar_id => $discountCheckpoint ) {

			$bar 				= osc_bars()->get_bar_from_id( $bar_id );
			$overrideDiscounts 	= $bar['settings']['overrideDiscount'] === "yes";
			$barMaxDiscount 	= $discountCheckpoint['discount_amount'];

			

			if( $discountCheckpoint && ( !$overrideDiscounts || ( $barMaxDiscount >= $siteMaxDiscount ) ) ){

				$this->barsWithActiveDiscountPoints[ $bar_id ] = $discountCheckpoint;

			    WC()->cart->add_fee( $discountCheckpoint['title'], -$barMaxDiscount );

			    $this->totalRewardsDiscount[ $bar_id ] = $barMaxDiscount;

			}
		}


		
	}




	public function bar_cart_value( $bar, $type = '' ){

		if( !$type ){
			$type = $bar['settings']['barValue'];
		}

		$bar_id = $bar['id'];

		if( isset( $this->barValues[ $bar_id.'_'.$type ] ) ){
			return $this->barValues[ $bar_id.'_'.$type ];
		}

		$value = '';

		switch ( $type ) {
			case 'total':
				$value = WC()->cart->get_total('float');
				break;
			
			case 'subtotal':
				$value = WC()->cart->get_subtotal('float');
				break;

			case 'subtotal_tax':
				$value = wc_format_decimal( WC()->cart->get_subtotal('float') + WC()->cart->get_subtotal_tax('float') );
				break;

			case 'quantity':
				$value = WC()->cart->get_cart_contents_count() - $this->get_gifts_count_in_cart();
				break;
		}

		$value = apply_filters( 'osc_checkpoints_before_total_value_adjustment', $value, $type, $bar );

		$rewardsNotEligibleBarIDs = array();

		if( $bar['settings']['filter_byproducts'] !== "no" ){

			$filter 		= $bar['settings']['filter_byproducts'];

			$product_ids 	= isset( $bar['settings']['filter_product_ids'] ) ? $bar['settings']['filter_product_ids'] : array() ;
			$product_ids 	= empty( $product_ids ) || !is_array( $product_ids ) ? array() : $product_ids;

			$new_value 		= $filter === 'allowed_products' ? 0 : $value;

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

				if( isset( $cart_item['osc_gift'] ) ) continue;

				$cart_item_product_id 	= isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] ? $cart_item['variation_id'] : $cart_item['product_id'];
				$isCartItemInFilter 	= in_array( $cart_item_product_id , $product_ids );

				if( ( $filter === 'allowed_products' && !$isCartItemInFilter ) || ( $filter === 'except_products' && $isCartItemInFilter ) ){

					switch ( $type ) {
						case 'total':
							$value -= wc_format_decimal( $cart_item['line_total'] );
							break;
						
						case 'subtotal':
							$value -= wc_format_decimal( $cart_item['line_subtotal'] );
							break;

						case 'subtotal_tax':
							$value -= wc_format_decimal( ( $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'] ) );
							break;

						case 'quantity':
							$value -= $cart_item['quantity'];
							break;
					}

					WC()->cart->cart_contents[ $cart_item_key ]['osc_rewards_not_eligible'][$bar_id] = $bar['settings']['productNotEligibleTxt'];

					$rewardsNotEligibleBarIDs[] = $bar_id;

    				$saveCart = true;

				}
				else if( isset( $cart_item['osc_rewards_not_eligible'] ) &&  isset( $cart_item['osc_rewards_not_eligible'][$bar_id] ) ){
					unset( WC()->cart->cart_contents[ $cart_item_key ]['osc_rewards_not_eligible'][ $bar_id ] ); 
					$saveCart = true;
				}
				
			}

		}

		//Removing not eligible bar ( previously added )
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if( !isset( $cart_item['osc_rewards_not_eligible'] ) ) continue;
			WC()->cart->cart_contents[ $cart_item_key ]['osc_rewards_not_eligible'] = array_intersect_key( $cart_item['osc_rewards_not_eligible'], array_flip( $rewardsNotEligibleBarIDs ) );
			$saveCart = true;
		}
		

		if( isset( $saveCart ) ){
			WC()->cart->set_session(); // important: save back to session
		}



		return apply_filters( 'osc_checkpoints_total_value', $value, $type, $bar );
		
	}

	public function adjust_gift_location( $cart ){

		$newCart = array();

		foreach ( $cart as $item_key => $item ) {
			if( isset( $item['osc_gift'] ) ){
				$newCart[ $item_key ] = $item;
				unset( $cart[ $item_key ] );
			}
		}

		return array_merge( $cart, $newCart );
	}


	public function get_gifts_count_in_cart( $type = 'quantity' ){

		$quantity = $product = 0;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if( isset( $cart_item['osc_gift'] ) ){
				$quantity += $cart_item['quantity'];
				$product++;
			}
		}

		return  $type === 'quantity' ?  $quantity : $product;

	}


	public function is_gift_in_cart( $id ){

		$return_item_key = false;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			if( isset( $cart_item['osc_gift'] ) && ( ( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] == $id ) || $cart_item['product_id'] == $id ) ){
				$return_item_key = $cart_item_key;
				break;
			}
		}

		return $return_item_key;

	}


	public function handle_free_gifts(){

		$rewardsSettings = osc_helper()->get_rewards_option();

		if( $this->handlingGift || $rewardsSettings['scbar-en'] !== 'yes' ) return;

		$this->handlingGift = true;

		$bars = osc_bars()->get_bars();

		$allow_gifts_delete = osc_helper()->get_rewards_option('scbar-fg-en-delete') === "yes";

		foreach ( $bars as $index => $bar) {
		
			if( !isset( $bar['checkpoints'] ) ) continue;

			$points = $bar['checkpoints'];


			$limitToHighestReward 	= $bar['settings']['highestReward'] === "yes";

			if( $limitToHighestReward ){
				$barAchievedCheckpoint = osc_bars()->get_bar_last_achieved_checkpoint( $bar['id'] );
			}
;

			$restrictToHighestGift 	= isset( $bar['settings']['highestGift'] ) &&  $bar['settings']['highestGift'] === "yes";
			$highestGiftAdded 		= false;


			if( $restrictToHighestGift ){
				usort( $points , function( $a, $b ){
					if( $a['amount'] === $b['amount'] ) return 0;
					return $a['amount'] > $b['amount'] ? -1 : 1;
				} );
			}
			
			foreach ( $points as $point_index => $point ) {

				if( $point['type'] !== 'gift' || !isset( $point['gift_ids'] ) ) continue;

				$giftProductIds = $point['gift_ids'];
				
				if( empty( $giftProductIds ) ) continue;

				$disallowReward 	= false;
				$pointAmount 		= $point['amount'];
				$cartValue 			= $this->bar_cart_value( $bar );



				// If setting is only highest checkpoint is rewarded. 
				if( $limitToHighestReward && $barAchievedCheckpoint && ( $barAchievedCheckpoint['amount'] > $pointAmount ) ){
					$disallowReward = true;
				}

				foreach ( $giftProductIds as $id ) {

					$cartItemKey = $this->is_gift_in_cart( $id );

					if( !$disallowReward && $cartValue >= $pointAmount && ( !$restrictToHighestGift || !$highestGiftAdded  ) ){ //add gift to cart

						$giftQty = $point['gift_qty'] && $point['gift_qty'] > 0 ? $point['gift_qty'] : 1;
						
						if( !$cartItemKey ){

							$opted_out = $allow_gifts_delete && !empty( WC()->session->get( 'osc_free_gift_opted_out', [] )[ $id ] );

							if( !$opted_out ){

								$addedToCart 	= WC()->cart->add_to_cart( $id, $giftQty, 0, array(), array( 'osc_gift' => $id ) );
								unset( $this->notices['addtocart'] );
								$this->set_notice( __( 'Congratulations! Free gift has been added to your cart.', 'open-side-cart' ), 'success' );

							}
						}
						else{

							$cartItem = WC()->cart->get_cart_item($cartItemKey);

							if( $cartItem['quantity'] !== $giftQty ){
								WC()->cart->set_quantity( $cartItemKey, $giftQty );
							}

						}

						$this->allowedGiftIDs[] = $id;

						$highestGiftAdded = $restrictToHighestGift ? true : false;

					}
					else{

						if( $cartItemKey ){

							WC()->cart->remove_cart_item( $cartItemKey );

							if( !$disallowReward && !( $restrictToHighestGift && $highestGiftAdded ) ){ // do not show message when replaced with highest gift.
								$this->set_notice( __( 'Free gift has been removed from your cart.', 'open-side-cart' ), 'error' );
							}

						}
						
						if( $allow_gifts_delete ){
							$opt_out = WC()->session->get( 'osc_free_gift_opted_out', [] );
						    unset( $opt_out[ $id ] );
						    WC()->session->set( 'osc_free_gift_opted_out', $opt_out );
						}
						
					}
				}
				
			}

		}

		$this->handlingGift = false;
	}


	public function empty_cart(){
		WC()->cart->empty_cart();
		$this->set_notice( __( 'Cart Emptied', 'open-side-cart' ), 'sucess' );
		$this->get_refreshed_fragments();
	}

	public function prevent_cart_redirect( $value ){

		$ajaxAtc = $this->glSettings['m-ajax-atc'];

		if( $ajaxAtc !== 'no' ){
			$value = 'no';
		}

		return $value;		
	}

	/* Add to cart is performed by woocommerce as 'add-to-cart' is passed */
	public function add_to_cart(){

		if( !isset( $_POST['add-to-cart'] ) ) return;

		if( $this->addedToCart ){
			// trigger action for added to cart in ajax
			do_action( 'woocommerce_ajax_added_to_cart', intval( $_POST['add-to-cart'] ) );
			$this->get_refreshed_fragments();
		}
		else{
			$this->return_notices();
		}

	}


	public function return_notices(){


		ob_start();
		$this->print_notices_html();
		$notices = ob_get_clean();

		wp_send_json(array(
			'notice' 			=> $notices,
		));

		exit;
	}


	public function added_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ){

		$this->set_notice( __( 'Item added to cart', 'open-side-cart' ), 'success', 'addtocart' );

		$this->addedToCart = 'yes';

	}


	public function set_notice( $notice, $type = 'success', $code = '' ){
		$this->notices[ $code ] = osc_notice_html( $notice, $type );
	}



	public function update_shipping_method(){

		$chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );
		$posted_shipping_methods = isset( $_POST['shipping_method'] ) ? wc_clean( wp_unslash( $_POST['shipping_method'] ) ) : array();

		if ( is_array( $posted_shipping_methods ) ) {
			foreach ( $posted_shipping_methods as $i => $value ) {
				$chosen_shipping_methods[ $i ] = $value;
			}
		}

		WC()->session->set( 'chosen_shipping_methods', $chosen_shipping_methods );

		$this->set_notice( __( 'Shipping updated', 'open-side-cart' ), 'success' );

		$this->get_refreshed_fragments();
	}


	public function print_notices_html( $wc_cart_notices = true, $clean = true ){

		if( isset( $this->noticeHTML ) ){
			echo $this->noticeHTML;
		}

		if( !in_array( 'notifications', $this->glSettings['sch-show'] ) || !( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) return;

		if( $wc_cart_notices ){

			//Add WC notices
			$wc_notices = wc_get_notices( 'error' );

			foreach ( $wc_notices as $wc_notice ) {
				$this->set_notice( $wc_notice['notice'], 'error' );
			}

			wc_clear_notices();

		}

		$notices = apply_filters( 'osc_notices_before_print', $this->notices );

		$this->noticeHTML = sprintf( '<div class="osc-notice-container"><ul class="osc-notices">%1$s</ul></div>', implode( '' , $notices )  );

		echo apply_filters( 'osc_print_notices_html', $this->noticeHTML, $notices );
		
		if( $clean ){
			$this->notices = array();
		}

	}




	public function update_item_quantity(){


		$cart_key 	= sanitize_text_field( $_POST['cart_key'] );
		$new_qty 	= (float) $_POST['qty'];

		if( !is_numeric( $new_qty ) || $new_qty < 0 || !$cart_key ){
			$this->set_notice( __( 'Something went wrong', 'open-side-cart' ) );
		}
		
		$validated = apply_filters( 'osc_update_quantity', true, $cart_key, $new_qty );

		if( $validated && !empty( WC()->cart->get_cart_item( $cart_key ) ) ){

			$updated = $new_qty == 0 ? WC()->cart->remove_cart_item( $cart_key ) : WC()->cart->set_quantity( $cart_key, $new_qty );

			if( $updated ){

				if( $new_qty == 0 ){

					$notice = __( 'Item removed', 'open-side-cart' );

					$notice .= '<span class="osc-undo-item" data-key="'.$cart_key.'">'.__('Undo?','open-side-cart').'</span>';  

				}
				else{
					$notice = __( 'Item updated', 'open-side-cart' );
				}

				$this->set_notice( $notice, 'success' );
				
			}
		}


		$this->get_refreshed_fragments();

		die();
	}


	public function undo_item(){

		$cart_key 	= sanitize_text_field( $_POST['cart_key'] );

		if( !$cart_key ) return;

		$validated = apply_filters( 'osc_undo_item', true, $cart_key );

		if( $validated ){

			$updated = WC()->cart->restore_cart_item( $cart_key );

			if( $updated ){
				$this->set_notice( __( 'Item added back', 'open-side-cart' ), 'success' );
			}
		}

		$this->get_refreshed_fragments();

		die();
	}


	public function calculate_shipping(){
		WC_Shortcode_Cart::calculate_shipping();
		if( !wc_notice_count('error') ){
			$this->set_notice( __( 'Address updated', 'open-side-cart' ) );
		}
		$this->get_refreshed_fragments();
	}


	public function set_ajax_fragments($fragments){

		do_action( 'woocommerce_check_cart_items' );

		WC()->cart->calculate_totals();

		ob_start();
		osc_helper()->get_template( 'global/markup-notice.php' );
		$notice = ob_get_clean();
		
		ob_start();
		osc_helper()->get_template( 'osc-container.php' );
		$container = ob_get_clean();

		ob_start();
		osc_helper()->get_template( 'osc-slider.php' );
		$slider = ob_get_clean();

		ob_start(); 
		osc_helper()->get_template( 'osc-drawer.php' );
		$drawer = ob_get_clean();

		ob_start();
		osc_helper()->get_template( 'osc-shortcode.php' );
		$shortcode = ob_get_clean();


		$fragments['div.osc-markup-notices'] 	= $notice;
		$fragments['div.osc-container'] 		= $container; //Cart content
		$fragments['div.osc-slider'] 			= $slider;// Slider
		$fragments['div.osc-drawer'] 			= $drawer;// Slider
		$fragments['div.osc-sc-cont'] 			= $shortcode;
		
		return $fragments;

	}

	public function get_refreshed_fragments(){
		WC_AJAX::get_refreshed_fragments();
	}


	public function get_cart_count(){

		$exclude_gifts = osc_helper()->get_rewards_option( 'scbar-fg-qtyexc' ) === "yes";

		if( $this->glSettings['m-bk-count'] === 'items' ){
			$count 			= count( WC()->cart->get_cart() );
			$gift_count  	= $exclude_gifts ?  $this->get_gifts_count_in_cart('items') : 0;
		}
		else{
			$count = WC()->cart->get_cart_contents_count();
			$gift_count  	= $exclude_gifts ?  $this->get_gifts_count_in_cart() : 0;
		}

		return apply_filters( 'osc_cart_count', $count - $gift_count );
	}


	public function get_coupons(){

		if( !empty( $this->coupons ) ){
			return $this->coupons;
		}


		$showCoupon = $this->glSettings['m-cp-list'];

		if( $showCoupon === 'hide' ) return array();

		if( !trim($this->glSettings['m-cp-custom']) ){
			$includes = array();
		}
		else{
			$includes = array_map( 'trim', explode( ',', $this->glSettings['m-cp-custom'] ) );
		}


		$args = array(
		    'posts_per_page'   	=> (int) $this->glSettings['m-cp-count'],
		    'orderby'          	=> 'title',
		    'order'            	=> 'asc',
		    'post_type'        	=> 'shop_coupon',
		    'post_status'      	=> 'publish',
		    'include'			=> $includes
		);
		    
		$coupons_post = get_posts( $args );

		if( empty( $coupons_post ) ) return array();

		$coupons = array( 'valid' => array(), 'invalid' => array() );

		$hide_for_error_codes = array(
			105, //Not exists.
			107, //Expired
		);

		$hide_for_error_codes = apply_filters( 'osc_coupon_hide_invalid_codes', $hide_for_error_codes );

		foreach ( $coupons_post as $coupon_post ) {

			$coupon = new WC_Coupon( $coupon_post->ID );

			$discounts 	= new WC_Discounts( WC()->cart );
			$valid     	= $discounts->is_coupon_valid( $coupon );
			$code 		= $coupon->get_code();

			$off_amount = $coupon->get_amount();

			$off_value 	= 'percent' === $coupon->get_discount_type() ? $off_amount.'%' : wc_price( $off_amount ); 

			$data = array(
				'code' 		=> $code,
				'coupon' 	=> $coupon,
				'notice' 	=> '',
				'off_value' => $off_value
			);

			if( is_wp_error( $valid ) ){

				if( $showCoupon !== 'all' ) continue;

				$error_code = $valid->get_error_code();

				if( in_array( $error_code , $hide_for_error_codes ) ) continue;

				$data['notice'] = $valid->get_error_message();

			}

			$coupons[ is_wp_error( $valid ) ? 'invalid' : 'valid' ][] = $data;

		}


		$coupons = $this->coupons = apply_filters( 'osc_coupons_list', $coupons );

		return $coupons;
	}


	public function apply_coupon(){

		if( !isset( $_POST['coupon'] ) || !$_POST['coupon'] ) return;

		if( WC()->cart->apply_coupon( $_POST['coupon'] ) ){
			$this->set_notice( sprintf( __( '%s applied successfully', 'open-side-cart' ), strtoupper( esc_attr( $_POST['coupon'] ) ) ) );
		}

		$this->get_refreshed_fragments();
	}


	public function remove_coupon(){
		WC()->cart->remove_coupon( $_POST['coupon'] );
		$this->set_notice( __( 'Coupon has been removed', 'open-side-cart' ) );
		$this->get_refreshed_fragments();
	}



	public function get_totals(){

		$totals = array();

		if( WC()->cart->is_empty() ) return $totals;

		$sy 			= osc_helper()->get_style_option();
		$gl 			= osc_helper()->get_general_option();

		$show 			= $gl['scf-show'];
		$showSavings 	= in_array( 'savings', $show );
		$showSubtotal 	= in_array( 'subtotal', $show );
		$showCoupon 	= in_array( 'coupon', $show );
		$showShipping 	= in_array( 'shipping', $show );
		$showCalculator = in_array( 'shipping_calc' , $show );
		$showDiscount 	= in_array( 'discount' , $show );
		$showFee 		= in_array( 'fee', $show );
		$showTax 		= in_array( 'tax', $show );
		$showTotal 		= in_array( 'total', $show );


		if( $showSavings ){

			$savings = $this->get_cart_total_savings();

			if( $savings ){
				$totals['savings'] = array(
					'label' 	=> $this->glSettings['sct-savings'],
					'value' 	=> wc_price( $savings ),
					'action' 	=> 'less'
				);
			}

		}


		if( $showSubtotal ){
			$totals['subtotal'] = array(
				'label' 	=> $this->glSettings['sct-subtotal'],
				'value' 	=> WC()->cart->get_cart_subtotal(),
			);
		}


		if( $showShipping ){
			
			$label 				= __( 'Shipping', 'open-side-cart' );
			$calculatorToggle 	= '<span class="osc-toggle-slider" data-slider="shipping">%s</span>';
			$value 				= '';

			$packages = WC()->shipping()->get_packages();

			if( !empty( $packages ) ){

				//Support for 1 package only
				$package = $packages[0];

				$available_methods = $package['rates'];


				if( $available_methods ){
					$value = WC()->cart->get_cart_shipping_total();
				}
				else{

					$formatted_destination    = WC()->countries->get_formatted_address( $package['destination'], ', ' );

					if ( !$formatted_destination ) {
						$value = wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
					} else {
						$value = wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
					}
				}
			}

			if( $showCalculator ){

				$label = sprintf( $calculatorToggle, $label.'<span class="osc-icon-pencil"></span>' );
				$value = sprintf( $calculatorToggle, $value ? $value : __( 'Calculate', 'open-side-cart' ) );
			}


			$totals['shipping'] = array(
				'label' 	=> $label,
				'value' 	=> $value,
				'action' 	=> 'add'
			);
		}


		if( $showFee ){

			if( !empty( $this->barsWithActiveDiscountPoints ) ){

				$discountFeeIDs = array();
				foreach ($this->barsWithActiveDiscountPoints as $bar_key => $discountPoint ) {
					$discountFeeIDs[] = sanitize_title( $discountPoint['title'] );
				}
			}
		
			foreach ( WC()->cart->get_fees() as $fee ){

				if( !empty( $discountFeeIDs ) &&  in_array( $fee->id , $discountFeeIDs ) ) continue; // we will handle it later under checkpoint discount

				ob_start();
				wc_cart_totals_fee_html( $fee );
				$feeHTML = ob_get_clean();

				$totals[ 'fee_'.$fee->id ] = array(
					'label' 	=> $fee->name,
					'value' 	=> $feeHTML,
					'action' 	=> 'add'
				);
			}

			
		}

		if( $showTax && wc_tax_enabled() && WC()->cart->get_cart_tax() !== '' ){
			$totals['tax'] = array(
				'label' 	=> __( 'Tax', 'open-side-cart' ),
				'value' 	=> WC()->cart->get_cart_tax(),
				'action' 	=> 'add'
			);
		}

		if( $showDiscount && !empty( $this->barsWithActiveDiscountPoints ) ){

			foreach ( $this->barsWithActiveDiscountPoints as $bar_key => $checkpoint ) {
				
				$feeID = sanitize_title( $checkpoint['title'] );

				$checkpointDiscountFee = isset( WC()->cart->get_fees()[$feeID] ) ? WC()->cart->get_fees()[$feeID] : null;

				if( $checkpointDiscountFee ){

					ob_start();
					wc_cart_totals_fee_html( $checkpointDiscountFee );
					$feeHTML = ob_get_clean();


					$totals['checkpoint_discount_'.$bar_key] = array(
						'label' 	=> $checkpoint['title'],
						'value' 	=> $feeHTML,
						'action' 	=> 'less'
					);
					
				}

			}
		}

		if( $showDiscount && WC()->cart->has_discount() ){

			$discount 	= WC()->cart->get_discount_total();
			$discount 	= get_option( 'woocommerce_tax_display_cart' ) === 'incl' ? $discount + WC()->cart->get_discount_tax() : $discount;

			$totals['discount'] = array(
				'label' 	=> __( 'Discount', 'open-side-cart' ),
				'value' 	=> wc_price( $discount ),
				'action' 	=> 'less'
			);

			$appliedCouponNames = array();

			foreach ( WC()->cart->get_coupons() as $code => $coupon ){
				$appliedCouponNames[] = $code;
			}
			
			if( !empty( $appliedCouponNames ) ){
				$totals['discount']['label'] = $totals['discount']['label'] . ' ['. implode(', ', $appliedCouponNames ) .']';
			}

		}


		if( $showTotal ){
			$totals['total'] = array(
				'label' 	=> __( 'Total', 'open-side-cart' ),
				'value' 	=> WC()->cart->get_total(),
			);
		}

		return apply_filters( 'osc_cart_totals', $totals );

	}


	public function get_free_shipping_method_bar_data(){

		$data = array();

		WC()->cart->calculate_shipping();

		$packages = WC()->shipping()->get_packages();

		if( !empty( $packages ) ){

			//Support for 1 package only
			$package = $packages[0];

			$available_methods = $package['rates'];

			
			$shipping_zone 		= WC_Shipping_Zones::get_zone_matching_package( $package );
			$shipping_methods 	= $shipping_zone->get_shipping_methods(true);

			foreach ( $shipping_methods as $id => $obj ) {
				if( $obj instanceof WC_Shipping_Free_Shipping && ( $obj->requires === 'min_amount' || $obj->requires === 'either' ) ){

					$data['before_discount'] 	= $obj->ignore_discounts;
					$data['amount'] 			= $obj->min_amount;
		
					break;
				}
			}
		}

		return $data;
		
	}


	public function get_shipping_bar_data(){

		$data = array();

		$hasFreeShipping 	= false;
		$amountLeft 		= $fillPercentage = null;
		$subtotal 			= WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();

		$packages = WC()->shipping()->get_packages();

		if( empty( $packages ) ) return $data;

		//Support for 1 package only
		$package = $packages[0];

		$available_methods = $package['rates'];

		foreach ( $available_methods as $id => $obj ) {
			if( $obj instanceof WC_Shipping_Free_Shipping ){
				$hasFreeShipping = true;
				break;
			}
		}

		if( !$hasFreeShipping ){
			$shipping_zone 		= WC_Shipping_Zones::get_zone_matching_package( $package );
			$shipping_methods 	= $shipping_zone->get_shipping_methods(true);

			foreach ( $shipping_methods as $id => $obj ) {
				if( $obj instanceof WC_Shipping_Free_Shipping && ( $obj->requires === 'min_amount' || $obj->requires === 'either' ) ){
					
					if( $obj->ignore_discounts === "no" && !empty( WC()->cart->get_coupon_discount_totals() ) ){
						foreach ( WC()->cart->get_coupon_discount_totals() as $coupon_code => $coupon_value ) {
							$subtotal -= $coupon_value;
						}
					}

					if( $subtotal >= $obj->min_amount ){
						$hasFreeShipping = true;
					}
					else{
						$amountLeft 	= $obj->min_amount - $subtotal;
						$fillPercentage =  ceil( ($subtotal/$obj->min_amount) * 100 );
					}
					break;
				}
			}
		}

		if( !$hasFreeShipping && is_null( $amountLeft ) ) return $data;

		$data = array(
			'free' 				=> $hasFreeShipping,
			'amount_left' 		=> $amountLeft,
			'fill_percentage' 	=> $hasFreeShipping ? 100 : $fillPercentage
		);

		return apply_filters( 'osc_shipping_bar_data', $data );

	}


	//Get suggested products
	public function get_suggested_products( $type = '' ){

		if( $this->suggestedProducts ) return $this->suggestedProducts;

		$customIDS 		= array_filter( explode(',', $this->glSettings['scsp-ids']) );
		$type 			= $type ? $type : $this->glSettings['scsp-type'];
		$count 			= (int) $this->glSettings['scsp-count'];

		$exclude_ids = apply_filters( 'osc_suggested_product_exclude', array(), $type );

		if( !$count ) return;


		$product_ids = !empty( $customIDS ) ? $customIDS : array();

		if( empty( $product_ids ) ){
			if( $type === 'cross_sells' ){
				$product_ids = WC()->cart->get_cross_sells();
			}

			else if( $type === 'up_sells' ){

				foreach ( array_reverse( WC()->cart->get_cart() ) as $cart_item ) {

					$product =  $cart_item['data']->get_parent_id() ? wc_get_product( $cart_item['data']->get_parent_id() ) : $cart_item['data'];

					$product_ids = array_merge( $product_ids, $product->get_upsell_ids() );

					if( count( $product_ids ) >= $count ) break;

				}
			}

			else{

				foreach ( array_reverse( WC()->cart->get_cart() ) as $cart_item ) {

					$product_ids 	= array_merge( $product_ids, wc_get_related_products( $cart_item['product_id']  ) );

					if( count( $product_ids ) >= $count ) break;

				}
			}

		}

		//Remove already added product ids
		$addedIDs = array();
		foreach ( WC()->cart->get_cart() as $cart_item ) {
			$addedIDs[] = isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] ? $cart_item['variation_id'] : $cart_item['product_id'];
		}


		$exclude_ids = array_merge( $addedIDs, $exclude_ids );

		foreach ($product_ids as $key => $product_id) {
			if( in_array( $product_id, $exclude_ids ) ){
				unset( $product_ids[ $key ] );
			}
		}

	
		//Force random products
		$items_count = $this->glSettings['scsp-random'] === 'yes' ? $count  : ( count( $product_ids ) >= $count ? $count : count( $product_ids ) );

		$products = array();
		
		$args = array(
			'post_type'            	=> array( 'product', 'product_variation'),
			'post_status'    		=> 'publish',
			'ignore_sticky_posts'  	=> 1,
			'no_found_rows'       	=> 1,
			'posts_per_page'       	=> $items_count,
			'orderby'             	=> 'rand',
			'meta_query'			=> array(
					array(
			        'key' => '_stock_status',
			        'value' => 'instock',
			        'compare' => '=',
			    )
			)
		);

		if( !empty( $product_ids ) ){
			$args['post__in'] = $product_ids;
		}
		else{
			$args['post__not_in'] = $exclude_ids;
		}


		$args = apply_filters( 'osc_suggested_product_args', $args );


		if( $args['posts_per_page'] !== 0 ){
			$products = new WP_Query( $args );
			$this->suggestedProducts = $products;
		}

	
		return $products;

	}


	public function get_bundle_items(){

		if( !empty( $this->bundleItems ) ){
			return $this->bundleItems;
		}

		$data = array(

			'bundled_items' => array(
				'key' 		=> 'bundled_items',
				'type' 		=> 'parent',
				'delete' 	=> true,
				'qtyUpdate' => true,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true	
			),

			'bundled_by' => array(
				'key' 		=> 'bundled_by',
				'type' 		=> 'child',
				'delete' 	=> false,
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),


			'mnm_contents' => array(
				'key' 		=> 'mnm_contents',
				'type' 		=> 'parent',
				'delete' 	=> true,
				'qtyUpdate' => true,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),


			'mnm_container' => array(
				'key' 		=> 'mnm_container',
				'type' 		=> 'child',
				'delete' 	=> false,
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),

			'composite_children' => array(
				'key' 		=> 'composite_children',
				'type' 		=> 'parent',
				'delete' 	=> true,
				'qtyUpdate' => true,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),


			'composite_parent' => array(
				'key' 		=> 'composite_parent',
				'type' 		=> 'child',
				'delete' 	=> false,
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),

			'woosb_ids' => array(
				'key' 		=> 'woosb_ids',
				'type' 		=> 'parent',
				'delete' 	=> true,
				'qtyUpdate' => true,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),

			'woosb_parent_id' => array(
				'key' 		=> 'woosb_parent_id',
				'type' 		=> 'child',
				'delete' 	=> false,
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> false
			),

			'osc_gift' => array(
				'key' 		=> 'osc_gift',
				'type' 		=> 'parent',
				'delete' 	=>  osc_helper()->get_rewards_option('scbar-fg-en-delete') === "yes",
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'save' 		=> false,
				'price' 	=> true,
			),

			'wooco_parent_id' => array(
				'key' 		=> 'wooco_parent_id',
				'type' 		=> 'child',
				'delete' 	=> false,
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),

			'wooco_keys' => array(
				'key' 		=> 'wooco_keys',
				'type' 		=> 'parent',
				'delete' 	=> true,
				'qtyUpdate' => true,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			),


			'bopobb_parent_id' => array(
				'key' 		=> 'bopobb_parent_id',
				'type' 		=> 'child',
				'delete' 	=> false,
				'qtyUpdate' => false,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> false
			),

			'bopobb_ids' => array(
				'key' 		=> 'bopobb_ids',
				'type' 		=> 'parent',
				'delete' 	=> true,
				'qtyUpdate' => true,
				'image' 	=> true,
				'link' 		=> true,
				'savings' 	=> true
			)

		);

		$this->bundleItems = apply_filters( 'osc_product_bundle_items', $data );

		return $this->bundleItems;

	}


	public function is_bundle_item( $cart_item ){

		$bundleItems = $this->get_bundle_items();
		$isBundle = array_intersect_key( $bundleItems , $cart_item );
		return !empty( $isBundle ) ? array_values( array_intersect_key( $bundleItems , $cart_item ) )[0] : $isBundle;

	}

	public function save_for_later(){

		$cart_key 	= sanitize_text_field( $_POST['cart_key'] );

		$cart_item 	= WC()->cart->get_cart_item( $cart_key );

		$savedCart 	= (array) WC()->session->get( 'osc_saved_for_later_cart' );

		if ( $cart_item ) {

			$savedCart[ $cart_key ] = $cart_item;

			unset( $savedCart[ $cart_key ]['data'] );

			WC()->session->set( 'osc_saved_for_later_cart', $savedCart );

			WC()->cart->remove_cart_item( $cart_key );

			do_action( 'osc_saved_for_later_item_added', $cart_key, $savedCart );

		}

		$this->get_refreshed_fragments();

		die();
	}


	public function get_saved_for_later_items(){

		$saved_items = (array) WC()->session->get( 'osc_saved_for_later_cart' );

		$update = false;

		foreach ( $saved_items as $cart_key => $item ) {

			$product = wc_get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );

			if( !$product ){
				unset( $saved_items[ $cart_key ] );
				$update = true;
				continue;
			}

			$saved_items[$cart_key]['data'] = $product;

		}

		if( $update ){
			WC()->session->set( 'osc_saved_for_later_cart', $saved_items );
		}


		return $saved_items;
		
	}

	public function delete_save_for_later_item(){

		$cart_key 	= sanitize_text_field( $_POST['cart_key'] );

		$savedCart 	= $this->get_saved_for_later_items();

		if ( isset( $savedCart[ $cart_key ] ) ) {

			unset( $savedCart[ $cart_key ] );

			WC()->session->set( 'osc_saved_for_later_cart', $savedCart );

			$this->set_notice( __( 'Item has been removed from Save For Later.', 'open-side-cart' ), 'success' );

			do_action( 'osc_saved_for_later_item_deleted', $cart_key, $savedCart );

		}

		$this->get_refreshed_fragments();

		die();
	}


	public function move_save_for_later_item_to_cart(){

		$cart_key 	= sanitize_text_field( $_POST['cart_key'] );

		$savedCart 	= $this->get_saved_for_later_items();

		if ( isset( $savedCart[ $cart_key ] ) ) {

			$restored = WC()->cart->restore_cart_item( $cart_key );

			if( !$restored ){ //if woocommerce is unable to restore cart item

				$restore_item 	= $savedCart[ $cart_key ];

				$wcCart = WC()->cart->get_cart();

				$restore_item_product = wc_get_product( $restore_item['variation_id'] ? $restore_item['variation_id'] : $restore_item['product_id'] );

				if( $restore_item_product ){

					$restore_item['data'] 	= $restore_item_product;

					$product_id     		= (int) apply_filters( 'woocommerce_add_to_cart_product_id', $restore_item_product->get_id() );

					$quantity       		= $restore_item['quantity'];
					$variation_id   		= (int) $restore_item['variation_id'];
					$variations 			= $restore_item['variation'];
					$cart_item_data 		= wc_get_formatted_cart_item_data( $restore_item );

					if ( apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations, $cart_item_data ) ) {
						
						$wcCart[ $cart_key ] = $restore_item; 

						WC()->cart->set_cart_contents( apply_filters( 'woocommerce_cart_contents_changed', $wcCart ) );

						do_action( 'woocommerce_cart_loaded_from_session', $wcCart );

					}

				}

			}

			unset( $savedCart[ $cart_key ] );

			WC()->session->set( 'osc_saved_for_later_cart', $savedCart );

			if( $restored ){
				$this->set_notice( __( 'Item added to cart.', 'open-side-cart' ), 'success' );
				do_action( 'osc_saved_for_later_item_moved', $cart_key, $savedCart );
			}

		}

		$this->get_refreshed_fragments();

		die();
	}

	public function persistent_save_for_later_update(){

		if ( get_current_user_id() && apply_filters( 'osc_persistent_save_for_later_enabled', true ) ) {

			update_user_meta(
				get_current_user_id(),
				'_osc_save_for_later_persistent_' . get_current_blog_id(),
				array(
					'cart' => $this->get_saved_for_later_items(),
				)
			);
		}

	}

	public function get_saved_items_count(){
		return count( $this->get_saved_for_later_items() );
	}

	public function user_logged_in( $user_login, $user ){
		update_user_meta( $user->ID, '_osc_load_save_for_later_after_login', 1 );
	}


	public function sync_saved_for_later_list_for_logged_in_user(){

		if( !is_user_logged_in() || !get_user_meta( get_current_user_id(), '_osc_load_save_for_later_after_login', true ) ) return;

		$userSavedCart = $this->get_user_meta_saved_for_later();

		WC()->session->set( 'osc_saved_for_later_cart', array_merge( $userSavedCart, $this->get_saved_for_later_items() ) );

		delete_user_meta( get_current_user_id(), '_osc_load_save_for_later_after_login' );

	}


	public function get_user_meta_saved_for_later(){

		$saved_cart = array();

		if ( apply_filters( 'osc_persistent_save_for_later_enabled', true ) ) {

			$saved_cart_meta = get_user_meta( get_current_user_id(), '_osc_save_for_later_persistent_' . get_current_blog_id(), true );

			if ( isset( $saved_cart_meta['cart'] ) ) {
				$saved_cart = array_filter( (array) $saved_cart_meta['cart'] );
			}
		}

		return $saved_cart;
	}


	public function get_cart_total_savings(){
		$productSavings 	= $this->get_cart_total_product_savings();
		$rewardsDiscount 	= array_sum($this->totalRewardsDiscount);
		$savings = $this->get_cart_total_product_savings() + WC()->cart->get_discount_total() + $rewardsDiscount ;
		return apply_filters( 'osc_cart_savings', $savings, $productSavings, WC()->cart->get_discount_total(), $rewardsDiscount  );
	}

	public function get_cart_total_product_savings() {

    	$savings = 0;

		foreach ( WC()->cart->get_cart() as $cart_item ) {

			$bundleItem = $this->is_bundle_item($cart_item);
			if( $bundleItem && ( !isset( $bundleItem['savings'] ) || !$bundleItem['savings'] ) ) continue;

			$product = $cart_item['data'];

			// Regular price (could be variable/simple product)
			$regular_price = (float) $product->get_regular_price();
			$sale_price    = (float) $product->get_price();

			// If discounted
			if ( $regular_price > $sale_price ) {
			$savings += ( $regular_price - $sale_price ) * $cart_item['quantity'];
			}
		}

    	return apply_filters( 'osc_cart_total_product_savings', $savings );
	}


	public function get_cart_item_savings( $cart_item ){

		$bundleItem = $this->is_bundle_item($cart_item);
		if( $bundleItem && ( !isset( $bundleItem['savings'] ) || !$bundleItem['savings'] ) ) return 0;

		$cart_item_key = $cart_item['key'];

		$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

		$price_savings 	= $total_savings = 0;

		$data = array();

		$product_regular_price 		= wc_prices_include_tax() ? wc_get_price_including_tax( $_product, [ 'price' => $_product->get_regular_price() ] ) : wc_get_price_excluding_tax( $_product, [ 'price' => $_product->get_regular_price() ] );

		$product_price         		= wc_prices_include_tax() ? wc_get_price_including_tax( $_product, [ 'price' => $_product->get_price() ] ) : wc_get_price_excluding_tax( $_product, [ 'price' => $_product->get_price() ] );


		$price_savings = $this->get_savings_data( $product_price, $product_regular_price );
		
		if( !empty( $price_savings ) ){
			$data['price'] = $price_savings;
		}


		if( isset( $cart_item['line_subtotal'] ) ){

			$product_regular_total 		= $product_regular_price * $cart_item['quantity'];
			$product_subtotal 			= !wc_prices_include_tax() ? $cart_item['line_subtotal'] : ( $cart_item['line_subtotal'] + $cart_item['line_subtotal_tax'] );

			$total_savings = $this->get_savings_data( $product_subtotal, $product_regular_total );

			if( !empty( $total_savings ) ){
				$data['total'] = $total_savings;
			}

		}


		return $data;

		
	}


	public function get_savings_data( $new_amount, $base_amount, $unit = '' ){

		$return 	= array();
		$savings 	= 0;

		if( $base_amount > $new_amount ){
			$savings = $base_amount - $new_amount;
		}

		if( $savings >= 0.1 ){

			if( !$unit ){
				$unit = $this->glSettings['scb-prod-savings'];
			}

			if( $unit === 'amount' ){
				$price_savings_text = wc_price( $savings );
				
			}
			else{
				$price_savings_text = '<span>'.round( ($savings/$base_amount) * 100 ) .'%</span>';
			}

			$return['value'] 	= $savings;
			$return['text'] 	= sprintf( __( '<span %1$s>Save</span> %2$s', 'open-side-cart' ), 'class="osc-psavlabel"', $price_savings_text  );

		}

		return $return;

		
	}


}

function osc_cart(){
	return OSC_Cart::get_instance();
}
osc_cart();
