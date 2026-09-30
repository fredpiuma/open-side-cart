<?php

$tabs = array(
	
	'general' => array(
		'title'			=> 'General',
		'id' 			=> 'general',
		'option_key' 	=> 'osc-gl-options',
		'icon' 			=> 'osc-fw-icon-setting',
	),

	'style' => array(
		'title'			=> 'Style',
		'id' 			=> 'style',
		'option_key' 	=> 'osc-sy-options',
		'icon' 			=> 'osc-fw-icon-brush',
	),

	'rewards' => array(
		'title'			=> 'Rewards',
		'id' 			=> 'rewards',
		'option_key' 	=> 'osc-rewards-options',
		'icon' 			=> 'osc-fw-icon-gift',
	),



	'advanced' => array(
		'title'			=> 'Advanced',
		'id' 			=> 'advanced',
		'option_key' 	=> 'osc-av-options',
		'icon' 			=> 'osc-fw-icon-tune',
	),
);

return apply_filters( 'osc_admin_settings_tabs', $tabs );