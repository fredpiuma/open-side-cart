<?php

function osc_quantity_input( $args = array(), $product = null, $echo = true ) {

	if ( is_null( $product ) ) {
		return;
	}

	$defaults = array(
		'input_value'  	=> '1',
		'max_value'    	=> apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
		'min_value'    	=> apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
		'step'         	=> apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
		'pattern'      	=> apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
		'inputmode'    	=> apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
		'placeholder'  	=> apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
		'wsc_classes'  	=> apply_filters( 'osc_quantity_input_classes', array( 'osc-qty' ), $product ),
		'qtyDesign' 	=> osc_helper()->get_style_option('scbq-style')
	);

	$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

	// Apply sanity to min/max args - min cannot be lower than 0.
	$args['min_value'] = max( $args['min_value'], 0 );
	$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

	// Max cannot be lower than min if defined.
	if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
		$args['max_value'] = $args['min_value'];
	}

	ob_start();

	osc_helper()->get_template( 'global/body/qty-input.php', $args );

	if ( $echo ) {
		echo ob_get_clean(); // WPCS: XSS ok.
	} else {
		return ob_get_clean();
	}
}

function osc_notice_html( $message, $notice_type = 'success' ){
	
	$classes = $notice_type === 'error' ? 'osc-notice-error' : 'osc-notice-success';

	$icon = $notice_type === 'error' ? 'osc-icon-cross' : 'osc-icon-check_circle';
	
	$html = '<li class="'.$classes.'"><span class="'.$icon.'"></span>'.$message.'</li>';
	
	return apply_filters( 'osc_notice_html', $html, $message, $notice_type );
}



function osc_suggested_product_addtocart_link( $link, $product, $args ){

	if( !isset( $args['is_osc_sp'] ) ) return $link;

	return sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		'<span>+</span>'. __( 'Add', 'open-side-cart' )
	);
}
add_filter( 'woocommerce_loop_add_to_cart_link', 'osc_suggested_product_addtocart_link', 999, 3 );


function osc_add_flytocart_img_attr( $attr, $attachment, $size ){
	global $product;
	if( $product ){
		$attr['data-oscFly'] = 'fly';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'osc_add_flytocart_img_attr', 999, 3 );


function osc_display_suggested_products(){
	osc_helper()->get_template( 'global/footer/suggested-products.php' );
}


function osc_add_sp(){

	$location 	= osc_helper()->get_style_option('scsp-main-location');

	if( $location === 'before' || wp_is_mobile() ){
		$hook = 'osc_body_end';
	}
	elseif( $location === 'after' ){
		$hook = 'osc_footer_end';
	}
	else{
		return;
	}

	add_action( $hook, 'osc_display_suggested_products' );

}
add_action( 'osc_header_start', 'osc_add_sp' );



function osc_totals_set_location(){
	$locationHook = osc_helper()->get_style_option('scf-totals-loc') === 'body' || ( wp_is_mobile() && osc_helper()->get_style_option('scf-totals-loc') === 'mobile_body' ) ? 'osc_body_end' : 'osc_footer_content';
	add_action( $locationHook, 'osc_footer_totals_html', 20 );
}

add_action( 'osc_header_start', 'osc_totals_set_location' );

function osc_footer_extras_html(){
	osc_helper()->get_template( 'global/footer/extras.php' );
}

function osc_footer_totals_html(){
	osc_helper()->get_template( 'global/footer/totals.php' );
}


function osc_footer_text_html(){
	$footerTxt = osc_helper()->get_general_option('sct-footer');
	if( !$footerTxt || ( osc_helper()->get_general_option('scf-ftext-hide') === "yes" && WC()->cart->is_empty() ) ) return;
	?>
	<span class="osc-footer-txt"><?php echo $footerTxt; ?></span>
	<?php
}


function osc_footer_buttons_html(){
	osc_helper()->get_template( 'global/footer/buttons.php' );
}

add_action( 'osc_footer_content', 'osc_footer_extras_html', 10 );
add_action( 'osc_footer_content', 'osc_footer_text_html', 30 );
add_action( 'osc_footer_content', 'osc_footer_buttons_html', 40 );


//Divi builder fix
function osc_fix_for_divi_builder(){

	if( !function_exists('osc_frontend') ) return;

	if( defined('ET_CORE_VERSION') && !isset( $_GET['et_fb'] ) ){ // for front end
		remove_action( 'wp_body_open', array( osc_frontend(), 'cart_markup' ) );
	}

	if ( isset( $_GET['et_fb'] ) ){ // for back end customizer
		remove_action( 'wp_footer', array( osc_frontend(), 'cart_markup' ) );
		add_action( 'wp_body_open', array( osc_frontend(), 'cart_markup' ) );
	}

}
add_action( 'wp', 'osc_fix_for_divi_builder'  );


function osc_add_ajax_atc_disable_form(){
	global $product;

	if( !osc_enable_ajax_atc_for_product( $product ) ){
		echo '<span class="osc-disable-atc" style="display: none!important"></span>';
	}
}

add_action( 'woocommerce_before_add_to_cart_form', 'osc_add_ajax_atc_disable_form' );


function osc_enable_ajax_atc_for_product( $product ){

	if( is_int( $product ) ){
		$product = wc_get_product( $product );
	}

	$ajaxAtc = osc_helper()->get_general_option('m-ajax-atc');

	$enable = true;

	if( $ajaxAtc === 'yes' ){
		$enable = true;
	}
	else if ( $ajaxAtc === 'no' ) {
		$enable = false;
	}
	else{

		$catIds = osc_helper()->get_general_option('m-ajax-atc-catid');

		$catIds = $catIds ? explode(',', $catIds ) : array();
		
		//Enable on all except
		if( $ajaxAtc === 'cat_no' ){
			$enable = !( !empty( $catIds ) && array_intersect( $catIds , $product->get_category_ids() ) );	
		}

		//Enable for these category
		if( $ajaxAtc === 'cat_yes' ){
			$enable = array_intersect( $catIds , $product->get_category_ids() );
		}

	}

	return apply_filters( 'osc_enable_ajax_atc', $enable, $product );

}

function osc_progress_bar(){
	osc_helper()->get_template('global/header/bar.php');
}



/* Enqueue Cart Fragments */
add_action( 'wp_enqueue_scripts', function(){
	wp_enqueue_script( 'wc-cart-fragments' );
}, 999 );


function osc_elementor_disable_cart( $ispage ){
	if(  defined( 'ELEMENTOR_VERSION' ) && ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()  ) ){
		$ispage = false;
	}
	return $ispage;
}

add_filter( 'osc_is_sidecart_page', 'osc_elementor_disable_cart' );


function osc_display_infobox(){
	echo '<div class="osc-info-cont">';
	echo osc_helper()->get_general_option('sct-info');
	echo '</div>';
}



/* Information box location */
function osc_add_infobox_hook(){

	$location 	= osc_helper()->get_style_option('scm-info-loc');

	if( $location === 'body_end' || $location === 'body_end_stick' || ( $location === 'mobile_body' && wp_is_mobile() ) ){
		$hook = 'osc_body_end';
	}
	elseif( $location === 'body_start' ){
		$hook = 'osc_body_start';
	}
	elseif( $location === 'footer_end' ){
		$hook = 'osc_footer_end';
	}
	elseif( $location === 'footer_start' || $location === 'mobile_body' ){
		$hook = 'osc_footer_start';
	}
	else{
		return;
	}

	add_action( $hook, 'osc_display_infobox' );

}
add_action( 'osc_header_start', 'osc_add_infobox_hook' );

?>