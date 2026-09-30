jQuery(document).ready(function($){

	var isRTL = $('body.rtl').length;

	var AnimateCard = {

		type: function(){
			return SideCart.sy('scbp-card-anim-type');
		},

		duration: function(){
			return SideCart.sy('scbp-card-anim-time');
		},

		init: function(){

			var onEvent = SideCart.sy('scbp-card-visible') === 'back_hover' ? 'mouseenter' : 'click';

			$('.osc-has-back').off();
		
			$('.osc-has-back').on( onEvent, this.animate );
			$('.osc-has-back').on( 'mouseleave', this.reverseAnimate );

			if( SideCart.sy('scb-playout') === 'cards' ){
				this.initMasonryLayout();
			}

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

			$img.addClass( 'osc-caniming' + ' ' + AnimateCard.type() );

		},
		reverseAnimate: function(){

			var $img = $(this).find('.osc-img-col');

			if( !$img.hasClass( 'osc-caniming' ) ) return;

			$img.addClass( AnimateCard.type() + 'Return' );

			AnimateCard.clear = setTimeout(function(){
				$img.removeClass().addClass( $img.attr('data-exclasses') );
			}, AnimateCard.duration() * 1000 );

		},

		initMasonryLayout(){
			$('.osc-products.osc-pattern-card').masonry({
				// options
				itemSelector: '.osc-product-cont',
				columnWidth: '.osc-product-cont', /* Each column takes 50% */
				percentPosition: true
			});
		}
	}

	var Customizer = {

		$form: '',
		$styleTag: $('.osc-fw-as-preview-style'),
		previewTemplate: '',
		formValues: {},
		getPreviewCSS: function() {},
		getPreviewHTMLData: function() {},
		pageLoading: true,
		buildTimout: null,

		init: function(){
			this.initColorPicker();
			this.initSortable();
			this.initTemplates();
			this.events();
			this.build();
		},

		events: function(){
			$( Customizer.$form ).on('change', this.onFormChange );

			Customizer.$form.find('.wp-editor-area').each(function() {

			    var editor = tinymce.get(this.id);

			    if (!editor) return;

			    editor.on('change undo redo SetContent', function() {
			    	editor.save();
			        Customizer.build();
			    });

			});

		},

		initTemplates: function(){
			this.previewTemplate = wp.template('osc-fw-as-preview');
		},

		initColorPicker: function(){
			$('.osc-fw-as-color-input').wpColorPicker({
				change: function(event, ui){
					$(event.target).val(ui.color.toString()).trigger('change')
				}
			});
		},

		initSortable: function(){
			$('.osc-fw-as-sortable-list').sortable({
				update: function(){
					Customizer.build();
				}
			});
		},

		onFormChange: function(e){
			Customizer.build();
		},


		setFormValues: function(){
			//var values 		= this.$form.serializeArray();
			//this.formValues = this.objectifyForm(values);
			this.formValues = this.$form.serializeJSON();
		},

		build: function(){
			if( this.pageLoading ) return; // prevent multiple building event on page load due to 'change' event

			clearTimeout( Customizer.buildTimout );

			Customizer.buildTimout = setTimeout( function(){
				Customizer.setFormValues();
				Customizer.buildHTML();
				Customizer.buildCSS();
				AnimateCard.init();
			}, 200 );
			
		},


		buildCSS: function(){

			var css = '';

			$.each( Customizer.getPreviewCSS(), function( selector, properties ){
				css += selector+'{';
				$.each( properties, function(property, value){
					css += property+': '+value+';';
				} );
				css += '}';
			} );

			css += SideCart.av('m-custom-css');

			Customizer.$styleTag.html('<style>'+css+'</style>')	
		},

		buildHTML: function(){
			$('.osc-fw-as-preview').html(Customizer.previewTemplate(Customizer.getPreviewHTMLData()));

		},

		objectifyForm(inp){

			var rObject = {};

			for (var i = 0; i < inp.length; i++){
				if(inp[i]['name'].substr(inp[i]['name'].length - 2) == "[]"){
					var tmp = inp[i]['name'].substr(0, inp[i]['name'].length-2);
					if(Array.isArray(rObject[tmp])){
						rObject[tmp].push(inp[i]['value']);
					} else{
						rObject[tmp] = [];
						rObject[tmp].push(inp[i]['value']);
					}
				} else{
					rObject[inp[i]['name']] = inp[i]['value'];
				}
			}
			return rObject;
		}
	}


	var SideCart = {

		settingsInPreview: ['osc-gl-options[scb-show][]', 'osc-gl-options[sch-show][]', 'osc-gl-options[scf-show][]', 'osc-sy-options[scf-button-pos][]'],
		previewSettingsRecorded: false,

		init: function(){
			this.initCustomizer();
			this.events();
			this.toggle('show');
		},


		initCustomizer: function(){
			Customizer.$form 		=  $('form.osc-fw-as-form');
			Customizer.getPreviewCSS = this.getPreviewCSS;
			Customizer.getPreviewHTMLData = this.getPreviewHTMLData;
			Customizer.init()
		},

		events: function(){
			$(document.body).on( 'click', '.osc-basket', this.toggle );
			$(document.body).on( 'click', '.osch-close', this.toggle );
		},

		sy: function( key, unit = '' ){
			var value = this.option( 'osc-sy-options', key );
			return unit ? value + unit : value;
		},

		gl: function( key ){
			return this.option( 'osc-gl-options', key );
		},

		av: function( key ){
			return this.option( 'osc-av-options', key );
		},

		option: function( option, key ){
			if( !this.previewSettingsRecorded ){
				this.settingsInPreview.push( option+'['+key+']' )
			}
			return Customizer.formValues[option][key];
		},


		getPreviewCSS: function(){
			return SideCart.setPreviewCSS();
		},

		setPreviewCSS: function(){

			var basketPosition 	= this.sy('sck-count-pos'),
				openFrom 		= isRTL ? 'left' : 'right';

			var basket = {
				[this.sy('sck-position')]: 	this.sy('sck-offset','px'),	
				[openFrom]: 					this.sy('sck-hoffset', 'px'),									
				'background-color': 		this.sy('sck-basket-bg'),
				'color': 					this.sy('sck-basket-color'),
				'box-shadow': 				this.sy('sck-basket-sh'),
				'border-radius': 			this.sy('sck-shape') === 'round' ? '50%' : '14px',
				'width': 					this.sy('sck-bk-size','px'),
				'height':  					this.sy('sck-bk-size','px'),
			};

			var basketActive = {
				[openFrom]: this.sy('scm-width', 'px')
			}

			var basketIcon = {
				'font-size': this.sy('sck-size','px')
			}

			var basketCount = {
				'display': 	 		this.sy('sck-show-count') === 'yes' ? 'block' : 'none',
				'background-color': this.sy('sck-count-bg'),
				'color': 			this.sy('sck-count-color'),
				[basketPosition === 'top_right' || basketPosition === 'top_left' ? 'top' : 'bottom']: '-12px',
				[basketPosition === 'top_right' || basketPosition === 'bottom_right' ? 'right' : 'left']: '-12px'
			}

			var container = {
				'max-width': 				this.sy('scm-width','px'),
				'right': 					'-'+this.sy('scm-width','px'),
				'font-family': 				this.sy('scm-font'),
				[this.sy('sck-position')]: 	'0'
			}

			if( this.sy('scm-height') === 'full' ){
				container['top'] = '0';
				container['bottom'] = '0';
			}
			else{
				container['max-height'] = '100vh';
			}


			var header = {
				'background-color': this.sy('sch-bgcolor'),
				'color': 			this.sy('sch-txtcolor'),
				'border-bottom': 	this.sy('sch-border'),
				'padding': 			this.sy('sch-padding')
			}

			var headerTxt = {
				'font-size': this.sy('sch-head-fsize','px')
			}

			var body = {
				'background-color': this.sy('scb-bgcolor'),
			}

			var bodyText = {
				'font-size': 	this.sy('scb-fsize','px'),
				'color': 		this.sy('scb-txtcolor')
			}

			var footer = {
				'padding': 				this.sy('scf-padding'),
				'background-color': 	this.sy('scf-bgcolor'),
				'color': 				this.sy('scf-txtcolor'),
				'box-shadow': 			this.sy('scf-shadow'),
			}

			var footerFSize = {
				'font-size': 			this.sy('scf-fsize', 'px'),
			}

			var product =  {
				'padding': 				this.sy('scbp-padding'),
				'background-color': 	this.sy('scbp-bgcolor'),
				'margin':  				this.sy('scbp-margin'),
				'border-radius': 		this.sy('scbp-bradius', 'px'),
				'box-shadow': 			this.sy('scbp-shadow'),
			}

			var productImgCol = {
				'width': this.sy('scbp-imgw', '%')
			}


			if( this.sy('scf-stick') !== 'yes' ){
				footer['flex-grow'] 	= 1;
				body['flex-grow'] 		= 0;
				body['overflow'] 		= 'unset';
				container['overflow'] 	= 'auto';
			}


			var cardStyle = {
				container: {
					'padding': 	this.sy('scbp-card-padding'),
				},
				image: {
					'max-width': this.sy('scbp-card-imgw', '%'),
					'height': this.sy('scbp-card-imgh', 'px')
				},
				productCont: {
					'width': 100/parseInt( this.sy('scbp-card-count') ) + '%',
				},
				product: {
					'border': this.sy('scbp-card-border'),
					'box-shadow': this.sy('scbp-card-shadow'),
				},



				front: {
					'background-color': this.sy('scbp-card-front-color')
				},
				cardAndFront: {
					'border-bottom-left-radius': this.sy('scbp-card-radius-btm', 'px'),
					'border-bottom-right-radius': this.sy('scbp-card-radius-btm', 'px')
				},
				imgAndImgCol: {
					'border-top-left-radius': this.sy('scbp-card-radius-top', 'px'),
					'border-top-right-radius': this.sy('scbp-card-radius-top', 'px'),
				},
				cardText: {
					'font-size': 	this.sy('scb-fsize','px'),
				},
				cardFront: {
					'color': 		this.sy('scbp-card-txtcolor'),
				},
				cardBack: {
					'color': 		this.sy('scbp-card-backtxt-color'),
				},
				back: {
					'background-color': this.sy('scbp-card-back-color')
				},
				animation: {
					'animation-duration': this.sy('scbp-card-anim-time', 's')
				}

			}

			if( parseInt(this.sy('scbp-card-imgw')) < 100 ){
				cardStyle.imageCol = {
					'background-color': this.sy('scbp-card-img-color')
				}
			}


			var cardSelectors = {
				'.osc-product-cont': cardStyle.container,
				'.osc-pattern-card .osc-img-col img': cardStyle.image,
				'.osc-pattern-card .osc-product-cont': cardStyle.productCont,
				'.osc-pattern-card .osc-product': cardStyle.product,
				'.osc-pattern-card .osc-img-col': cardStyle.imageCol,
				'.osc-sm-front, .osc-card-actionbar > *': cardStyle.front,
				'.osc-pattern-card, .osc-sm-front': cardStyle.cardAndFront,
				'.osc-pattern-card, .osc-img-col img, .osc-img-col, .osc-sm-back-cont': cardStyle.imgAndImgCol,
				'.osc-sm-back': cardStyle.back,
				'.osc-pattern-card, .osc-pattern-card a, .osc-pattern-card .amount': cardStyle.cardText,
				'.osc-body .osc-sm-front, .osc-body .osc-sm-front a, .osc-body .osc-sm-front .amount, .osc-card-actionbar': cardStyle.cardFront,
				'.osc-sm-back, .osc-sm-back a, .osc-sm-back .amount': cardStyle.cardBack,
				'.magictime': cardStyle.animation
			}

			if( osc_admin_params.isMobile === 'yes' && this.sy('scb-playout') === 'cards' && this.sy('scbp-card-visible') === 'back_hover' ){
				cardSelectors['.osc-img-col a'] = {
					'pointer-events': 'none'
				}
			}

			if( this.sy('scm-info-loc') === 'body_end_stick' ){
				body['display'] = 'flex';
				body['flex-direction'] = 'column';
			}


			var selectors = {
				'.osc-basket': basket,
				'.osc-cart-active .osc-basket': basketActive,
				'.osc-bki': basketIcon,
				'.osc-items-count': basketCount,
				'.osc-container,.osc-slider': container,
				'.osc-header': header,
				'.osch-text': headerTxt,
				'.osc-body': body,
				'.osc-products:not(.osc-pattern-card), .osc-products:not(.osc-pattern-card) span.amount, .osc-products:not(.osc-pattern-card) a': bodyText,
				'.osc-footer': footer,
				'.osc-footer, .osc-footer a, .osc-footer .amount': footerFSize,
				'.osc-products:not(.osc-pattern-card) .osc-product': product,
				'.osc-products:not(.osc-pattern-card) .osc-img-col': productImgCol,
				'.osch-items-count, .osch-save-count': {
					'background-color': this.sy('sck-count-bg'),
					'color': 			this.sy('sck-count-color')
				},
				'span.osch-icon': {
					'font-size': this.sy('sch-close-fsize','px')
				},
				'.osc-sm-sales': {
					'background-color': this.sy('scbp-sales-bgcolor'),
					'color': 			this.sy('scbp-sales-txtcolor'),
					'border': 			this.sy('scbp-sales-border')
				},
				'.osc-save, .osc-smr-del': {
					'font-size': this.sy('scb-icon-size','px')
				}
			}

			if( this.sy('scm-info-loc') === 'body_end_stick' ){
				selectors['.osc-body .osc-info-cont'] = {
					'margin-top': 'auto',
					'margin-bottom': '5px'
				}
				
			}

			const buttonThemeSelectors = {
				'osc-sy-options[scm-btntheme-cart]': 'a.osc-ft-btn-cart',
				'osc-sy-options[scm-btntheme-checkout]': 'a.osc-ft-btn-checkout',
				'osc-sy-options[scm-btntheme-continue]': 'a.osc-ft-btn-continue',
				'osc-sy-options[scm-btntheme-tooltip]': '.osc-tooltip',
			}


			
			var $buttonStyleTag = $('.osc-button-theme-styles'),
				$buttonStyleTag = $buttonStyleTag.length ? $buttonStyleTag : $('<div class="osc-button-theme-styles"></div>').insertAfter(Customizer.$styleTag);


			if( osc_fw_admin_params.BtnTheme && ( !$('input[name="osc-sy-options[scf-btn-newlayout]"]').length ||  this.sy('scf-btn-newlayout') === 'yes' ) ){

				var buttonCSS = '';

				$.each( buttonThemeSelectors, function( settingID, classSelector ){

					const themeID 	= $('select[name="'+settingID+'"]').val();

					const themeValues = themeID ? osc_fw_admin_params.BtnTheme.getThemes( themeID ) : null;

					 if( !themeValues ) return true;

					buttonCSS += osc_fw_admin_params.BtnTheme.getCSS( themeValues, classSelector );

				} );

					
				$buttonStyleTag.html('<style>'+buttonCSS+'</style>');
			
			}
			else{

				$buttonStyleTag.html('');


				var footerBtn = {
					'padding': 				this.sy('scf-btn-padding'),
					'background-color': 	this.sy('scf-btn-bgcolor'),
					'color': 				this.sy('scf-btn-txtcolor'),
					'border': 				this.sy('scf-btn-border'),
				}

				var footerBtnHover = {
					'background-color': 	this.sy('scf-btnhv-bgcolor'),
					'color': 				this.sy('scf-btnhv-txtcolor'),
					'border': 				this.sy('scf-btnhv-border'),
				}

				selectors['.osc-ft-buttons-cont a.osc-ft-btn, .osc-container .osc-btn'] 			= footerBtn;
				selectors['.osc-ft-buttons-cont a.osc-ft-btn:hover, .osc-container .osc-btn:hover'] = footerBtnHover;	

			}

			var gridCols = 'auto';

			if( this.sy('scf-btns-row') === 'three' ){
				gridCols = '1fr 1fr 1fr';
			}
			else if( this.sy('scf-btns-row') === 'two_one' ){
				gridCols = '2fr 2fr';
				selectors['a.osc-ft-btn:nth-child(3)'] = {
					'grid-column': '1/-1'
				}
			}
			else if( this.sy('scf-btns-row') === 'one_two' ){
				gridCols = '2fr 2fr';
				selectors['a.osc-ft-btn:nth-child(1)'] = {
					'grid-column': '1/-1'
				}
			}
			

			selectors['.osc-ft-buttons-cont'] = {
				'grid-template-columns': gridCols
			}


			if( this.sy('scbp-display') === 'stretched' ){
				selectors['.osc-sm-info'] = {
					'flex-grow': '1',
    				'align-self': 'stretch'
				}
				selectors['.osc-sm-left'] = {
					'justify-content': 'space-evenly'
				}
			}
			else{
				selectors['.osc-sum-col'] = {
					'justify-content': this.sy('scbp-display')
				}
			}

			if( this.gl('m-tooltip') !== 'yes' ){
				selectors['.osc-tooltip'] = {
					'display': 'none!important'
				}
			}


			var sideCartwidth = parseInt(this.sy('scm-width')) + 100;

			selectors['.osc-cart-active .osc-fw-as-container'] = {
				'width': 'calc( 100% - '+sideCartwidth+'px )'
			}

			/* Quantity */
			selectors['.osc-qty-box'] = {
				'max-width': this.sy('scbq-width' ,'px')
			}

			selectors['.osc-qty-box.osc-qtb-square'] = {
				'border-color': this.sy('scbq-box-bcolor')
			}


			selectors['input[type="number"].osc-qty'] = {
				'border-color': 		this.sy( 'scbq-input-bcolor' ),
				'background-color': 	this.sy( 'scbq-input-bgcolor' ),
				'color': 				this.sy( 'scbq-input-txtcolor' ),
				'height': 				this.sy( 'scbq-height','px' ),
				'line-height': 			this.sy( 'scbq-height','px' ),
			}

			selectors['input[type="number"].osc-qty, .osc-qtb-square'] = {
				'border-width': this.sy( 'scbq-bsize', 'px' ),
				'border-style': 'solid'
			}

			selectors['.osc-chng'] = {
				'background-color': this.sy( 'scbq-box-bgcolor' ),
				'color': 			this.sy( 'scbq-box-txtcolor' ),
				'width': 			this.sy('scbq-btnsize' ,'px')
			}

			selectors['.osc-qtb-circle .osc-chng'] = {
				'height'		: this.sy('scbq-btnsize' ,'px'),
				'line-height'	: this.sy('scbq-btnsize' ,'px')
			}

			selectors['.osc-body .osc-ft-totals'] = {
				'padding': this.sy( 'scbp-padding' ),
				'margin': this.sy( 'scbp-margin' ),
			}

			selectors['.osc-product dl.variation'] = {
				'display': this.sy('scbp-var-format') === 'one_line' ? 'flex' : 'block'
			}

			selectors = $.extend({}, selectors, cardSelectors);


			if( !this.previewSettingsRecorded ){
				$.each( this.settingsInPreview, function( index, name ){
					var $input = $('[name="'+name+'"]').closest('.osc-fw-as-setting');
					if( !$input.length ) return true;
					$input.addClass( 'osc-fw-as-has-preview' );
				} );
				this.previewSettingsRecorded = true;
			}
		
			return selectors;

		},

		getPreviewHTMLData: function(){
			return SideCart.setPreviewHTMLData();
		},

		setPreviewHTMLData: function(){

			var data = {
				openFrom: this.sy('scm-open-from'),
				basket: {
					show: 		this.sy('sck-enable') !== 'always_hide',
					icon: 		this.sy('sck-basket-icon'),
					countType: 	this.gl('m-bk-count')
				},
				totalsLocation: 				this.sy('scf-totals-loc'),
				header: {
					showBasketIcon: 			this.gl('sch-show').includes('basket'),
					showCloseIcon: 				this.gl('sch-show').includes('close'),
					showSaveLaterIcon: 			this.gl('sch-show').includes('save'),
					closeIcon: 					this.sy('sch-close-icon'),
					heading: 					this.gl('sct-cart-heading'),
					layout: 					this.sy('sch-layout'),

				},
				product: {
					layout: 				this.sy('scb-playout'),
					updateQty: 				false,
					showPImage: 			this.gl('scb-show').includes('product_image'),
					showPname: 				this.gl('scb-show').includes('product_name'),
					showPdel: 				this.gl('scb-show').includes('product_del'),
					showPtotal: 			this.gl('scb-show').includes('product_total'),
					showPmeta: 				this.gl('scb-show').includes('product_meta'),
					showPprice: 			this.gl('scb-show').includes('product_price'),
					showPqty: 				this.gl('scb-show').includes('product_qty'),
					showPriceSavings: 		this.gl('scb-show').includes('product_price_save'),
					showTotalSavings: 		this.gl('scb-show').includes('product_total_save'),
					savingsUnit:  			this.gl('scb-prod-savings'),
					qtyPriceDisplay: 		this.gl('scbp-qpdisplay'),
					deletePosition: 		this.sy('scbp-delpos'),
					deleteText: 			this.gl('sct-delete'),
					deleteType: 			this.sy('scbp-deltype'),
					deleteIcon:  			this.sy('scb-del-icon'),
					priceType:  			this.gl('scb-prod-price'),
					showSalesCount: 		this.gl('scb-show').includes('total_sales'),
					updateQty: 				this.gl('scb-update-qty') === "yes",
					qtyDesign: 				this.sy('scbq-style'),
				},
				card: {
					backShow: {
						name: 	this.sy('scbp-card-back').includes('name'),
						price: 	this.sy('scbp-card-back').includes('price'),
						qty: 	this.sy('scbp-card-back').includes('qty'),
						total: 	this.sy('scbp-card-back').includes('total'),
						meta: 	this.sy('scbp-card-back').includes('meta'),
						link: 	this.sy('scbp-card-back').includes('link'),
					},
					visibility: this.sy('scbp-card-visible'),
					hasBack: this.sy('scbp-card-visible') !== 'all_on_front' && (this.sy('scbp-card-back').length > 1)
				},
				footer: {
					totals: {
						savings: 	this.gl('scf-show').includes('savings'),
						subtotal: 	this.gl('scf-show').includes('subtotal'),
						shipping: 	this.gl('scf-show').includes('shipping'),
						total: 		this.gl('scf-show').includes('total')
					},
					subtotalLabel: 		this.gl('sct-subtotal'),
					savingLabel: 		this.gl('sct-savings'),
					footerTxt: 			this.gl('sct-footer'),
					checkoutTotal: 		this.gl('scf-chkbtntotal-en'),
					buttonsPosition: 	this.sy('scf-button-pos'),
					buttonsText: 		{
						cart: this.gl('sct-ft-cartbtn'),
						checkout: this.gl('sct-ft-chkbtn'),
						continue: this.gl('sct-ft-contbtn')
					}
				},
				saveForLater: {
					enabled: 	this.gl('sl-enable') === "yes",
					icon: 		this.sy('sl-icon'),
					heading: 	this.gl('sct-sl-txt'),
				},
				informationBoxLocation: this.sy('scm-info-loc'),
				informationBox: this.gl('sct-info')
			}

			data.product.oneLiner = data.product.qtyPriceDisplay === 'one_liner' && data.product.showPqty && data.product.showPprice && data.product.showPtotal && !data.product.updateQty;

			return data;
		},

		toggle: function( type ){

			var $activeEls 	= $('body'),
				activeClass = 'osc-cart-active';

			if( type === 'show' ){
				$activeEls.addClass(activeClass);
			}
			else if( type === 'hide' ){
				$activeEls.removeClass(activeClass);
			}
			else{
				$activeEls.toggleClass(activeClass);
			}

		}
	}

	SideCart.init();


	$('select[name="osc-gl-options[m-ajax-atc]"]').on( 'change', function(){

		var $catSetting = $(this).closest('.osc-fw-as-setting').next();

		if( $(this).val() === 'cat_yes' || $(this).val() === 'cat_no' ){
			$catSetting.show();
		}
		else{
			$catSetting.hide();
		}
	} ).trigger('change');


	//Install login popup plugin
	$('.osc-el-install').click(function(e){

		e.preventDefault();
		var $cont = $(this).closest('.osc-el-links');
		$cont.html( 'Installing.. Please wait..' );

		$.ajax({
			url: osc_admin_params.adminurl,
			type: 'POST',
			data: {
				action: 'osc_el_install',
				osc_nonce: osc_admin_params.nonce
			},
			success: function( response ){

				if( response.firsttime_download ){
					$.post(osc_admin_params.adminurl, {
						'action': 'osc_el_request_just_to_init_save_settings'
					},function(result){
						if( response.notice ){
							$cont.html(response.notice)
						}
					})
				}
				else{
					if( response.notice ){
						$cont.html(response.notice)
					}
				}
				
			}
		})
	})



	//Hide/show product row and layout settings section
	$('select[name="osc-sy-options[scb-playout]"]').on('change', function(){

		var $rowSection 	= $('.osc-fw-ass-style-scb_product'),
			$cardSection 	= $('.osc-fw-ass-style-scb_productcard'),
			$cardlink 		= $('a[href="#style_scb_productcard"]'),
			$rowlink 		= $('a[href="#style_scb_product"]');

		if( $(this).val() === 'rows' ){
			$rowSection.show();
			$cardSection.hide();
			$cardlink.hide();
			$rowlink.show();
		}
		else{
			$rowSection.hide();
			$cardSection.show();
			$rowlink.hide();
			$cardlink.show();
		}
	}).trigger('change');


	var $oneLinerSetting = $('select[name="osc-gl-options[scbp-qpdisplay]"], select[name="osc-sy-options[scbp-qpdisplay]"]').closest('.osc-fw-as-setting');

	//Hide product elements for card layout depending on the items enabled/disabled
	$('input[name="osc-gl-options[scb-show][]"]').on( 'change', function(){

		var val 		= $(this).val(),
			isChecked 	= $(this).prop('checked');

		var $relatedEl = $('input[name="osc-sy-options[scbp-card-back][]"][value="'+val.replace("product_", "")+'"]');
		if( $relatedEl ){
			if( isChecked ){
				$relatedEl.closest('label').show();
			}
			else{
				$relatedEl.closest('label').hide();
			}
		}


		var oneLinerEligibile = ['product_price','product_qty','product_total'];

		if( oneLinerEligibile.includes( val ) ){

			if( !isChecked ){
				$oneLinerSetting.hide();
			}
			else{
				var allValues = $("input[name='osc-gl-options[scb-show][]']").map(function() {
				    return $(this).val();
				}).get();

				var failed = false;

				$.each( oneLinerEligibile, function( index, eligval ){
					if( !allValues.includes(eligval) ){
						failed = true;
						return false;
					}
				} )

				if( !failed ){
					$oneLinerSetting.show();
				}

			}
		}

		
	} ).trigger('change');


	//Disable one liner setting if quantity update is enabled.
	$('input[name="osc-gl-options[scb-update-qty]"]').on('change', function(){
		if( $(this).prop('checked') ){
			$oneLinerSetting.hide();
		}
		else{
			$('input[name="osc-gl-options[scb-show][]"]').trigger('change');
		}
	}).trigger('change');


	$('select[name="osc-gl-options[scbp-qpdisplay]"]').on( 'change', function(){
		$('select[name="osc-sy-options[scbp-qpdisplay]"]').val($(this).val());
	} );

	$('select[name="osc-sy-options[scbp-qpdisplay]"]').on( 'change', function(){
		$('select[name="osc-gl-options[scbp-qpdisplay]"]').val($(this).val());
	} );


	$('select[name="osc-gl-options[scbp-qpdisplay]"], select[name="osc-sy-options[scbp-qpdisplay]"]').on('change', function(){

		var $toggle = $('input[name="osc-sy-options[scbp-card-back][]"][value="total"], input[name="osc-sy-options[scbp-card-back][]"][value="price"]').closest('label');

		if( $(this).val() === 'one_liner' ){
			$toggle.hide();
		}
		else{
			$toggle.show();
		}
	}).trigger('change');


	$('select[name="osc-sy-options[scbp-card-visible]"]').on('change', function(){

		var $toggle = $('input[name="osc-sy-options[scbp-card-back][]"]').closest('.osc-fw-as-setting');

		if( $(this).val() === 'all_on_front' ){
			$toggle.hide();
		}
		else{
			$toggle.show();
		}
	})



	$('img.osc-patimg').on('click', function(){

		$('img.osc-patimg').removeClass('osc-patactive');

		$(this).addClass('osc-patactive')

		$('select[name="osc-sy-options[scb-playout]"]').val( $(this).data('pattern') ).trigger('change');
		
	});


	$('img.osc-patimg[data-pattern="'+$('select[name="osc-sy-options[scb-playout]"]').val()+'"]').addClass('osc-patactive');

	Customizer.pageLoading = false;
	Customizer.build();


	$('button.osc-adpopup-go').on('click', function(){
		$('body').removeClass('osc-adpopup-active');
		$('.osc-admin-popup').remove();
		$('img.osc-patimg[data-pattern="'+$('select[name="osc-sy-options[scb-playout]"]').val()+'"]').addClass('osc-patactive');
	});


	$('ul[id^="oscH-"]').sortable({
      connectWith: ".oscHconnectedSortable",
       axis: "x",
       update: function(event, ui) {

       	if( ui.sender ){

	       	var newName = ui.item.find('input').attr('name').replace(
	       		'['+ui.sender.data('name')+']',
	       		'['+ui.item.closest('ul').data('name')+']'
	       	);

	       	ui.item.find('input').attr('name',newName);
	    }

	    ui.item.find('input').trigger('change');

       }
    }).disableSelection();


	$('body').on('click', '.osc-acc-head', function(){

		var $container 	= $(this).closest('.osc-accordion'),
			$content 	= $container.children('.osc-acc-cont');

		$container.toggleClass('osc-acc-active');
	})



    var Rewards = {

		templateBar: '',
		templateCheckpoint: '',
		barInputNames: {},
		$cont: $('.osc-rewards-cont'),

		init: function(){
			this.initTemplates();
			this.events(); 
			this.createSettingsOnLoad();
		},

		barNumbering: function(){
			$.each( $('.osc-bar'), function( index, el ){
				var $el 		= $(el),
					$titleInput = $el.find('.osc-bar-title-input');
				$titleInput.val( $titleInput.val().replace( '[%^]','#'+ (index + 1) ) ).trigger('input');
			} )
		},

		createSettingsOnLoad(){
			var bars = osc_admin_params.bars;
			if( !bars ) return;
			$.each( bars, function( index, barData ){

				$bar 	= $(Rewards.templateBar(barData.settings));

				$('.osc-bars').append($bar);

				if( barData.checkpoints ){
					$.each( barData.checkpoints, function( index, checkpointData ){
						var $checkpoint = $(Rewards.templateCheckpoint(checkpointData));
						$bar.find('.osc-bar-checkpoints').append($checkpoint);
					} );
				}

				$bar.find('select.osc-bar-barValue').trigger('change');

				Rewards.onBarAdd($bar, false);
				
			} )

			Rewards.globalBarInit();
			
		},




		onBarAdd: function($bar, callGlobal = true){

			Rewards.initColorPicker($bar);
			
			$bar.find('.osc-bar-setting[data-barset="filter-byproduct"').trigger('change');
			$bar.find( '.osc-bar-prodsearch' ).each(function( index, el ){
				if( $(el).closest('.osc-bar-checkpoints').length ) return; //will fetch values later on checkpoint toggle.
				Rewards.productSearchFillDefaultValues($(el));
			})

			if( callGlobal ){
				Rewards.globalBarInit();
			}
			
			
		},

		globalBarInit: function(){
			Rewards.initProductSearchBox();
			Rewards.initSortable();
			Rewards.barNumbering();
		},


		addBar: function(){

			$('.osc-bar').removeClass('osc-acc-active');

			var $bar = $(Rewards.templateBar(osc_admin_params.barDefaults.settings));
	
			$('.osc-bars').append($bar);

			$bar.addClass('osc-acc-active');

			Rewards.onBarAdd($bar);

			
		},

		events: function(){
			$('button.osc-add-bar').on('click', Rewards.addBar );
			$('body').on('click', 'button.osc-bar-add-chkpoint', Rewards.addBarCheckpoint );
			$('body').on('click', '.osc-bar-delete', Rewards.deleteBar);
			$('body').on('click', '.osc-checkpoint-delete', Rewards.deleteCheckpoint);
			$('body').on('click', '.osc-bar-chkpoint > .osc-acc-head', Rewards.onCheckPointToggle );
			$('body').on( 'input', '.osc-chkpoint-title-input', Rewards.onCheckPointTitleChange );
			$('body').on( 'input', '.osc-bar-title-input', Rewards.onBarTitleChange );
			$('body').on( 'change', 'select.osc-bar-barValue', Rewards.onBarValueChange );
			$('body').on( 'change', '.osc-bar-setting[data-barset="filter-byproduct"]', Rewards.onProductFilterChange );

			$('button.osc-fw-as-form-save').on( 'click', Rewards.beforeSettingsSave );
			$(document).ajaxComplete(Rewards.onSettingsSave);
			
		},

		onProductFilterChange: function(){

			var $bar 						= $(this).closest('.osc-bar'),
				$productSearchCont 			= $bar.find('.osc-bar-setting[data-barset="filter-byproductsearch"]'),
				$notEligbTxtCont 			= $bar.find('.osc-bar-setting[data-barset="product-noteligbtxt"]'),
				$freeShippingPoint 			= $bar.find('.osc-bar-chkpoint[data-type="freeshipping"]'),
				$checkPointSelector 		= $bar.find('.osc-checkpoint-selector select'),
				$checkPointSelectorShipping = $checkPointSelector.find('option[value="freeshipping"]'),
				$shippingNotice 			= $bar.find('.osc-freeshipnotice');

			if( $(this).find('select').val() === 'no' ){
				$productSearchCont.add($shippingNotice).add($notEligbTxtCont).hide();
				$checkPointSelectorShipping.add($freeShippingPoint).show();
			}
			else{
				$productSearchCont.add($notEligbTxtCont).add($shippingNotice).show();
				$checkPointSelectorShipping.add($freeShippingPoint ).hide();
			}

			$checkPointSelector
			    .find('option:not([value="freeshipping"])')
			    .first()
			    .prop('selected', true)
			    .trigger('change');
		},


		onBarValueChange: function(){
			var $bar 						= $(this).closest('.osc-bar'),
				$checkPointSelector 		= $bar.find('.osc-checkpoint-selector select'),
				$checkPointSelectorShipping = $checkPointSelector.find('option[value="freeshipping"]'),
				$freeShippingCheckpoint 	= $bar.find('.osc-bar-chkpoint[data-type="freeshipping"]');


			if( $(this).val() === 'quantity' ){
				$checkPointSelectorShipping.add($freeShippingCheckpoint).hide();
				$checkPointSelector.val('discount').trigger('change');
			}
			else{
				$checkPointSelectorShipping.add($freeShippingCheckpoint).show();
			}
		},

		onCheckPointTitleChange: function(){
			$(this).closest('.osc-bar-chkpoint').find('.osc-chkpoint-title').text($(this).val());
		},

		onBarTitleChange: function(){
			var $bar = $(this).closest('.osc-bar');
			$bar.find('.osc-bar-title').text($(this).val());
		},

		onCheckPointToggle: function(){
			var $checkpoint = $(this).closest('.osc-bar-chkpoint');
			Rewards.initIconPicker( $checkpoint );
			$.each( $checkpoint.find('.osc-bar-prodsearch'), function( index, el ){
				Rewards.productSearchFillDefaultValues($(el));
			});
		},


		productSearchFillDefaultValues( $searchCont ){

			
			var	$defaultCont 	= $searchCont.find('.osc-barpsearch-defaults');

			if( !$defaultCont.length ) return true;

			var $defaultInputs  = $defaultCont.find('input'),
				$searchSelect 	= $searchCont.find('select.wc-product-search'),
				defaultValues 	= [] ;

			if( !$defaultInputs.length ) return true;

			let productIDs = $defaultInputs.map(function(){
			    return $(this).val();
			}).get();

			$searchCont.addClass('osc-fw-as-processing');

			$.ajax({
				url: osc_admin_params.adminurl,
				type: 'POST',
				data: {
					action: 'osc_product_search_fill_defaults',
					product_ids: productIDs,
					osc_nonce: osc_admin_params.nonce
				},
				success: function( response ){
					$searchSelect.html(response);
					$defaultCont.remove();
					$searchCont.removeClass('osc-fw-as-processing');
				}
			})



		},

		onSettingsSave: function(event,xhr,options){

			if( $(event.target.activeElement).hasClass('osc-fw-as-form-save') ){

				$.each( Rewards.barInputNames, function( newName, oldName ){
					$('[name="'+newName+'"]').attr('name', oldName);
				})

				Rewards.barInputNames = {};

				Rewards.$cont.removeClass('osc-fw-as-processing');

			}
		},

		beforeSettingsSave: function(){

			var $cont 	= Rewards.$cont,
				id 		= '[%$]';

			$cont.addClass('osc-fw-as-processing');

			$('.osc-bar').each( function(index, el){
				
				$(el).find('[name*="' + id + '"]').each( function(i, inel){

					var name 	= $(inel).attr('name'),
						newName = name.replace( '[%$]', '['+index+']' );

					if( name.includes('[%#]') ){
						var checkPointIndex = $(inel).closest('.osc-bar-chkpoint').index();
						newName = newName.replace('[%#]', '['+checkPointIndex+']' );
					}

					Rewards.barInputNames[newName] = name;

					$(inel).attr('name' , newName );

					if( name === id+'[id]' ){
						$(inel).val( 'id_'+index );
					}

				} );


			} );
		},


		initColorPicker: function($bar){
			$bar.find('.osc-barColorPicker input:not(.wp-color-picker)').wpColorPicker({
				change: function(event, ui){
					$(event.target).val(ui.color.toString()).trigger('change')
				}
			});
		},


		initSortable: function(){
			$('.osc-bars').sortable({
				handle: '.osc-bar-head'
			});
		},

		initTemplates: function(){
			this.templateBar 		= wp.template('osc-fw-as-bar');
			this.templateCheckpoint = wp.template('osc-fw-as-chkpoint');
		},

		

		deleteBar: function(e){
			if( !confirm( 'Are you sure you want to delete this progress bar and all its checkpoints?' ) ){
				e.preventDefault();
				return;
			}
			$(this).closest('.osc-bar').remove();
		},

		deleteCheckpoint: function(e){
			$(this).closest('.osc-bar-chkpoint').remove();
			e.stopImmediatePropagation();
		},

		addBarCheckpoint: function(){

			var $bar 			= $(this).closest('.osc-bar'),
				$type  			= $bar.find('.osc-checkpoint-selector select');

			var checkpointData 	= {
				type: $type.val(),
			}

			checkpointData = $.extend( osc_admin_params.barDefaults.checkpoints[checkpointData.type], checkpointData );

			$bar.find('.osc-bar-chkpoint').removeClass('osc-acc-active');

			var $checkpoint = $(Rewards.templateCheckpoint(checkpointData));

			$bar.find('.osc-bar-checkpoints').append($checkpoint);

			$checkpoint.addClass('osc-acc-active');

			Rewards.initIconPicker( $checkpoint );
			Rewards.initProductSearchBox();


		},

		initProductSearchBox(){
			$( document.body ).trigger( 'wc-enhanced-select-init' );
		},

		initIconPicker( $checkpoint ){

			$checkpoint.find('.osc-bar-icon:not(.iconpicker-input)').iconpicker({
				hideOnSelect: true,
			}).on('iconpickerSelected', function(e){
			  $(e.target).next().attr('class',e.iconpickerValue || $(e.target).val() );
			}).trigger('iconpickerSelected');
			
		}

	}

	Rewards.init();

	$(document).on('click', '.iconpicker-item', function(e) {
	    e.preventDefault(); // stops "#" from being written
	});


	setTimeout( function(){

		var onceResized = false;

		$(window).resize(function(){

			if( onceResized ) return;

			const $container 	= $('.osc-fw-as-container');

			if( $container.length && $container.innerWidth() <= 900 ){
				
				SideCart.toggle('close');
				
			}

			onceResized = true;

		}).trigger('resize');

	}, 400 );
})