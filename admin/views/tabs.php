<?php

$tabs = array(
	
	'general' => array(
		'title'			=> 'General',
		'id' 			=> 'general',
		'option_key' 	=> 'xoo-wsc-gl-options',
		'icon' 			=> 'xoo-icon-setting',
	),

	'style' => array(
		'title'			=> 'Style',
		'id' 			=> 'style',
		'option_key' 	=> 'xoo-wsc-sy-options',
		'icon' 			=> 'xoo-icon-brush',
	),

	'rewards' => array(
		'title'			=> 'Rewards',
		'id' 			=> 'rewards',
		'option_key' 	=> 'xoo-wsc-rewards-options',
		'icon' 			=> 'xoo-icon-gift',
	),



	'advanced' => array(
		'title'			=> 'Advanced',
		'id' 			=> 'advanced',
		'option_key' 	=> 'xoo-wsc-av-options',
		'icon' 			=> 'xoo-icon-tune',
	),
);

return apply_filters( 'xoo_wsc_admin_settings_tabs', $tabs );