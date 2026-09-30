<?php

$sections = array(


	/* General TAB Sections */

	array(
		'title' => 'Side Cart Header',
		'id' 	=> 'sc_head',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-header'
	),

	array(
		'title' => 'Side Cart Body',
		'id' 	=> 'sc_body',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-page'
	),


	array(
		'title' => 'Side Cart Footer',
		'id' 	=> 'sc_footer',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-footer'
	),

		array(
		'title' => 'Main',
		'id' 	=> 'main',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-header'
	),


	array(
		'title' => 'Cart Menu',
		'id' 	=> 'sh_bk',
		'tab' 	=> 'general',
		'desc' 	=> 'You can also use shortcode [osc_cart] to generate basket icon anywhere.',
		'icon' 	=> 'osc-fw-icon-code'
	),



	array(
		'title' => 'Texts',
		'id' 	=> 'texts',
		'tab' 	=> 'general',
		'desc' 	=> 'Leave text empty to remove element',
		'icon' 	=> 'osc-fw-icon-page'
	),



	array(
		'title' => 'URLs',
		'id' 	=> 'urls',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-link'
	),

	array(
		'title' => 'Basket',
		'id' 	=> 'basket',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-cart'
	),

	array(
		'title' => 'Suggested Products',
		'id' 	=> 'suggested_products',
		'tab' 	=> 'general',
		'icon' 	=> 'osc-fw-icon-header'
	),

	array(
		'title' => 'Save For Later',
		'id' 	=> 'save_for_later',
		'tab' 	=> 'general',
		'desc' 	=> 'Allow users to save items in their cart for later purchase.',
		'icon' 	=> 'osc-fw-icon-header'
	),


	/* Style TAB Sections */

	array(
		'title' => 'Button Themes',
		'id' 	=> 'sc_button_theme_creator',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-tune',
		'desc' 	=> 'Create and manage reusable button styles for side cart.'
	),

	array(
		'title' => 'Main',
		'id' 	=> 'sc_main',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-home'
	),

	array(
		'title' => 'Side Cart Basket',
		'id' 	=> 'sc_basket',
		'tab' 	=> 'style',
		'desc' 	=> 'You can also add basket to your menu bar using shortcode [osc_cart]. Please see info tab for more.',
		'icon' 	=> 'osc-fw-icon-cart'
	),

	array(
		'title' => 'Side Cart Header',
		'id' 	=> 'sc_head',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-header'
	),

	array(
		'title' => 'Side Cart Body',
		'id' 	=> 'sc_body',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-page'
	),

	array(
		'title' => 'Product - Row layout',
		'id' 	=> 'scb_product',
		'tab' 	=> 'style',
		'desc' 	=> osc_helper()->get_style_option('scbp-card-en') === 'yes' ? ' XX No effect XX. Please make sure CARD LAYOUT is disabled' : '',
		'icon' 	=> 'osc-fw-icon-header' 
	),

	array(
		'title' => '🏷️ Product - Card layout 🏷️ ',
		'id' 	=> 'scb_productcard',
		'tab' 	=> 'style',
		'desc' 	=> 'Show your product items as cards',
		'icon' 	=> 'osc-fw-icon-header'
	),

	array(
		'title' => 'Quantity Box',
		'id' 	=> 'scb_qty',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-header'
	),

	array(
		'title' => 'Side Cart Footer',
		'id' 	=> 'sc_footer',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-footer'
	),


	array(
		'title' => 'Suggested Products',
		'id' 	=> 'sc_sug_products',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-store'
	),


	array(
		'title' => 'Saved for Later',
		'id' 	=> 'saved_for_later',
		'tab' 	=> 'style',
		'icon' 	=> 'osc-fw-icon-heartplus'
	),


	array(
		'title' => 'Cart Menu / Shortcode',
		'id' 	=> 'sh_bk',
		'tab' 	=> 'style',
		'desc' 	=> 'Use shortcode [osc_cart] to generate basket icon anywhere.',
		'icon' 	=> 'osc-fw-icon-code'
	),


	/* Rewards TAB Sections */
	array(
		'title' => 'Global Settings',
		'id' 	=> 'general',
		'tab' 	=> 'rewards',
		'icon' 	=> 'osc-fw-icon-gift',
	),

	/* Custom CSS TAB Sections */
	array(
		'title' => 'Main',
		'id' 	=> 'av_main',
		'tab' 	=> 'advanced',
		'icon' 	=> 'osc-fw-icon-home'
	)
);

return apply_filters( 'osc_admin_settings_sections', $sections );