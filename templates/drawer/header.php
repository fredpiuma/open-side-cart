<?php
/**
 * Side Cart Drawer
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/drawer/header.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredpiuma/open-side-cart
 * @version 4.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::drawer_header() );

?>

<div class="osc-drawer-header">
	<span class="osc-drh-txt"><?php esc_html_e( $heading ) ?></span>
	<span class="osc-toggle-drawer oscdh-close osc-icon-arrow-thin-<?php echo $openDirection; ?>"></span>
</div>