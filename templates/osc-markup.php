<?php
/**
 * Side Cart Markup
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-markup.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredpiuma/open-side-cart
 * @version 4.9
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$isAjax = defined('DOING_AJAX') && DOING_AJAX;

?>

<div class="osc-markup osc-align-<?php echo osc_helper()->get_style_option('scm-open-from'); ?>">

    <div class="osc-modal">

        <div class="osc-container">
    	   <?php if( $isAjax ) osc_helper()->get_template( 'osc-container.php' ); ?>
           <?php osc_helper()->get_template( 'global/preloader.php' ); ?>
        </div>

    	<span class="osc-opac"></span>

    </div>

    <div class="osc-slider-modal">

        <div class="osc-slider">
    	   <?php if( $isAjax ) osc_helper()->get_template( 'osc-slider.php' ); ?>
        </div>

    </div>

    <div class="osc-drawer-modal">

        <div class="osc-drawer">
            <?php if( $isAjax ) osc_helper()->get_template( 'osc-drawer.php' ); ?>
        </div>

    </div>
    
</div>