jQuery(document).ready(function($){

	var isCartPage 		= osc_params.isCart == '1',
		isCheckoutPage 	= osc_params.isCheckout == '1';


	var get_wcurl = function( endpoint ) {
		return osc_params.wc_ajax_url.toString().replace(
			'%%endpoint%%',
			endpoint
		);
	};


	var Notice = {

		timeout: null,

		markupTimeout: null,

		$cartNoticeCont: function(){
			return $('.osc-markup').find('.osc-notice-container');
		},

		$markupNoticeCont: function(){
			return $('.osc-markup-notices')
		},

		add: function( notice, type = 'success', clearPrevious = true ){

			if( clearPrevious ){
				Notice.$cartNoticeCont().html('');
			}

			var noticeHTML = type === 'success' ? osc_params.html.successNotice.toString().replace( '%s%', notice ) : osc_params.html.errorNotice.toString().replace( '%s%', notice );

			Notice.$cartNoticeCont().html( noticeHTML );

		},


		setHTML: function(noticeHTML){
			Notice.$cartNoticeCont().add( Notice.$markupNoticeCont() ).html(noticeHTML);
		},

		showNotification: function(){

			if( !cart.isOpen() ){
				Notice.showMarkupNotice();
				return;
			}

			Notice.$cartNoticeCont().slideDown();
			
			clearTimeout(Notice.timeout);

			Notice.timeout = setTimeout(function(){
				Notice.$cartNoticeCont().slideUp('slow');
			}, osc_params.notificationTime );

		},


		hideNotification: function(){
			Notice.$cartNoticeCont().hide();
		},

		hideMarkupNotice: function(){
			Notice.$markupNoticeCont().removeClass('osc-active');
		},


		showMarkupNotice: function(){

			var $markupNotice = Notice.$markupNoticeCont();

			var $noticeCont = $markupNotice.find('.osc-notice-container .osc-notices');

			if( !$noticeCont.length || $noticeCont.children().length === 0 ) return;

			setTimeout(function(){$markupNotice.addClass('osc-active')},10);
			
			clearTimeout(Notice.markupTimeout);

			Notice.markupTimeout = setTimeout(function(){
				$markupNotice.removeClass('osc-active');
			},osc_params.notificationTime )
		}
	}

	var masonryInitialised = {};

	function initMasonryLayout( type = '' ){

		var layouts = {
			saveLater: {'.osc-savl-column': '.osc-savl-prod-cont'},
			cart: {'.osc-pattern-card': '.osc-product-cont'},
			suggested: {'.osc-sp-column ul.osc-sp-slider': '.osc-sp-prod-cont'}
		};

		var initLayouts = {};

		if( type ){
			if( Array.isArray( type ) ){
				$.each( type, function(index, type_val ){
					initLayouts[ type_val ] = layouts[ type_val ];
				} )
			}
			else{
				initLayouts[type] = layouts[type];
			}
			
		}else{
			initLayouts = layouts;
		}


		$.each( initLayouts, function(type, layout){

			if( masonryInitialised[type] &&  document.body.contains( masonryInitialised[type][0] ) ) return true;

			$.each( layout, function(cont, childClass){
				if( $(cont).length && $(cont).is(':visible') ){
					$(cont).masonry({
						// options
						itemSelector: childClass,
						columnWidth: childClass, /* Each column takes 50% */
						percentPosition: true
					});
					masonryInitialised[type] = $(cont);
				}
			})
		})

		
	}


	var QuickView  = {

		init: function(){
			$( document.body ).on( 'found_variation', '.osc-qv-container .variations_form', QuickView.onVariationSelect );
			$( document.body ).on( 'reset_data hide_variation', '.osc-qv-container .variations_form', QuickView.onVariationReset );
		},

		load: function(){

			var $form = $( document.body ).find('.osc-qv-container .variations_form');

			if( !$form.length ) return;

			$form.each(function () {
				$(this).wc_variation_form();
				$(this).find('.variations select').change();
			});

		},

		onVariationSelect: function( event, variation ){

			if( !variation || !variation.image || !variation.image.src ) return;

			const $container 	= $( this ).closest('.osc-qv-container');
			const $img 			= $container.find('.osc-fw-qv-product-image');

	        QuickView.storeOriginalImageAttrs( $container );

			$img.attr({
				src    : variation.image.src,
				alt    : variation.image.alt || '',
				width  : variation.image.src_w || '',
				height : variation.image.src_h || ''
			});

			if( variation.image.srcset ){
				$img.attr( 'srcset', variation.image.srcset );
			}
			else{
				$img.removeAttr( 'srcset' );
			}

			if( variation.image.sizes ){
				$img.attr( 'sizes', variation.image.sizes );
			}
			else{
				$img.removeAttr( 'sizes' );
			}

		},

		onVariationReset: function(){
			const $img = $( this ).closest('.osc-qv-container').find('.osc-fw-qv-product-image');
			QuickView.resetImage( $img );
		},

		storeOriginalImageAttrs: function( $container ){

	        const $img = $container.find('.osc-fw-qv-product-image');

	        if( !$img.length || $img.data('osc-fw-originalized') ) return;

			[ 'src', 'srcset', 'sizes', 'alt', 'width', 'height' ].forEach(function( attr ){

				$img.attr( 'data-o_' + attr, $img.attr( attr ) || '' );

			});

			$img.data('osc-fw-originalized', true);
	    },

	    resetImage: function( $img ){

	    	[ 'src', 'srcset', 'sizes', 'alt', 'width', 'height' ].forEach(function( attr ){

				const original = $img.attr( 'data-o_' + attr );

				if( original ){
					$img.attr( attr, original );
				}
				else{
					$img.removeAttr( attr );
				}

			});

	    }

	}

	QuickView.init();

	class Container{

		static eventHandlerCalled = false;

		constructor( $modal, container ){
			this.$modal 			= $modal;
			this.container 			= container || 'cart';
		}


		isOpen(){
			return this.$modal.hasClass('osc-'+this.container+'-active');
		}

		eventHandlers(){
			$(document.body).on( 'wc_fragments_loaded updated_checkout', this.onCartUpdate.bind(this) );	
		}

		onCartUpdate(){
			this.unblock();
			Notice.showNotification();
		}

		setAjaxData( data, noticeSection ){

			var ajaxData = {
				container: this.container,
				noticeSection: this.container,
				isCheckout: isCheckoutPage,
				isCart: isCartPage
			}


			if( typeof data === 'object' ){

				$.extend( ajaxData, data );

			}
			else{

				var serializedData = data;

				$.each( ajaxData, function( key, value ){
					serializedData += ( '&'+key+'='+value );
				} )
		
				ajaxData = serializedData;

			}

			return ajaxData;
		}


		toggle( type ){

			var $activeEls 	= this.$modal.add( 'body' ).add('html'),
				activeClass = 'osc-'+ this.container +'-active';

			if( type === 'show' ){
				$activeEls.addClass(activeClass);
			}
			else if( type === 'hide' ){
				$activeEls.removeClass(activeClass);
				Notice.hideNotification();
			}
			else{
				$activeEls.toggleClass(activeClass);
			}


			$(document.body).trigger( 'osc_' + this.container + '_toggled', [ type ] );

		}


		block(){
			this.$modal.addClass('osc-loading');
		}

		unblock(){
			this.$modal.removeClass('osc-loading');
		}


		refreshMyFragments(){

			if( osc_params.refreshCart === "yes" && typeof wc_cart_fragments_params !== 'undefined' ){
				$( document.body ).trigger( 'wc_fragment_refresh' );
				return;
			}

			this.block();

			$.ajax({
				url: get_wcurl( 'osc_refresh_fragments' ),
				type: 'POST',
				context: this,
				data: {},
				success: function( response ){
					this.updateFragments(response);
					$( document.body ).trigger( 'wc_fragments_refreshed' );
				},
				complete: function(){
					this.unblock();
				}
			})

		}


		updateCartCheckoutPage(){

			//Refresh checkout page
			if( isCheckoutPage ){
				if( $( 'form.checkout' ).length === 0 ){
					location.reload();
					return;
				}
				$(document.body).trigger("update_checkout");
			}

			//Refresh Cart page
			if( isCartPage ){
				$(document.body).trigger("wc_update_cart");
			}

		}

		updateFragments( response ){

			if( response.fragments ){

				$( document.body ).trigger( 'osc_before_loading_fragments', [ response ] );

				this.block();

				//Set fragments
		   		$.each( response.fragments, function( key, value ) {
					$( key ).replaceWith( value );
				});

		   		if( typeof wc_cart_fragments_params !== 'undefined' && ( 'sessionStorage' in window && window.sessionStorage !== null ) ){

		   			sessionStorage.setItem( wc_cart_fragments_params.fragment_name, JSON.stringify( response.fragments ) );
					localStorage.setItem( wc_cart_fragments_params.cart_hash_key, response.cart_hash );
					sessionStorage.setItem( wc_cart_fragments_params.cart_hash_key, response.cart_hash );

					if ( response.cart_hash ) {
						sessionStorage.setItem( 'wc_cart_created', ( new Date() ).getTime() );
					}

				}

				$( document.body ).trigger( 'wc_fragments_loaded' );

				this.unblock();

			}

			if( osc_params.refreshCart === "yes" && typeof wc_cart_fragments_params !== 'undefined' ){
				this.block();
				$( document.body ).trigger( 'wc_fragment_refresh' );
				return;
			}

		}

	}


	class Cart extends Container{

		static isWCAjaxAddToCart = false;

		constructor( $modal ){

			super( $modal, 'cart' );

			this.baseQty 				= 1;
			this.qtyUpdateDelay 		= null;
			this.bodyPosition 			= false;
			this.cartLoaded 			= false;
			this.blockAddedToCartCalled = false;
			this.barsData 				= {};
			this.barsWidth 				= {};

			this.refreshFragmentsOnPageLoad();
			this.eventHandlers();
			this.initSlider();
			this.toggleBasket();

		}


		refreshFragmentsOnPageLoad(){
			if( osc_params.fetchCart === 'page_load' ){
				setTimeout(function(){
					this.refreshMyFragments();
				}.bind(this), osc_params.fetchDelay )
			}
		}

		eventHandlers(){

			super.eventHandlers();

			this.$modal.on( 'click', '.osc-chng', this.toggleQty.bind(this) );
			this.$modal.on( 'change', '.osc-qty', this.changeInputQty.bind(this) );
			this.$modal.on( 'click', '.osc-undo-item', this.undoItem.bind(this) );
			this.$modal.on( 'focusin', '.osc-qty', this.saveQtyFocus.bind(this) );
			this.$modal.on( 'click', '.osc-smr-del', this.deleteIconClick.bind(this) );
			this.$modal.on( 'click', '.osch-close, .osc-opac, .osc-cart-close', this.closeCartOnClick.bind(this) );
			this.$modal.on( 'click', '.osc-basket', this.toggleCart.bind(this) );

			$( document.body ).on( 'click', '.osc-ecl', this.emptyCart.bind(this) );

			$(document.body).on( 'osc_cart_updated', this.updateCartCheckoutPage.bind(this) );
			$(document.body).on( 'click', 'a.added_to_cart, .osc-cart-trigger', this.openCart.bind(this) );
			$(document.body).on( 'added_to_cart ', this.addedToCart.bind(this) );

			if( osc_params.ajaxAddToCart === 'yes' ){
				$(document.body).on( 'submit', 'form.cart', this.addToCartFormSubmit.bind(this) );
			}

			if( typeof wc_cart_fragments_params === 'undefined' ){
				$( window ).on( 'pageshow' , this.onPageShow.bind(this) );
			}

			if( osc_params.triggerClass ){
				$(document.body).on( 'click', '.'+osc_params.triggerClass, this.openCart.bind(this) );
			}



			if( isCheckoutPage || isCartPage ){
				$(document.body).on( 'updated_shipping_method', this.refreshMyFragments.bind(this) );
			}

			$(document.body).on( 'wc-blocks_added_to_cart', this.blockAddedToCart.bind(this) );

			$(document.body).on( 'adding_to_cart', this.checkIfWCAjaxAddToCart.bind(this) );

			//Animate shipping bar
			$(document.body).on( 'osc_before_loading_fragments adding_to_cart wc_fragment_refresh', this.storeProgressBarWidth.bind(this) );

			//$(document.body).on( 'wc_fragments_loaded', this.checkIfWCAjaxAddToCartUnset.bind(this) );

			initMasonryLayout( ['cart', 'suggested' ] );

			if( osc_params.autoOpenCart === 'yes' && osc_params.addedToCart === 'yes'){
				this.openCart();
			}


			this.$modal.on( 'click', '.osc-save', this.saveForLater.bind(this) );

		}


		saveForLater(e){

			if( osc_params.saveForLaterNeedsLogin ) return;

			var $product 		= $(e.currentTarget).closest('.osc-product'),
				cartKey 		= $product.data('key'),
				formData 		= {
					cart_key: cartKey,
				}

				this.block();
				this.saveScrollPosition();
			
			
			$.ajax({
				url: get_wcurl( 'osc_save_for_later' ),
				type: 'POST',
				context: this,
				data: this.setAjaxData(formData),
				success: function(response){

					this.updateFragments( response );

					$(document.body).trigger( 'osc_added_to_save_list', [response, cartKey] );
					$(document.body).trigger( 'osc_cart_updated', [response] );

					this.setScrollPosition();
					this.unblock();

					var $saveLaterIcon = this.$modal.find('.osch-savelater');

					if( $saveLaterIcon.length ){
						$saveLaterIcon.addClass('osc-shake-animate');
						setTimeout(function(){
							$saveLaterIcon.removeClass('osc-shake-animate');
						},1200);
					}

				}

			})

		}

		checkIfWCAjaxAddToCartUnset(){
			this.isWCAjaxAddToCart = false;
		}

		checkIfWCAjaxAddToCart(e, $button, data){

			Cart.isWCAjaxAddToCart = true;

			if( ( !(data instanceof FormData) || !data.has('action') || !data.get('action') === 'osc_add_to_cart' ) && $button.hasClass('ajax_add_to_cart') ){
				this.isWCAjaxAddToCart = true;
			}
		}


		toggleCart(e){
			if( this.isOpen() ){
				this.closeCartOnClick(e);
			}
			else{
				this.openCart(e);
			}
			
		}


		openCart(e){
			if( e ){
				e.preventDefault();
				e.stopImmediatePropagation();
			}

			if( !this.cartLoaded && osc_params.fetchCart === 'cart_open' ){
				this.refreshMyFragments();
				this.cartLoaded = true;
			}

			this.toggle('show');
			this.animateProgressBar();
			Notice.hideMarkupNotice();

		}

		addToCartFormSubmit(e){

			var $form = $(e.currentTarget);

			if( $form.closest('.product').hasClass('product-type-external') || $form.siblings('.osc-disable-atc').length ) return;

			var $button  		= e.originalEvent && e.originalEvent.submitter ? $(e.originalEvent.submitter) : $form.find( 'button[type="submit"]'),
				formData 		= new FormData($form.get(0)),
				productData  	= $form.serializeArray(),
				hasProductId 	= false;

			//Check for woocommerce custom quantity code 
			//https://docs.woocommerce.com/document/override-loop-template-and-show-quantities-next-to-add-to-cart-buttons/
			$.each( productData, function( key, form_item ){
				if( form_item.name === 'productID' || form_item.name === 'add-to-cart' ){
					if( form_item.value ){
						hasProductId = true;
						return false;
					}
				}
			})

			//If no product id found , look for the form action URL
			if( !hasProductId && $form.attr('action') ){
				var is_url = $form.attr('action').match(/add-to-cart=([0-9]+)/),
					productID = is_url ? is_url[1] : false; 
			}

			// Add submitted button value
	        if( $button.attr('name') && $button.attr('value') ){
	            formData.append( $button.attr('name'), $button.attr('value') );
	        }

	        if( productID ){
	        	formData.append( 'add-to-cart', productID );
	        }

	        formData.append( 'action', 'osc_add_to_cart' );

	        var doAjaxAddToCart = true;

	        
        	$.each( osc_params.skipAjaxForData, function( key, value ){
        		if( formData.has(key) && ( !value || formData.get(key) == value ) ){
        			doAjaxAddToCart = false;
        			return false;
        		}
        	} )
	        

	        if( doAjaxAddToCart ){
	        	e.preventDefault();
	        	this.addToCartAjax( $button, formData );//Ajax add to cart
	        }
			
		}


		addToCartAjax( $button, formData ){

			var cart = this;

			this.block();

			$button.addClass('loading');

			// Trigger event.
			$( document.body ).trigger( 'adding_to_cart', [ $button, formData ] );

			//formData.append('noticeSection', 'slider');

			$.ajax({
				url: get_wcurl( 'osc_add_to_cart' ),
				type: 'POST',
				context: this,
				cache: false,
			    contentType: false,
			    processData: false,
				data: formData,
			    success: function(response){

					if(response.fragments){
						// Trigger event so themes can refresh other areas.
						$( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $button ] );
					}else if(response.notice ){
						Notice.setHTML(response.notice);
						Notice.showNotification();
					}
					else{
						//window.location.reload();
					}

			    },
			    complete: function(){
			    	this.unblock();
			    	$button.removeClass('loading').addClass('added');
			    }
			})
		}

		addedToCart( e, response, hash, $button ){

			$(document.body).trigger( 'osc_cart_updated', [response] );
	
			var _this = this;

			this.flyToCart( $button, function(){
				if( osc_params.autoOpenCart === "yes" ){
					setTimeout(function(){
						_this.openCart();	
					},20 )
				}
			} );
		}


		blockAddedToCart(){

			if( !Cart.isWCAjaxAddToCart && !this.blockAddedToCartCalled ){

				this.refreshMyFragments();
				
				var _this = this;

				if( osc_params.autoOpenCart === "yes" ){
					setTimeout(function(){
						_this.openCart();	
					},20 )
				}

				this.blockAddedToCartCalled = true;

				setTimeout( function(){
					_this.blockAddedToCartCalled = false;
				}, 200 );

				Cart.isWCAjaxAddToCart = false;

			}


						
		}


		flyToCart( $atcEL, callback ){

			var $basket = this.$modal.find('.osc-basket').length ? this.$modal.find('.osc-basket') : $(document.body).find('.osc-sc-cont');

			if( !$basket.length || osc_params.flyToCart !== 'yes' || !$atcEL || !$atcEL.length ){
				callback();
				return;
			}

			var customDragImgClass 	= osc_params.productFlyClass,
				$dragIMG 			= null,
				$product 			= $atcEL.closest('.product');


			//If has product container
			if( $product.length ){

				$product = $($product[0]);

				var $productGallery = $product.find('.woocommerce-product-gallery');

				if( customDragImgClass && $product.find( customDragImgClass ).length ){
					$dragIMG = $product.find( customDragImgClass );
				}
				else if( $product.find( 'img[data-oscFly="fly"]' ).length ){
					if( $productGallery.length ){
						$dragIMG = $productGallery.find( '.flex-active-slide img[data-oscFly="fly"]' ).length ? $productGallery.find( '.flex-active-slide img[data-oscFly="fly"]' ) : $productGallery.find( 'img[data-oscFly="fly"]' )
					}
					else{
						$dragIMG = $product.find( 'img[data-oscFly="fly"]' );
					}
				}
				else if( $productGallery.length ){
					$dragIMG = $productGallery;
				}
				else{
					$dragIMG = $product;
				}

			}
			else if( customDragImgClass ){
				var moveUp = 4;
				for ( var i = moveUp; i >= 0; i-- ) {
					var $foundImg = $atcEL.parent().find( customDragImgClass );
					if( $foundImg.length ){
						$dragIMG = $foundImg;
						return false;
					}
				}
			}


			if( !$dragIMG || !$dragIMG.length ){
				callback();
				return;
			}

			$dragIMG = $dragIMG.eq(0);

			var $imgclone = $dragIMG
				.clone()
	    		.offset({
		            top: $dragIMG.offset().top,
		            left: $dragIMG.offset().left
		        })
	        	.addClass( 'osc-fly-animating' )
	            .appendTo( $('body') )
	            .animate({
	            	'top': $basket.offset().top - 20,
		            'left': $basket.offset().left - 20,
		            'width': 75,
		            'height': 75
		        }, parseInt( osc_params.flyToCartTime ), 'easeInOutExpo' );
	        
	        setTimeout(function () {
	        	callback()
	        }, parseInt( osc_params.flyToCartTime ) );

	        $imgclone.animate({
	        	'width': 0,
	        	'height': 0
	        }, function () {
	        	$(this).detach();
	        });

		}


		toggleQty(e){

			var $toggler 	= $(e.currentTarget),
				$input 		= $toggler.siblings('.osc-qty');

			if( !$input.length ) return;

			var baseQty = this.baseQty = parseFloat( $input.val() ),
				step 	= parseFloat( $input.attr('step') ),
				action 	= $toggler.hasClass( 'osc-plus' ) ? 'add' : 'less',
				newQty 	= action === 'add' ? baseQty + step : baseQty - step;

			
			$input.val(newQty).trigger('change');

		}

		changeInputQty(e){

			Notice.hideNotification();

			var $_this	= this,
 				$input 	= $(e.currentTarget),
				newQty 	= parseFloat( $input.val() ),
				step 	= parseFloat( $input.attr('step') ),
				min 	= parseFloat( $input.attr('min') ),
				max 	= parseFloat( $input.attr('max') ),
				invalid = false,
				message = false;

			//Validation
			
			if( isNaN( newQty )  || newQty < 0 || newQty < min  ){
				invalid = true;
			}
			else if( newQty > max ){
				invalid = true;
				message = osc_params.strings.maxQtyError.replace( '%s%', max );
			}
			else if( !Number.isInteger(newQty/step ) ){
				invalid = true;
				message = osc_params.strings.stepQtyError.replace( '%s%', step );
			}
			
			//Set back to default quantity
			if( invalid ){
				$input.val( this.baseQty );
				if( message ){
					Notice.add( message, 'error' );
					Notice.showNotification();
				}
				return;
			}

			//Update
			$input.val( newQty );

			clearTimeout( this.qtyUpdateDelay );

			this.qtyUpdateDelay = setTimeout(function(){
				$_this.updateItemQty( $input.parents('.osc-product').data('key'), newQty )
			}, osc_params.qtyUpdateDelay );
			
			
		}


		saveScrollPosition(){
			this.bodyPosition = this.$modal.find('.osc-body').length ? this.$modal.find('.osc-body').scrollTop() : false;
		}

		setScrollPosition(){
			if( this.bodyPosition !== false ){
				this.$modal.find( '.osc-body' ).scrollTop( this.bodyPosition );
				this.bodyPosition = false; //reset
			}
		}

		updateItemQty( cart_key, qty ){

			if( !cart_key || qty === undefined ) return;

			this.block();
			
			this.saveScrollPosition();

			var formData = {
				cart_key: cart_key,
				qty: qty
			}

			$.ajax({
				url: get_wcurl( 'osc_update_item_quantity' ),
				type: 'POST',
				context: this,
				data: this.setAjaxData(formData),
				success: function(response){
					this.updateFragments( response );
					$(document.body).trigger( 'osc_quantity_updated', [response] );
					$(document.body).trigger( 'osc_cart_updated', [response] );
					this.setScrollPosition();
					this.unblock();
				}

			})
		}


		closeCartOnClick(e){
			e.preventDefault();
			if( drawer.isOpen() ){
				drawer.toggle('hide');
				setTimeout( function(){
					cart.toggle( 'hide' )
				}, 500 );
			}
			else{
				cart.toggle('hide');
			}

		}


		saveQtyFocus(e){
			this.baseQty = $(e.currentTarget).val();
		}


		onPageShow(e){
			if ( e.originalEvent.persisted ) {
				this.refreshMyFragments();
				$( document.body ).trigger( 'wc_fragment_refresh' );
			}
		}

		deleteIconClick(e){
			this.updateItemQty( $( e.currentTarget ).parents('.osc-product').data('key'), 0 );
		}

		undoItem(e){

			var $undo 		= $(e.currentTarget),
				formData 	= {
					cart_key: $undo.data('key')
				}

			this.block();

			$.ajax({
				url: get_wcurl('osc_undo_item'),
				type: 'POST',
				context: this,
				data: this.setAjaxData(formData),
				success: function(response){
					this.updateFragments( response );
					$(document.body).trigger( 'osc_item_restored', [response] );
					$(document.body).trigger( 'osc_cart_updated', [response] );
					this.unblock();
				}

			})
		}

		storeProgressBarWidth( e ){

			var $bars 	= $(document.body).find( '.osc-bar-cont' ),
				self 	= this;

			if( !$bars.length ) return;

			$.each( $bars, function( index, el ){

				var $bar 	= $(el),
					$filled = $bar.find('.osc-bar-filled'),
					id 		= $bar.attr('id');

				self.barsWidth[ id ] 		= $filled.prop('style').width;
				self.barsData[ id ] 		= $bar.data('bardata')

			} );

			
		}

		onCartUpdate(){
			super.onCartUpdate();
			this.cartLoaded = true;
			this.initAnimateProgressBar = true;
			if( this.isOpen() ){
				this.animateProgressBar();
			}
			this.initSlider();
			this.toggleBasket();
			initMasonryLayout( ['cart','suggested'] );
		}


		
		toggleBasket(){

			var $basket 	= $('.osc-basket'),
				show 		= osc_params.showBasket,
				hasProducts = this.$modal.find('.osc-product').length;

			if( show === "always_show" ){
				$basket.show();	
			}
			else if( show === "hide_empty" ){
				if( hasProducts ){
					$basket.show();
				}
				else{
					$basket.hide();
				}
			}
			else{
				$basket.hide();
			}

			var $shortcode = $('.osc-sc-cont');

			if( $shortcode.length && osc_params.menuCartHideOnEmpty.length ){

				var shortcodeEls = osc_params.shortcodeEls;

				$.each( osc_params.menuCartHideOnEmpty, function( index, val ){

					if( shortcodeEls[val] ){
						if( hasProducts ){
							$(shortcodeEls[val]).show();
						}
						else{
							$(shortcodeEls[val]).hide();
						}
					}

				})

			}
			
		}

		animateProgressBar(){

			if( isCartPage || isCheckoutPage || !this.initAnimateProgressBar ) return;

			var $bars  	= $(document.body).find( '.osc-bar-cont' ),
				self 	= this;

			if( !$bars.length ) return;

			$.each( $bars, function( index, el ){

				var $bar 	= $(el),
					$filled = $bar.find('.osc-bar-filled'),
					id 		= $bar.attr('id');

				if( !self.barsWidth[ id ] ) return true;

				var newWidth = $filled.prop('style').width;

				$filled
					.width( self.barsWidth[ id ] )
					.animate({ width: newWidth }, 400, 'linear');

			} );

			this.checkPointAchievedAnimate();

			this.initAnimateProgressBar = false;
		}

		checkPointAchievedAnimate(){

			var $bars 	= $(document.body).find( '.osc-bar-cont' ),
				self 	= this;

			if( !this.barsData || !$bars.length ) return;


			$.each( $bars, function( index, el ){

				var $bar 		= $(el),
					$filled 	= $bar.find('.osc-bar-filled'),
					id 			= $bar.attr('id'),
					barData 	= $bar.data('bardata'),
					pastBarData = self.barsData[ id ];

				if( !pastBarData ) return true;

				var pastPointsReached 	= [],
					newPointsReached 	= [],
					allPointsReached 	= false;

				$.each( pastBarData.points, function( point_index, point ){
					if( point.reached == true ){
						pastPointsReached.push('id_'+point_index);
					}
				} )

				$.each( barData.points, function( point_index, point ){
					if( point.reached == true && !pastPointsReached.includes('id_'+point_index)  ){
						newPointsReached.push('id_'+point_index);
					}

					if( point.reached == true && (point_index + 1) === barData.points.length  ){
						allPointsReached = true;
					}
				} )



				if( newPointsReached.length ){
					setTimeout( function(){
						Celebrate.Celebrate( $bar, allPointsReached && osc_params.bar.fullCelebration !== 'none' ? osc_params.bar.fullCelebration : osc_params.bar.singleCelebration );
					}, 200 );
				}

				self.barsData[id] = barData;


			})

			
		}


		emptyCart(){

			this.block();

			$.ajax({
				url: get_wcurl( 'osc_empty_cart' ),
				type: 'POST',
				context: this,
				data: {},
			    success: function(response){
			    	
					this.updateFragments( response );
					// Trigger event.
					$( document.body ).trigger( 'osc_cart_emptied' );
					$(document.body).trigger( 'osc_cart_updated', [response] );
			    },
			    complete: function(){
			    	this.unblock();
			    }
			})
		}


		initSlider(){

			if( typeof $.fn.lightSlider !== 'function' || osc_params.spSlide.enable !== 'yes' ) return;

			$('ul.osc-sp-slider').each( function( index, el ){
				var $el = $(el);

				if( $(this).parents('.osc-drawer').length ) return;

				$el.lightSlider(osc_params.spSlide);

			} );
			
		}

	}


	class Drawer extends Container{

		constructor( $modal ){

			super( $modal, 'drawer' );

			this.setHeaderHeight();

			this.eventHandlers();

		}

		eventHandlers(){

			super.eventHandlers();

			$(document.body).on( 'osc_cart_toggled', this.drawOutOnCartOpen.bind(this) );
			$(document.body).on( 'click', '.osc-toggle-drawer', this.toggleDrawer.bind(this) );
			$(document.body).on( 'click', '.oscdh-close', this.close.bind(this) );
			//$(document.body).on( 'wc_fragments_loaded', this.onCartUpdate.bind(this) );
		}

		onCartUpdate(){
			super.onCartUpdate();
			this.setHeaderHeight();
			setTimeout(function(){
				drawer.toggleOnContentBasis();
			}, 0);
			
		}


		toggleOnContentBasis(){

			var hasContent = !this.isDrawerEmpty();

			if( this.isOpen() ){
				if( !hasContent ){
					this.toggle('hide');
					this.$modal.find('.osc-dtg-icon').addClass('osc-disabled');
					this.emptyClosed = true;
				}
			}
			else{
				if( hasContent && cart.isOpen() && this.emptyClosed ){
					this.toggle('show');
					this.$modal.find('.osc-dtg-icon').removeClass('osc-disabled');
					this.emptyClosed = false;
				}
			}
		}

		setHeaderHeight(){
			var $cartHeader = $('.osch-top');
			if( !$cartHeader.length ) return;

			var cartHeaderHeight = $cartHeader.height();

			this.$modal.closest('.osc-markup').find('.osc-drawer-header, .osc-sl-heading').each(function( index, el ){

				var $el = $(el);

				if( $el.height() < $cartHeader.height() ){
					$el.height( cartHeaderHeight );
				}
			})
	
			
		}

		isDrawerEmpty(){
			return !this.$modal.find('.osc-dr-content').length;
		}


		toggleDrawer(){
			this.toggle();
		}


		drawOutOnCartOpen(e,type){
			
			if( !cart.isOpen() || this.isDrawerEmpty() ) return;

			setTimeout( function(){
				drawer.toggle('show');
				initMasonryLayout('suggested');
			}, osc_params.drawerWait );

		}


		close(e){
			this.toggle('hide');
		}

		getDataType(){
			return this.$modal.find('.osc-dr-content').data('drawer');
		}


	}

	

	class Slider extends Container{

		constructor( $modal ){

			super( $modal, 'slider' );

			this.eventHandlers();

			this.shipping = osc_params.shippingEnabled ? Shipping.init( this ) : null;

		}

		eventHandlers(){

			super.eventHandlers();


			$( document.body ).on( 'click', '.osc-toggle-slider', this.triggerSlider.bind(this) );
			$( document.body ).on( 'osc_cart_toggled', this.closeSliderOnCartClose.bind(this) );

			if( osc_params.sliderAutoClose ){
				$( document.body ).on( 'osc_coupon_applied osc_shipping_calculated updated_shipping_method osc_coupon_removed osc_moved_from_save_list added_to_cart', this.closeSlider.bind(this) );
			}

			$(document.body).on( 'submit', 'form.osc-sl-apply-coupon', this.submitCouponForm.bind(this) );
			$(document.body).on( 'click', '.osc-coupon-apply-btn', this.applyCouponFromBtn.bind(this) );
			$(document.body).on( 'click', '.osc-remove-coupon', this.removeCoupon.bind(this) );
			$(document.body).on( 'click', '.osc-savl-del', this.deleteSavedForLaterItem.bind(this) );
			$(document.body).on( 'click', '.osc-savl-atc', this.moveSavedForLaterItemToCart.bind(this) );

			$( document.body ).on( 'click', '.osc-toggle-slider[data-slider="quickview"]', this.loadQuickview.bind(this) );

		}


		loadQuickview(e){

			e.preventDefault();

			this.toggle('show');

			var $toggler 		= $(e.currentTarget),
				formData 		= {};

			if( $toggler.data('cart_key') ){
				formData['cart_key'] = $toggler.data('cart_key');
			}
			else if( $toggler.data('product_id') ){
				formData['product_id'] = $toggler.data('product_id');
			}
			else{
				return;
			}

			var $sliderContent = $('.osc-sl-quickview .osc-sl-body');

			$sliderContent.html('');

			this.block();
			
			$.ajax({
				url: get_wcurl( 'osc_load_quickview' ),
				type: 'POST',
				context: this,
				data: this.setAjaxData(formData),
				success: function(response){

					$sliderContent.html(response);

					QuickView.load();

					$(document.body).trigger( 'osc_quickview_loaded', [response, formData] );

					this.unblock();

				}

			})
		}


		moveSavedForLaterItemToCart(e){

			var $item 			= $(e.currentTarget).closest('.osc-savl-product'),
				cartKey 		= $item.data('ckey'),
				formData 		= {
				cart_key: cartKey,
			}

			this.block();
			
			$.ajax({
				url: get_wcurl( 'osc_move_save_for_later_item' ),
				type: 'POST',
				context: this,
				data: this.setAjaxData(formData),
				success: function(response){

					this.updateFragments( response );

					$(document.body).trigger( 'osc_moved_from_save_list', [response, cartKey] );

					this.unblock();

				}

			})
		}

		deleteSavedForLaterItem(e){

			var $item 			= $(e.currentTarget).closest('.osc-savl-product'),
				cartKey 		= $item.data('ckey'),
				formData 		= {
				cart_key: cartKey,
			}

			this.block();
			
			$.ajax({
				url: get_wcurl( 'osc_delete_save_for_later_item' ),
				type: 'POST',
				context: this,
				data: this.setAjaxData(formData, 'slider' ),
				success: function(response){

					this.updateFragments( response );

					$(document.body).trigger( 'osc_delete_from_save_list', [response, cartKey] );

					this.unblock();

				}

			})
		}


		removeCoupon(e){

			e.preventDefault();

			var $removeEl 	= $(e.currentTarget),
				coupon 		= $removeEl.data('code'),
				formData 	= {
					coupon: coupon
				};

			this.block();	

			$.ajax( {
				url: get_wcurl( 'osc_remove_coupon' ),
				type: 'POST',
				context: this,
				data: this.setAjaxData( formData, cart.$modal.find( $removeEl ).length ? 'cart' : 'slider' ),
				success: function( response ) {
					this.updateFragments(response);
				},
				complete: function() {
					this.unblock();
					this.updateCartCheckoutPage();
					$( document.body ).trigger( 'osc_coupon_removed' );
				}
			} );
		}

		onCartUpdate(){
			super.onCartUpdate();
			this.toggleContent();
		}

		closeSlider(){
			this.toggle('hide');
		}


		applyCouponFromBtn(e){
			this.applyCoupon( $(e.currentTarget).val() );
		}

		submitCouponForm(e){

			e.preventDefault();

			var $form = $(e.currentTarget);

			this.applyCoupon( $form.find('input[name="osc-slcf-input"]').val() );

		}


		closeSliderOnCartClose(e){

			var $this = $(e.currentTarget); 

			if( !cart.$modal.hasClass('osc-cart-active') ){
				this.toggle('hide');
			}

		}


		triggerSlider(e){

			var $toggler 	= $(e.currentTarget),
 				slider 		= $toggler.data('slider');

			if( slider === 'shipping' && isCheckoutPage ){
				Notice.add( osc_params.strings.calculateCheckout, 'error' );
				Notice.showNotification();
				return;
			}


			this.$modal.attr( 'data-slider', slider );
			
			this.toggle();

			this.toggleContent();
		}


		toggleContent(){

			var activeSlider = '';

			$('.osc-sl-content').hide();
			
			var activeSlider 	= this.$modal.attr('data-slider'),
				$toggleEl 		= $('.osc-sl-content[data-slider="'+activeSlider+'"]');
	
			if( $toggleEl.length ) $toggleEl.show();

			if( activeSlider === 'savelater' && this.isOpen() ){
				initMasonryLayout('saveLater');
			}

			$( document.body ).trigger( 'osc_slider_data_toggled', [activeSlider] );
		}


		applyCoupon( coupon ){

			if( !coupon ){
				Notice.add( osc_params.strings.couponEmpty, 'error' );
				Notice.showNotification();
				return;
			}

			this.block();

			var formData = {
				'coupon': coupon,
			}

			$.ajax( {
				url: get_wcurl('osc_apply_coupon'),
				type: 'POST',
				context: this,
				data: this.setAjaxData( formData ),
				success: function( response ) {
					this.updateFragments(response);
				},
				complete: function() {
					this.unblock();
					this.updateCartCheckoutPage();
					$( document.body ).trigger( 'osc_coupon_applied' );
				}
			} );

		}

	}

	

	var Shipping = {

		init: function( slider ){
			slider.$modal.on( 'change', 'input.osc-shipping-method', this.shippingMethodChange );
			slider.$modal.on( 'submit', 'form.woocommerce-shipping-calculator', this.shippingCalculatorSubmit );
			slider.$modal.on( 'click', '.shipping-calculator-button', this.toggleCalculator );
			$(document.body).on( 'wc_fragments_loaded osc_slider_data_toggled', this.initSelect2 );
		},

		toggleCalculator: function(e){

			e.preventDefault();
			e.stopImmediatePropagation();

			$(this).siblings('.shipping-calculator-form').slideToggle();
			$( document.body ).trigger( 'country_to_state_changed' );
		},

		shippingCalculatorSubmit: function(e){

			e.preventDefault();
			e.stopImmediatePropagation();

			var $form = $(this);

			slider.block();

			// Provide the submit button value because wc-form-handler expects it.
			$( '<input />' )
				.attr( 'type', 'hidden' )
				.attr( 'name', 'calc_shipping' )
				.attr( 'value', 'x' )
				.appendTo( $form );

			var formData = $form.serialize();

			// Make call to actual form post URL.
			$.ajax( {
				url: get_wcurl( 'osc_calculate_shipping' ),
				type: 'POST',
				context: this,
				data: slider.setAjaxData(formData),
				success: function( response ) {
					slider.updateFragments(response);
				},
				complete: function() {
					slider.unblock();
					slider.updateCartCheckoutPage();
					$( document.body ).trigger( 'osc_shipping_calculated' );
				}
			} );

		},

		shippingMethodChange: function(e){

			e.preventDefault();
			e.stopImmediatePropagation();

			var shipping_methods = {};

			slider.block();

			$( 'select.shipping_method, :input[name^=osc-shipping_method][type=radio]:checked, :input[name^=shipping_method][type=hidden]' ).each( function() {
				shipping_methods[ $( this ).data( 'index' ) ] = $( this ).val();
			} );

			var formData = {
				shipping_method: shipping_methods,
			}

			$.ajax( {
				type:     'POST',
				url:      get_wcurl( 'osc_update_shipping_method' ),
				data:     slider.setAjaxData( formData ),
				success:  function( response ) {
					slider.updateFragments(response);
				},
				complete: function() {
					slider.unblock();
					slider.updateCartCheckoutPage();
					$( document.body ).trigger( 'updated_shipping_method' );
				}
			} );

		},

		initSelect2: function(e){
			$( document.body ).trigger( 'country_to_state_changed' );
		},
	}


	var cart 	= new Cart( $('.osc-modal') ),
		slider 	= new Slider( $('.osc-slider-modal') );
		drawer 	= new Drawer( $('.osc-drawer-modal') );


	var AnimateCard = {

		type: osc_params.cardAnimate.type,
		duration: osc_params.cardAnimate.duration,

		init: function(){

			var onEvent = osc_params.cardAnimate.event === 'back_hover' ? 'mouseenter' : 'click';
		
			$('body').on( onEvent, '.osc-has-back', this.animate );
			$('body').on( 'mouseleave', '.osc-has-back', this.reverseAnimate );

		},
		animate: function(e){

			if( e.target.classList.contains('osc-smr-del') ) return;

			var $img = $(this).find('.osc-img-col');

			if( !$img.hasClass('osc-caniming') ){
				e.preventDefault();
			}
			else{
				return;
			}

			$img.attr('data-exclasses', $img.attr('class') );

			$img.removeClass()
			$img.addClass($img.attr('data-exclasses'));

			$img.addClass( 'osc-caniming' + ' ' + AnimateCard.type );

		},
		reverseAnimate: function(){

			var $img = $(this).find('.osc-img-col');

			if( !$img.hasClass( 'osc-caniming' ) ) return;

			$img.addClass(AnimateCard.type+'Return');

			AnimateCard.clear = setTimeout(function(){
				$img.removeClass().addClass( $img.attr('data-exclasses') );
			}, AnimateCard.duration * 1000);

		}
	}

	if( osc_params.cardAnimate.enable === "yes" ){
		AnimateCard.init();
	}



	var Celebrate = {

		canvasIndex: 0,

		myconfetti: '',

		Celebrate: async function( $bar, celebrationName ){

			if (typeof Celebrate[celebrationName] !== 'function') return;

			const index = Celebrate.canvasIndex++;

			const myconfetti = await Celebrate.CreateCanvas(index);

			const barPos = Celebrate.BarPosition( $bar );

			Celebrate[celebrationName](myconfetti, barPos);

		},

		CreateCanvas: async function(index){

			const canvasID = 'osc_fw_canvas_' + index;

		    // Append a fresh canvas for this celebration
		    $('.osc-container').append('<canvas id="'+canvasID+'"></canvas>').show();

		    const canvas = document.getElementById(canvasID);

		    // Create a separate confetti instance for this canvas
		    return await confetti.create(canvas, { resize: true });
			
		},

		BarPosition: function( $bar ){
			
			var windowHeight 	= $(window).height(),
				$barPole 		= $bar.find('.osc-bar');

			var barPosition 	= $barPole.offset().top - $(window).scrollTop();

			Celebrate.barPosition = barPosition/windowHeight;
			
			return Celebrate.barPosition;
			
		},

		Fireworks: function( myconfetti, barPos ){
		    const duration = 3 * 1000,
		          animationEnd = Date.now() + duration,
		          defaults = { startVelocity: 30, spread: 100, ticks: 60, zIndex: 0 };

		    function randomInRange(min, max) {
		        return Math.random() * (max - min) + min;
		    }

		    const interval = setInterval(function(){
		        const timeLeft = animationEnd - Date.now();
		        if (timeLeft <= 0) return clearInterval(interval);

		        const particleCount = 50 * (timeLeft / duration);

		        myconfetti(Object.assign({}, defaults, {
		            particleCount,
		            origin: { x: randomInRange(0.1, 0.3), y: barPos }
		        }));

		        myconfetti(Object.assign({}, defaults, {
		            particleCount,
		            origin: { x: randomInRange(0.7, 0.9), y: barPos }
		        }));

		    }, 250);
		},

		Stars: function( myconfetti, barPos ){

			const defaults = {
				origin: { x: 0.5, y: barPos },
				spread: 360,
				ticks: 50,
				gravity: 0,
				decay: 0.94,
				startVelocity: 30,
				shapes: ["star"],
				colors: ["FFE400", "FFBD00", "E89400", "FFCA6C", "FDFFB8"],
			};

			function shoot() {
				myconfetti({
					...defaults,
					particleCount: 40,
					scalar: 1,
					shapes: ["star"],
				});

				myconfetti({
					...defaults,
					particleCount: 10,
					scalar: 0.75,
					shapes: ["circle"],
				});
			}

			setTimeout(shoot, 0);
			setTimeout(shoot, 100);
			setTimeout(shoot, 200);
		},

		SchoolPride: function( myconfetti, barPos ){

			const end = Date.now() + 1.5 * 1000;

			// go Buckeyes!
			const colors = ["#bb0000", "#ffffff"];

			(function frame() {
				myconfetti({
					particleCount: 2,
					angle: 60,
					spread: 55,
					origin: { x: 0, y: barPos },
					colors: colors,
				});

				myconfetti({
					particleCount: 2,
					angle: 120,
					spread: 55,
					origin: { x: 1, y: barPos },
					colors: colors,
				});

				if (Date.now() < end) {
					requestAnimationFrame(frame);
				}
			})();
		},

		RealisticLook: function( myconfetti, barPos ){

			const count = 1000,

			defaults = {
				origin: { y: barPos },
			};

			function fire(particleRatio, opts) {
				myconfetti(
					Object.assign({}, defaults, opts, {
						particleCount: Math.floor(count * particleRatio),
					})
				);
			}

			fire(0.25, {
				spread: 26,
				startVelocity: 55,
			});

			fire(0.2, {
				spread: 60,
			});

			fire(0.35, {
				spread: 100,
				decay: 0.91,
				scalar: 0.8,
			});

			fire(0.1, {
				spread: 120,
				startVelocity: 25,
				decay: 0.92,
				scalar: 1.2,
			});

			fire(0.1, {
				spread: 120,
				startVelocity: 45,
			});
		},

		BasicCannon: function( myconfetti, barPos ){
			myconfetti({
			  particleCount: 400,
			  spread: 70,
			  origin: { y: barPos },
			});
		}

	}



	var scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;

	$('<style>')
	  .prop('type', 'text/css')
	  .html('body.osc-cart-active { padding-right: ' + scrollBarWidth + 'px; }')
	  .appendTo('head');
	})

