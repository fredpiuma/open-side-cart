<?php
/**
 * Markup Notice
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/markup-notice.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredericomdecastro/open-side-cart
 * @version 4.9
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

extract( OSC_Template_Args::markup_notice() );

if( !$showNotifications ) return;

?>

<div class="osc-markup-notices">
	<?php osc_cart()->print_notices_html(); ?>
</div>