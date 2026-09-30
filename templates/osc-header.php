<?php
/**
 * Side Cart Header
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/osc-header.php.
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

extract( OSC_Template_Args::cart_header() );

$headingHTML = $basketHTML = $saveHTML = $closeHTML = '';

?>


<?php ob_start(); ?>

<?php if( $showBasket ): ?>

<div class="osch-basket">

	<?php if( $customBasketIcon ): ?>
		<span class="osch-bki"><img src="<?php echo esc_url($customBasketIcon) ?>"></span>
	<?php else: ?>
		<span class="osch-bki <?php echo esc_html($basketIcon) ?> osch-icon"></span>
	<?php endif; ?>

	<span class="osch-items-count"><?php echo osc_cart()->get_cart_count() ?></span>
</div>
<?php endif; ?>

<?php $basketHTML = ob_get_clean(); ?>



<?php ob_start(); ?>

<?php if( $heading ): ?>
	<span class="osch-text"><?php echo $heading ?></span>
<?php endif; ?>

<?php $headingHTML = ob_get_clean(); ?>




<?php ob_start(); ?>

<?php if( $saveforLaterEnabled ): ?>
	<div class="osc-tooltip-cont">
		<div class="osch-savelater osc-has-tooltip osc-toggle-slider" data-slider="savelater">
			<?php if( !OSC_Template_Args::$saveForLaterNeedsLogin ): ?>
				<span class="osch-save-count"><?php echo osc_cart()->get_saved_items_count() ?></span>
			<?php endif; ?>
			<span class="osch-save-icon <?php echo $saveForLaterIcon ?> osch-icon"></span>
		</div>
		<span class="osc-tooltip"><?php echo $savedForLaterHeading; ?></span>
	</div>
<?php endif; ?>

<?php $saveHTML = ob_get_clean(); ?>



<?php ob_start(); ?>

<?php if( $showCloseIcon ): ?>
	<span class="osch-close <?php echo  $close_icon ?> osch-icon"></span>
<?php endif; ?>
<?php $closeHTML = ob_get_clean(); ?>


<div class="osch-top">

	
	<?php osc_cart()->print_notices_html(); ?>
		

	<?php foreach ( $headerLayout as $section => $elements ): ?>
		
		<div class="osch-section osch-sec-<?php echo $section ?>">
			<?php foreach ( $elements as $element ){
				echo ${$element.'HTML'};
			}
			?>
		</div>

	<?php endforeach; ?>
	

</div>