<?php
/**
 * Shipping Bar
 *
 * This template can be overridden by copying it to yourtheme/templates/open-side-cart/global/bar.php.
 *
 * HOWEVER, on occasion we will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen.
 * @see     https://github.com/fredericomdecastro/open-side-cart
 * @version 4.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$pointsBarWidth = array();
foreach ( $points as $index => $point ){
    $pointsBarWidth[] = ($point['amount']/$amount)*100;
}

?>

<div id="<?php echo $id ?>" class="osc-bar-cont <?php echo $free ? 'osc-bar-reached' : '' ?>" >
  
    <div class="osc-bar">
        <span style="width: <?php esc_attr_e( $fill_percentage ); ?>%; background-image: url(<?php echo OSC_URL.'/assets/images/bar.png' ?>)" data-points="<?php  echo json_encode($pointsBarWidth)  ?>"></span>
    </div>

   
    <div class="oscb-points-flags">
        <?php foreach ( $points as $index => $point ): ?>
            <img class="oscbp-flag" src="<?php echo OSC_URL.'/assets/images/flag'.$index.'.png' ?>" style="left: <?php echo ($point['amount']/$amount)*100 ?>%">
        <?php endforeach; ?>
    </div>

     <div class="oscb-points">

         <?php foreach ( $points as $index => $point ): ?>
            <span class="oscbp-txt"><?php echo $point['text']; ?></span>
        <?php endforeach; ?>

    </div>

    <div class="osc-bar-text-cont">
        <?php foreach ( $points as $index => $point ): ?>
            <span class="oscbp-txt"><?php echo $point['remainingTxt']; ?></span>
        <?php endforeach; ?>
    </div>

</div>

<div id="emitter"></div>