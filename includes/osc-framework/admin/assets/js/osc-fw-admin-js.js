jQuery(document).ready(function($){

	window.osc_fw_admin_params.debounce = function( fn, delay ) {

		let timer;

		return function() {

			clearTimeout( timer );

			const args = arguments;
			const context = this;

			timer = setTimeout( function() {
				fn.apply( context, args );
			}, delay );

		};

	};

	//Form reset
	$('.osc-fw-as-form-reset').click(function(e){
		if( !confirm( 'Are you sure?' ) )
			e.preventDefault();
	})

	//Toggle pro
	$('.osc-fw-as-pro-toggle').click(function(e){
		$('.osc-fw-settings-container').toggleClass('osc-fw-as-disable-pro');
	})

	$('.osc-fw-settings-container').addClass('osc-fw-as-disable-pro');

	var sectionScrollPositions = {}

	//Setting default position to 0
	$('ul.osc-fw-sc-tbar-tabs li').each( function(){
		sectionScrollPositions[ $(this).data('tab') ] = $('.osc-fw-sc-tbar-tabs').offset().top;

	} );


	var firstClick = true;

	function updateActiveSection(){

	    const hash = window.location.hash;

	    $('.osc-fw-as-setsbar-section').removeClass('osc-fw-active');

	    $('.osc-fw-as-setsbar-section[href="' + hash + '"]').addClass('osc-fw-active');
	}

	$(window).on('hashchange', updateActiveSection);

	updateActiveSection();

	//Switch Tabs
	$('ul.osc-fw-sc-tbar-tabs li').click(function(){

		if( !firstClick ){
			sectionScrollPositions[$('ul.osc-fw-sc-tbar-tabs li.osc-fw-sct-active').data('tab')] = $(window).scrollTop();
		}

		const activeClass 		= 'osc-fw-sct-active',
			  selectedTabID  	= $(this).data('tab'); 

		$('ul.osc-fw-sc-tbar-tabs li, .osc-fw-sc-tab-content, .osc-fw-as-setsections').removeClass(activeClass);

		$(this).addClass(activeClass);

		$('.osc-fw-as-container').attr( 'data-active_tab', selectedTabID );

		$('.osc-fw-as-setsections[data-tab="'+selectedTabID+'"]').addClass(activeClass); //activating section

		$('.osc-fw-sc-tab-content[data-tab="'+selectedTabID+'"]').addClass(activeClass);

		if( !firstClick ){
			$(window).scrollTop( sectionScrollPositions[ selectedTabID ] );
		}
		
		firstClick = false;

	})

	$('ul.osc-fw-sc-tbar-tabs li:nth-child(1)').trigger('click');

	$('.osc-fw-as-form').on( 'submit', function(e){

		e.preventDefault();

		var $button = $(this).find('.osc-fw-as-form-save');
			$buttonHTML = $button.html();

		$button.text( 'Saving....' );

		var data = {
			'form': $(this).serialize(),
			'action': 'osc_fw_admin_settings_save',
			'osc_fw_ff_nonce': osc_fw_admin_params.nonce,
			'slug': osc_fw_admin_params.slug
		}

		$.ajax({
			url: osc_fw_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$button.text('Settings Saved');
				setTimeout(function(){
					$button.html( $buttonHTML )
				},2000)
			}
		});

	})



	//Media

	function renderMediaUploader(upload_btn) {
	 
	    var file_frame, image_data;
	 
	    /**
	     * If an instance of file_frame already exists, then we can open it
	     * rather than creating a new instance.
	     */
	    if ( undefined !== file_frame ) {
	 
	        file_frame.open();
	        return;
	 
	    }
	 
	    /**
	     * If we're this far, then an instance does not exist, so we need to
	     * create our own.
	     *
	     * Here, use the wp.media library to define the settings of the Media
	     * Uploader. We're opting to use the 'post' frame which is a template
	     * defined in WordPress core and are initializing the file frame
	     * with the 'insert' state.
	     *
	     * We're also not allowing the user to select more than one image.
	     */
	    file_frame = wp.media.frames.file_frame = wp.media({
	        frame:    'post',
	        state:    'insert',
	        multiple: false
	    });
	 
	    /**
	     * Setup an event handler for what to do when an image has been
	     * selected.
	     *
	     * Since we're using the 'view' state when initializing
	     * the file_frame, we need to make sure that the handler is attached
	     * to the insert event.
	     */
	    file_frame.on( 'insert', function() {
	 	
	        // Read the JSON data returned from the Media Uploader
   		 	var json = file_frame.state().get( 'selection' ).first().toJSON();

   		 	upload_btn.siblings('.osc-fw-upload-url').val(json.url);
   		 	upload_btn.siblings('.osc-fw-upload-title').html(json.filename);
   		
	 
	    });
	 
	    // Now display the actual file_frame
	    file_frame.open();
 
	}





	
    $( '.osc-fw-upload-icon' ).on( 'click', function( evt ) {

        // Stop the anchor's default behavior
        evt.preventDefault();

        // Display the media uploader
        renderMediaUploader($(this));

    });
 
   


    //Get media uploaded name
	$('.osc-fw-upload-url').each(function(){
		var media_url = $(this).val();
		if(!media_url) return true; // Skip to next if no value is set

		var index = media_url.lastIndexOf('/') + 1;
		var media_name = media_url.substr(index);

		$(this).siblings('.osc-fw-upload-title').html(media_name);
	})


	//Remove uploaded file
	$('.osc-fw-remove-media').on('click',function(){
		$(this).siblings('.osc-fw-upload-url').val('');
		$(this).siblings('.osc-fw-upload-title').html('');
	})


	//Initialize color picker
	$('.osc-fw-as-color-input').wpColorPicker();

	//initialize sortable
	$('.osc-fw-as-sortable-list').each( function( index, sortEl ){
		var $sortEl = $(sortEl),
			sortData = $sortEl.data('sort');
		$sortEl.sortable( sortData );
	} );


	$( 'select[data-select2box="yes"]' ).each(function(index, el){
		var $el = $(el);
		$el.select2({
			multiple: $el.attr('data-multiple')
		});
	});


	$('.osc-fw-as-exim').on( 'click', function(){
		$(this).toggleClass('osc-fw-as-active');
	} );


	//On export settings click
	$('.osc-fw-as-setexport').on( 'click', function(){
		var $form = $(this).closest('form.osc-fw-as-form');
		$('.osc-fw-as-exim').removeClass('osc-fw-as-active');
		$('body').addClass('osc-fw-as-exmodal-active');
		$('.osc-fw-as-excont textarea').val( JSON.stringify($form.serializeArray()) ).select();

		$('.osc-fw-as-impcont').hide();
		$('.osc-fw-as-excont').show();
	} );


	//Close import/export modal
	$('.osc-fw-as-exipclose').on( 'click', function(){
		$('body').removeClass('osc-fw-as-exmodal-active');
	} );



	//On import settings click
	$('.osc-fw-as-setimport').on( 'click', function(){
		$('.osc-fw-as-exim, .osc-fw-as-imported').removeClass('osc-fw-as-active');
		$('.osc-fw-as-impcont').show();
		$('.osc-fw-as-excont').hide();
		$('body').addClass('osc-fw-as-exmodal-active');
	} );


	$('.osc-fw-as-run-export').click( function(){

		$('.osc-fw-as-expdone').hide();

		var options = [];

		$('.osc-fw-as-expcheck input[type="checkbox"]:checked').each( function( index, el ){
			var $el = $(el);
			options.push($el.attr('value'));
		} )

		if( !options.length ) return;

		var $button = $('button.osc-fw-as-run-export ');

		$button.addClass('osc-fw-as-processing');
		$button.text( 'Please wait....' );


		var data = {
			'action': 'osc_fw_admin_settings_export',
			'osc_fw_ff_nonce': osc_fw_admin_params.nonce,
			'slug': osc_fw_admin_params.slug,
			'options': options
		}

		$.ajax({
			url: osc_fw_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$button.text('Export Success');
				

				setTimeout(function(){
					$button.text( 'Export' )
				},5000)
				$('.osc-fw-as-expdone').show();
				$('.osc-fw-as-expdone textarea').val(JSON.stringify(response)).select();
			}
		});

	} );

	$('button.osc-fw-as-run-import').click( function(){

		if( !confirm( 'This will override your current settings. Are you sure?' ) ) return;

		var textValue 	= $('.osc-fw-as-impcont textarea').val(),
			$button  	= $(this);

		$button.addClass('osc-fw-as-processing');
		$button.text( 'Please wait....' );

		var data = {
			'action': 'osc_fw_admin_settings_import',
			'osc_fw_ff_nonce': osc_fw_admin_params.nonce,
			'slug': osc_fw_admin_params.slug,
			'import': textValue
		}

		$.ajax({
			url: osc_fw_admin_params.adminurl,
			type: 'POST',
			data: data,
			success: function(response){
				$('.osc-fw-as-imported').addClass('osc-fw-as-active');
				$('.osc-fw-as-impcont textarea').val('');
				$button.text('Import Success');
				setTimeout(function(){
					$button.text( 'Import' );
					location.reload();
				},3000)
			}
		});

	})


	$('img.osc-fw-as-patimg').on('click', function(){

		var $cont 		= $(this).closest('.osc-fw-as-pattern-cont'),
			$checkbox  	= $(this).siblings('input[type="checkbox"]'),
			hasMultiple = $cont.data('multiple') === "yes",
			isRequired 	= $cont.data('required') === "yes"; 

		if( hasMultiple ){
			if( isRequired && $cont.find('input[type="checkbox"]:checked').length === 1 && $checkbox.is(':checked')  ) return; //cannot uncheck last checked option if required
			$(this).toggleClass('osc-fw-as-patactive');
			$checkbox.prop('checked', function (i, val) { //toggle
				return !val;
			}).trigger('change');
		}
		else{
			$cont.find('img.osc-fw-as-patimg').removeClass('osc-fw-as-patactive');
			$(this).addClass('osc-fw-as-patactive');
			$cont.find('input[type="checkbox"]').prop('checked', false).trigger('change');
			$checkbox.prop('checked',true).trigger('change');
		}

	});

	$('.osc-fw-as-patcheckbox').each(function(index, el){
		var $el = $(el);
		if( $el.prop('checked') ){
			$el.siblings('img.osc-fw-as-patimg').addClass('osc-fw-as-patactive');
		}
	});

	$('.osc-fw-as-info-hover').hover(
		function() {
			$(this).closest('.osc-fw-as-pattern-cont').find('.osc-fw-as-info[data-key="'+$(this).data('key')+'"]').show();
		},
		function() {
			$('.osc-fw-as-info').hide();
		}
	);


	$('.osc-fw-as-form').on('change', ':input', function() {

		//Value based description
		let $fieldCont 		= $(this).closest('.osc-fw-as-field'),
			$settingCont 	= $(this).closest( '.osc-fw-as-setting' ),
			fieldVal 		= $(this).val(),
			fieldId 		= $settingCont.data('field_id');

		if( $(this).is(':checkbox') && !$(this).is(':checked') ){
			fieldVal = 'unchecked';
		}

		if( $fieldCont.length && $fieldCont.find('.osc-fw-as-val-desc').length ){


			let $valueDesc 	= $fieldCont.find('.osc-fw-as-val-desc'),
				descData 	= $valueDesc.data('desc');

			$valueDesc.text('');

			if( descData[ $(this).val() ] ){
				$valueDesc.text( descData[ $(this).val() ] );
			}

		}

		//Toggle settings

		let toggleSettings = $settingCont.data('togglesettings');

		if( toggleSettings ){
			$.each( toggleSettings, function( settingID, settingValues ){

				let $setting = $('.osc-fw-as-setting[data-field_id="'+settingID+'"]');

				if( !$setting.length  ) return;

				let hiddenby = $setting.data('hiddenby' ) || {};

				if( settingValues.includes(fieldVal) ){
					hiddenby[ fieldId ] = 1;
				}
				else{
					delete hiddenby[ fieldId ];
				}
				

				$setting.attr('data-hiddenby', JSON.stringify(hiddenby));

				if( Object.keys(hiddenby).length ){
					$setting.hide();
				}
				else{
					$setting.show();
				}

				
				
			} )
		}

	});

	$('.osc-fw-as-setting[data-togglesettings] :input').trigger('change');


	setTimeout( function(){


		$(window).resize(function(){

			const $form 		= $('form.osc-fw-as-form');
			const $container 	= $('.osc-fw-as-container');

			if( $form.length ){

				if( $form.innerWidth() <= 700 ){
					$('.osc-fw-as-sidebar').addClass('osc-fw-as-sbar-collapsed');
					$form.addClass('osc-fw-as-break');
				}
				else{
					$form.removeClass('osc-fw-as-break');
				}

			}

			if( $container.length ){
				if( $container.innerWidth() <= 950 ){
					if( !$('body').hasClass('folded') ){
						$('#collapse-button').trigger('click');
					}
					$container.addClass('osc-fw-as-smaller');
				}
				else{
					$container.removeClass('osc-fw-as-smaller');
				}
			}

		}).trigger('resize');

	}, 400 );


	$('.osc-fw-as-sbar-close').on( 'click', function(){
		$('.osc-fw-as-sidebar').toggleClass('osc-fw-as-sbar-collapsed');
	} );

	$('.osc-fw-as-sidebar').css({
		'margin-top': $('.osc-fw-sc-tbar-tabs').outerHeight(),
		'top': $('#wpadminbar').outerHeight() + 10
	}); 


	$(document).on( 'click', '.osc-fw-set-tab', function(){

		var $trigger 	= $(this),
			target 		= $trigger.data('xootab'),
			$wrapper 	= $trigger.closest('.osc-fw-tabs-cont');

		$trigger.addClass('osc-fw-tabactive').siblings('[data-xootab]').removeClass('osc-fw-tabactive');

		$wrapper.find('[data-xootab]').removeClass('osc-fw-tabactive');

		$wrapper.find('[data-xootab="' + target + '"]').addClass('osc-fw-tabactive');

	});


	var BtnTheme = {

		themeTemplate: '',

		themeInputNames: {},

		$cont: $('.osc-fw-btntheme-cont'),

		init: function(){
			this.initTemplates();
			this.events(); 
			this.renderThemes(); //creates theme and settings on load
			this.loadThemeSelectorOptions(); // Button theme selector options added
		},


		events: function(){

			$('button.osc-fw-add-btntheme').on('click', BtnTheme.addTheme );

			$('body').on('click', '.osc-fw-acc-ctadel', BtnTheme.deleteTheme);

			$('body').on('click', '.osc-fw-acc-ctacopy', BtnTheme.copyTheme);

			$('body').on( 'input', '.osc-fw-btntheme-title-input', BtnTheme.onThemeTitleInput );

			$('body').on( 'change', '.osc-fw-btntheme-title-input', BtnTheme.loadThemeSelectorOptions );

			$('button.osc-fw-as-form-save').on( 'click', BtnTheme.beforeSettingsSave );

			$(document).ajaxComplete(BtnTheme.onSettingsSave);

			$(document).on( 'osc_fw_accordion_toggled', BtnTheme.onAccordionToggled );

			$(document).on( 'input change','.osc-fw-btn-setting input, .osc-fw-btn-setting select', osc_fw_admin_params.debounce( BtnTheme.onThemeSettingsChange, 200 ) );

			$('body').on( 'change', '.osc-fw-btnrowset-sizetype select', BtnTheme.onThemeSettingSizeChange );
			
		},


		onThemeSettingSizeChange: function(){
			BtnTheme.toggleSizeTypeFields( $(this).closest('.osc-fw-btntheme') );
		},

		toggleSizeTypeFields: function( $theme ){
			var $sizeSelectField = $theme.find( '.osc-fw-btnrowset-sizetype select');
			$theme.find('[data-size_type]').hide();
			$theme.find('[data-size_type="'+$sizeSelectField.val()+'"]').show();
		},

		onThemeSettingsChange: function(){
			BtnTheme.renderPreview( $(this).closest('.osc-fw-btntheme') );
		},

		getThemes: function( theme_id ) {

		    var formValues = BtnTheme.$cont.closest('form').serializeJSON(),
		        field_id   = BtnTheme.$cont.closest('.osc-fw-as-button_theme_creator').data('field_id');

		    const themes = field_id
		        .match(/[^[\]]+/g)
		        .reduce((current, key) => current?.[key], formValues);

		    if( theme_id === undefined ) {
		        return themes;
		    }

		    if( theme_id instanceof jQuery ) {
		        theme_id = theme_id.find('input.osc-fw-btntheme-id').val();
		    }


		    return themes?.[theme_id] || null;
		},

		loadThemeSelectorOptions: function(){

			console.log('loaded');

			var themes = BtnTheme.getThemes();

			if( !themes ) return;

			var optionsHTML = '';

			$.each( themes, function( theme_id, theme_data ){
				optionsHTML += '<option value="'+theme_id+'">'+theme_data['title']+'</option>';
			} );

			$('.osc-fw-as-setting[data-setting="button_theme_selector"]').each(function(index, el){

				const $select 		= $(el).find('select');

				const selectedVal 	= $select.val() || $select.data('default');

				$select.html(optionsHTML);

				if( $select.find('option').length ){
					if( selectedVal && $select.find('option[value="'+selectedVal+'"]').length ){
						$select.val( selectedVal );
					}
					else{
						$select.val( $select.find('option:first-child').val() );
					}
				}

			})
		},

		onAccordionToggled: function( e, $container, isOpened ){

		
		},


		themeNumbering: function(){
			$.each( $('.osc-fw-btntheme'), function( index, el ){
				var $el 		= $(el),
					$titleInput = $el.find('.osc-fw-btntheme-title-input');
				$titleInput.val( $titleInput.val().replace( '[%^]','#'+ (index + 1) ) ).trigger('input');
			} )
		},

		renderThemes(){

			var themes = JSON.parse( BtnTheme.$cont.attr('data-value') );

			if( !themes ) return;

			$.each( themes, function( index, themeData ){

				var $theme 	= $(BtnTheme.themeTemplate( themeData ) );

				$('.osc-fw-btnthemes').append( $theme );

				BtnTheme.onThemeAdd( $theme, false );
				
			} )

			BtnTheme.globalThemeInit();
			
		},


		onThemeAdd: function( $theme, callGlobal = true ){

			BtnTheme.initColorPicker($theme);

			BtnTheme.renderPreview($theme);

			BtnTheme.toggleSizeTypeFields( $theme );
			
			if( callGlobal ){
				BtnTheme.globalThemeInit();
				BtnTheme.loadThemeSelectorOptions();
			}
			
			
		},


		renderPreview: function( $theme ){

		    var css = BtnTheme.getCSS(
		        BtnTheme.getThemes( $theme.find('input.osc-fw-btntheme-id').val() ),
		        '.osc-fw-btn-setting[data-field_id="' + $theme.data('field_id') + '"] .osc-fw-btn-preview-wrap button'
		    );

		    $theme.find('.osc-fw-btn-preview-wrap style').html( css );

	
		},


		generateID: function(){
			return 'theme_'+crypto.randomUUID().replace(/-/g, '');
		},

		globalThemeInit: function(){
			BtnTheme.initSortable();
			BtnTheme.themeNumbering();
		},


		

		createTheme: function( values ){

			$('.osc-fw-btntheme').removeClass('osc-fw-acc-active');	

			values.theme_id = BtnTheme.generateID();

			var $theme = $(BtnTheme.themeTemplate( values ) );
	
			$('.osc-fw-btnthemes').append($theme);

			$theme.addClass('osc-fw-acc-active');

			BtnTheme.onThemeAdd($theme);
		},

		addTheme: function(){

			var defaults = JSON.parse( BtnTheme.$cont.attr('data-defaults') );

			BtnTheme.createTheme( defaults );
		},


	
		copyTheme: function(){

			var values = BtnTheme.getThemes( $(this).closest('.osc-fw-btntheme') );

			BtnTheme.createTheme( values );


		},

		onThemeTitleInput: function(){

			var $theme = $(this).closest('.osc-fw-btntheme');

			$theme.find('.osc-fw-btntheme-title').text($(this).val());

			

		},



		onSettingsSave: function(event,xhr,options){

			if( $(event.target.activeElement).hasClass('osc-fw-as-form-save') ){

				BtnTheme.$cont.removeClass('osc-fw-as-processing');

			}
		},

		beforeSettingsSave: function(){

			var $cont 	= BtnTheme.$cont,
				id 		= '[%$]';

			$cont.addClass('osc-fw-as-processing');

			
		},


		initColorPicker: function($theme){
			$theme.find('.osc-fw-as-color-input:not(.wp-color-picker)').wpColorPicker({
				change: function(event, ui){
					$(event.target).val(ui.color.toString()).trigger('change')
				}
			});
		},


		initSortable: function(){
			$('.osc-fw-btntheme-cont').sortable({
				handle: '.osc-fw-btntheme-head'
			});
		},

		initTemplates: function(){
			this.themeTemplate 	= wp.template('osc-fw-as-btntheme');
		},

		

		deleteTheme: function(e){
			if( !confirm( 'Are you sure you want to delete this Button Theme?' ) ){
				e.preventDefault();
				return;
			}
			$(this).closest('.osc-fw-btntheme').remove();

			BtnTheme.loadThemeSelectorOptions();
		},

		initIconPicker( $checkpoint ){

			$checkpoint.find('.osc-bar-icon:not(.iconpicker-input)').iconpicker({
				hideOnSelect: true,
			}).on('iconpickerSelected', function(e){
			  $(e.target).next().attr('class',e.iconpickerValue || $(e.target).val() );
			}).trigger('iconpickerSelected');
			
		},

		getCSS: function( values, selectors ) {

		    selectors = Array.isArray( selectors ) ? selectors : [ selectors ];

		    var border      = values.border || {},
		        hover       = values.hover || {},
		        hoverBorder = hover.border || {},
		        text        = values.text || {};

		    var normalSelectors = selectors.join(','),
		        hoverSelectors = $.map( selectors, function( selector ) {
		            return selector + ':hover';
		        }).join(',');

		    var isAuto = values.size_type === 'auto';

		    return normalSelectors + '{' +

		        'max-width:' + ( isAuto ? 'none' : ( values.width || '' ) + ( values.width_unit || '' ) ) + ';' +
		        'width:' + ( isAuto ? 'max-content' : '100%' ) + ';' +
		        'height:' + ( isAuto ? 'auto' : ( values.height || '' ) + ( values.height_unit || '' ) ) + ';' +
		        'padding:' + ( isAuto
		            ? ( values.padding_v || 0 ) + 'px ' + ( values.padding_h || 0 ) + 'px'
		            : '5px 10px'
		        ) + ';' +

		        'background-color:' + ( values.bgColor || '' ) + ';' +
		        'color:' + ( values.txtColor || '' ) + ';' +

		        'font-weight:' + ( text.fontWeight || 500 ) + ';' +
		        'font-style:' + ( text.fontStyle || 'normal' ) + ';' +
		        'font-size:' + ( text.fontSize || 15 ) + ( text.fontSizeUnit || 'px' ) + ';' +
		        'text-transform:' + ( text.textTransform || 'none' ) + ';' +

		        'border:' + ( border.size || 0 ) + 'px ' + ( border.style || 'solid' ) + ' ' + ( border.color || 'transparent' ) + ';' +
		        'border-radius:' + ( border.radius || 0 ) + 'px;' +
		        'display:inline-flex;' +
				'align-items:center;' +
				'justify-content:center;'+

		    '}' +

		    hoverSelectors + '{' +

		        'background-color:' + ( hover.bgColor || values.bgColor || '' ) + ';' +
		        'color:' + ( hover.txtColor || values.txtColor || '' ) + ';' +
		        'border:' + ( hoverBorder.size || border.size || 0 ) + 'px ' + ( hoverBorder.style || border.style || 'solid' ) + ' ' + ( hoverBorder.color || border.color || 'transparent' ) + ';' +
		        'border-radius:' + ( hoverBorder.radius || border.radius || 0 ) + 'px;' +

		    '}';
		}

	}

	BtnTheme.init();

	osc_fw_admin_params.BtnTheme = BtnTheme;


	$('body').on( 'click', '.osc-fw-as-resetval', function(){

		var $settingCont = $(this).closest('.osc-fw-as-setting');

		if( $settingCont.data('setting') === 'wp_editor' && $settingCont.find('.wp-editor-area').length ){


			var editorId 	= $settingCont.find('.wp-editor-area').attr('id'),
			 	editor 		= tinymce.get(editorId);

			if (editor) {
			    editor.setContent(JSON.parse($(this).data('default')));
			    editor.save();
			    $('#' + editorId).trigger('change');
			}
		}

		
	} )



	function toggleAccordion( $container ){

		$content 	= $container.children('.osc-fw-acc-cont');

		$container.toggleClass('osc-fw-acc-active');

		$container.trigger( 'osc_fw_accordion_toggled', [ $container, $container.hasClass('osc-fw-acc-active') ] );
	}


	$('body').on('click', '.osc-fw-acc-ctaedit', function(){

		var $container 	= $(this).closest('.osc-fw-accordion');

		toggleAccordion( $container );

	});


	

	
})