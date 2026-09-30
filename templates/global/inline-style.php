<?php

/* Main */
$cartWidth 		= (int) $sy['scm-width'];
$cartheight 	= $sy['scm-height'];
$openFrom 		= $sy['scm-open-from'];
$fontFamily 	= $sy['scm-font'];

/* Basket */
$minBasketMob 	= $sy['sck-mob-size'];
$basketSize 	= $sy['sck-bk-size'];
$BasketMobile 	= $sy['sck-show-mobile'];
$showBasket 	= $sy['sck-enable'];
$basketPosition = $sy['sck-position'];
$basketShape 	= $sy['sck-shape'];
$basketIconSize = $sy['sck-size'];
$basketOffset 	= $sy['sck-offset'];
$basketHOffset 	= $sy['sck-hoffset'];
$countPosition 	= $sy['sck-count-pos'];
$basketBG 		= $sy['sck-basket-bg'];
$basketColor 	= $sy['sck-basket-color'];
$basketShadow 	= $sy['sck-basket-sh'];
$countBG 		= $sy['sck-count-bg'];
$countColor 	= $sy['sck-count-color'];


/* Header */
$headerIconSize 	= $sy['sch-close-fsize'];
$headFontSize 	= $sy['sch-head-fsize'];
$headBGColor 	= $sy['sch-bgcolor'];
$headTxtColor 	= $sy['sch-txtcolor'];
$headBorder		= $sy['sch-border'];
$headPadding 	= $sy['sch-padding'];

/* Body */
$bodyFontSize 	= $sy['scb-fsize'];
$bodyBGColor 	= $sy['scb-bgcolor'];
$bodyTxtColor 	= $sy['scb-txtcolor'];
$bodyIconSize 	= $sy['scb-icon-size'];

/* Product Card */
$bpCardCount 		= (int) $sy['scbp-card-count'];
$bpCardImgColor 	= $sy['scbp-card-img-color'];
$bpCardFrtColor 	= $sy['scbp-card-front-color'];
$bpCardBckColor 	= $sy['scbp-card-back-color'];
$bpCardBckTxtColor 	= $sy['scbp-card-backtxt-color'];
$bpCardTxtColor 	= $sy['scbp-card-txtcolor'];
$bpCardBorder 		= $sy['scbp-card-border'];
$bPCardimgwidth		= (int) $sy['scbp-card-imgw'];
$bPCardimgheight	= (int) $sy['scbp-card-imgh'];
$bPCardpadding 		= $sy['scbp-card-padding'];
$bpCardAnimTim 		= $sy['scbp-card-anim-time'];
$bPCardShadow		= $sy['scbp-card-shadow'];
$bPCardRadTop		= $sy['scbp-card-radius-top'];
$bPCardRadBtm		= $sy['scbp-card-radius-btm'];


/*Product Row */
$bpVarFormat 	= $sy['scbp-var-format'];
$bpDisplay 		= $sy['scbp-display'];
$bPpadding 		= $sy['scbp-padding'];
$bPimgwidth		= (int) $sy['scbp-imgw'];
$bPmargin		= $sy['scbp-margin'];
$bPradius		= (int) $sy['scbp-bradius'];
$bPshadow		= $sy['scbp-shadow'];
$bpBgColor		= $sy['scbp-bgcolor'];
$bpSalesColor 	= $sy['scbp-sales-bgcolor'];
$bpSalestxtColor = $sy['scbp-sales-txtcolor'];
$bpSalesBorder 	= $sy['scbp-sales-border'];

/* Quantity */
$qtyStyle 		= $sy['scbq-style'];
$qtyWidth 		= $sy['scbq-width'];
$qtybtnsize 	= $sy['scbq-btnsize'];
$qtyHeight 		= $sy['scbq-height'];
$qtyBorsize 	= $sy['scbq-bsize'];
$inputBorColor 	= $sy['scbq-input-bcolor'];
$btnBorColor 	= $sy['scbq-box-bcolor'];
$inputBgColor 	= $sy['scbq-input-bgcolor'];
$inputTxtColor 	= $sy['scbq-input-txtcolor'];
$btnBgColor 	= $sy['scbq-box-bgcolor'];
$btnTxtColor 	= $sy['scbq-box-txtcolor'];

/* Footer */
$new_btn_layout = !isset( $sy['scf-btn-newlayout'] ) || $sy['scf-btn-newlayout'] === "yes";
$footerStick 	= $sy['scf-stick'];
$buttonsOrder  	= $sy['scf-button-pos'];
$buttonRows 	= $sy['scf-btns-row'];


$ftrPadding 	= $sy['scf-padding'];
$ftrBgColor 	= $sy['scf-bgcolor'];
$ftrTxtColor 	= $sy['scf-txtcolor'];
$ftrFsize 		= $sy['scf-fsize'];
$ftrShadow 		= $sy['scf-shadow'];

/* Suggested Products */
$spImgWidth 	= (int) $sy['scsp-imgw'];
$spFontSize 	= (int) $sy['scsp-fsize'];
$spBGColor 		= $sy['scsp-bgcolor'];
$spPrdBGColor 	= $sy['scsp-prd-bgcolor'];
$drawerWidth 	= $sy['scs-drawer-width'];
$spColCount 	= (int) ( $sy['scsp-col-items'] ? $sy['scsp-col-items'] : 1 );

/* Suggested Products */
$savlImgWidth 	= (int) $sy['sl-imgw'];
$savlFontSize 	= (int) $sy['sl-fsize'];
$savlBGColor 	= $sy['sl-bgcolor'];
$savlTxtColor  	= $sy['sl-prd-txtcolor'];
$savlPrdBGColor = $sy['sl-prd-bgcolor'];
$savlColCount 	= (int) ( $sy['sl-col-items'] ? $sy['sl-col-items'] : 1 );



/* Shortcode */
$SCbasketSize 	= $sy['shbk-size'];
$SCbasketColor 	= $sy['shbk-color'];
$SCcountBG 		= $sy['shbk-count-bg'];
$SCcountColor 	= $sy['shbk-count-color'];
$SCtxtColor 	= $sy['shbk-txt-color'];

if( $buttonRows === 'three' ){
	$gridCols = '1fr 1fr 1fr';
}
elseif ( $buttonRows === 'two_one' ) {
	$gridCols = '2fr 2fr';
	echo 'a.osc-ft-btn:nth-child(3){
		grid-column: 1/-1;
	}';
}
elseif ( $buttonRows === 'one_two' ) {
	$gridCols = '2fr 2fr';
	echo 'a.osc-ft-btn:nth-child(1){
		grid-column: 1/-1;
	}';
}
else{
	$gridCols = 'auto';
}


$buttonThemeSelectorMap = array(
	'scm-btntheme-empty' 	=> '.osc-empty-cart a.osc-btn',
	'scm-btntheme-sp' 		=> 'span.osc-sp-atc a.button',
	'scm-btntheme-coupon' 	=> '.osc-sl-content.osc-sl-coupon .osc-btn',
	'scm-btntheme-ship' 	=> '.osc-slider button[name="calc_shipping"]',
	'scm-btntheme-save' 	=> '.osc-savl-atc',
);

if( $new_btn_layout ){
	$buttonThemeSelectorMap = array_merge( $buttonThemeSelectorMap, array(
		'scm-btntheme-cart'     => 'a.osc-ft-btn.osc-ft-btn-cart',
		'scm-btntheme-checkout' => 'a.osc-ft-btn.osc-ft-btn-checkout',
		'scm-btntheme-continue' => 'a.osc-ft-btn.osc-ft-btn-continue',
	) );
}

$buttonThemes = $sy['scm-btnthemes'];

osc_helper()->print_button_themed_css( $buttonThemeSelectorMap, $sy, $buttonThemes );

$bannerThemeSelectorMap = array(
	'scm-btntheme-tooltip'  => '.osc-tooltip',
	'scm-btntheme-gift'  	=> '.osc-gift-ban',
);

osc_helper()->print_button_themed_css( $bannerThemeSelectorMap, $sy, $buttonThemes, false );

if( isset( $buttonThemes[ $sy['scm-btntheme-checkout'] ] ) ){
	$checkoutButtonTheme = $buttonThemes[ $sy['scm-btntheme-checkout'] ];
	echo 'a.osc-ft-btn.osc-ft-btn-checkout .amount{
		color: '.$checkoutButtonTheme['txtColor'].';
	}';
	echo 'a.osc-ft-btn.osc-ft-btn-checkout:hover .amount{
		color: '.$checkoutButtonTheme['hover']['txtColor'].';
	}';
}



if( !$new_btn_layout ){

	$buttonPadding 		= $sy['scf-btn-padding'];
	$buttonTheme 		= $sy['scf-btns-theme'];
	$buttonbgColor 		= $sy['scf-btn-bgcolor'];
	$buttontxtColor 	= $sy['scf-btn-txtcolor'];
	$buttonBorder 		= $sy['scf-btn-border'];
	$HVbuttonbgColor 	= $sy['scf-btnhv-bgcolor'];
	$HVbuttontxtColor 	= $sy['scf-btnhv-txtcolor'];
	$HVbuttonBorder 	= $sy['scf-btnhv-border'];


	if( $buttonTheme === 'custom' ) :?>

		.osc-ft-buttons-cont a.osc-ft-btn, .osc-container .osc-btn {
			background-color: <?php echo $buttonbgColor ?>;
			color: <?php echo $buttontxtColor ?>;
			border: <?php echo $buttonBorder ?>;
			padding: <?php echo $buttonPadding ?>;
		}

		.osc-ft-buttons-cont a.osc-ft-btn:hover, .osc-container .osc-btn:hover {
			background-color: <?php echo $HVbuttonbgColor ?>;
			color: <?php echo $HVbuttontxtColor ?>;
			border: <?php echo $HVbuttonBorder ?>;
		}

		.osc-btn .amount{
			color: <?php echo $buttontxtColor ?>
		}

		.osc-btn:hover .amount{
			color: <?php echo $HVbuttontxtColor ?>;
		}

	<?php endif; ?>


	<?php

} 

?>

.osc-sp-left-col img, .osc-sp-left-col{
	max-width: <?php echo $spImgWidth ?>px;
}

.osc-sp-right-col{
	font-size: <?php echo $spFontSize ?>px;
}

.osc-sp-container, .osc-dr-sp{
	background-color: <?php echo $spBGColor ?>;
}



.osc-footer{
	background-color: <?php echo $ftrBgColor ?>;
	color: <?php echo $ftrTxtColor ?>;
	padding: <?php echo $ftrPadding ?>;
	box-shadow: <?php echo $ftrShadow ?>;
}

.osc-footer, .osc-footer a, .osc-footer .amount{
	font-size: <?php echo $ftrFsize ?>px;
}

.osc-ft-buttons-cont{
	grid-template-columns: <?php echo $gridCols ?>;
}

.osc-basket{
	<?php echo $basketPosition ?>: <?php echo $basketOffset ?>px;
	<?php echo $openFrom ?>: <?php echo $basketHOffset ?>px;
	background-color: <?php echo $basketBG ?>;
	color: <?php echo $basketColor ?>;
	box-shadow: <?php echo $basketShadow ?>;
	border-radius: <?php echo $basketShape === 'round' ? '50%' : '14px' ?>;
	display: <?php echo $showBasket === 'hide_empty' || $showBasket === 'always_hide' ? 'none' : 'flex'; ?>;
	width: <?php echo $basketSize; ?>px;
	height: <?php echo $basketSize; ?>px;
}

<?php if( $BasketMobile !== 'yes' ): ?>

@media only screen and (max-width: 600px) {
	.osc-basket, .osc-basket[style*='block']  {
		display: none!important;
	}
}

<?php endif; ?>

.osc-bki{
	font-size: <?php echo $basketIconSize.'px' ?>
}

.osc-items-count{
	<?php echo $countPosition === 'top_right' || $countPosition === 'top_left' ? 'top' : 'bottom' ?>: -10px;
	<?php echo $countPosition === 'top_right' || $countPosition === 'bottom_right' ? 'right' : 'left' ?>: -10px;
}

.osc-items-count, .osch-items-count, .osch-save-count{
	background-color: <?php echo $countBG ?>;
	color: <?php echo $countColor ?>;
}

.osc-container, .osc-slider, .osc-drawer{
	max-width: <?php echo $cartWidth ?>px;
	<?php echo $openFrom ?>: <?php echo -$cartWidth ?>px;
	<?php echo $cartheight === 'full' ? 'top: 0;bottom: 0' : 'max-height: 100vh' ?>;
	<?php echo $basketPosition ?>: 0;
	font-family: <?php echo $fontFamily; ?>
}

.osc-drawer{
	max-width: <?php echo $drawerWidth; ?>px;
}

.osc-cart-active .osc-container, .osc-slider-active .osc-slider{
	<?php echo $openFrom ?>: 0;
}

.osc-drawer-active .osc-drawer{
	<?php echo $openFrom ?>: <?php echo $cartWidth ?>px;
}
.osc-drawer{
	<?php echo $openFrom ?>: 0;
}

<?php if( $footerStick !== 'yes' ): ?>

.osc-container {
    overflow: auto;
}

.osc-body{
	overflow: unset;
	flex-grow: 0;
}
.osc-footer{
	flex-grow: 1;
}

<?php endif; ?>

.osc-cart-active .osc-basket{
	<?php echo $openFrom ?>: <?php echo $cartWidth ?>px;
}

span.osch-icon{
	font-size: <?php echo $headerIconSize ?>px;
}


.osch-text, .osc-sl-heading, .osc-drawer-header{
	font-size: <?php echo $headFontSize ?>px;
}

.osc-header, .osc-drawer-header, .osc-sl-heading{
	color: <?php echo $headTxtColor ?>;
	background-color: <?php echo $headBGColor ?>;
	border-bottom: <?php echo $headBorder ?>;
	padding: <?php echo $headPadding ?>;
}


.osc-body{
	background-color: <?php echo $bodyBGColor ?>;
}

.osc-body, .osc-body span.amount, .osc-body a{
	font-size: <?php echo $bodyFontSize ?>px;
	color: <?php echo $bodyTxtColor ?>;
}

.osc-product, .osc-sp-product, .osc-savl-product{
	padding: <?php echo $bPpadding ?>;
	margin: <?php echo $bPmargin ?>;
	border-radius: <?php echo $bPradius ?>px;
	box-shadow: <?php echo $bPshadow ?>;
	background-color: <?php echo $bpBgColor ?>;
}

.osc-body .osc-ft-totals{
	padding: <?php echo $bPpadding ?>;
	margin: <?php echo $bPmargin ?>;
}

.osc-product-cont{
	padding: <?php echo $bPCardpadding ?>;
}

.osc-products:not(.osc-pattern-card) .osc-img-col{
	width: <?php echo $bPimgwidth ?>%;
}

.osc-pattern-card .osc-img-col img{
	max-width: <?php echo $bPCardimgwidth ?>%;
	height: <?php echo ($bPCardimgheight > 0 ? $bPCardimgheight.'px' : 'auto') ?>;
}

.osc-products:not(.osc-pattern-card) .osc-sum-col{
	width: <?php echo 100-$bPimgwidth ?>%;
}

.osc-pattern-card .osc-product-cont{
	width: <?php echo 100/$bpCardCount ?>% 
}

<?php if( $bpCardCount > 1 ): ?>
@media only screen and (max-width: 600px) {
	.osc-pattern-card .osc-product-cont  {
		width: 50%;
	}
}

<?php endif; ?>

.osc-pattern-card .osc-product{
	border: <?php echo $bpCardBorder ?>;
	box-shadow: <?php echo $bPCardShadow ?>;
}

<?php if( $bPCardimgwidth < 100 ): ?>
.osc-pattern-card .osc-img-col{
	background-color: <?php echo $bpCardImgColor ?>;
}
<?php endif; ?>

.osc-sm-front, .osc-card-actionbar > *{
	background-color: <?php echo $bpCardFrtColor ?>;
}
.osc-pattern-card, .osc-sm-front{
	border-bottom-left-radius: <?php echo $bPCardRadBtm; ?>px;
	border-bottom-right-radius: <?php echo $bPCardRadBtm; ?>px;
}
.osc-pattern-card, .osc-img-col img, .osc-img-col, .osc-sm-back-cont{
	border-top-left-radius: <?php echo $bPCardRadTop; ?>px;
	border-top-right-radius: <?php echo $bPCardRadTop; ?>px;
}
.osc-sm-back{
	background-color: <?php echo $bpCardBckColor ?>;
}
.osc-pattern-card, .osc-pattern-card a, .osc-pattern-card .amount{
	font-size: <?php echo $bodyFontSize ?>px;
}

.osc-body .osc-sm-front, .osc-body .osc-sm-front a, .osc-body .osc-sm-front .amount, .osc-card-actionbar{
	color: <?php echo $bpCardTxtColor ?>;
}

.osc-sm-back, .osc-sm-back a, .osc-sm-back .amount{
	color: <?php echo $bpCardBckTxtColor ?>;
}


.magictime {
    animation-duration: <?php echo $bpCardAnimTim ?>s;
}

<?php if( wp_is_mobile() && $sy['scb-playout'] === 'cards' && $sy['scbp-card-visible'] === 'back_hover' ) :?>
.osc-img-col a{
	pointer-events: none;
}
<?php endif; ?>


<?php if( $bpDisplay === 'stretched' ): ?>
.osc-sm-info{
	flex-grow: 1;
    align-self: stretch;
}

.osc-sm-left{
	justify-content: space-evenly;
}

<?php else: ?>
.osc-sum-col{
	justify-content: <?php echo $bpDisplay ?>;
}
<?php endif; ?>

/***** Quantity *****/

.osc-qty-box{
	max-width: <?php echo $qtyWidth ?>px;
}

.osc-qty-box.osc-qtb-square{
	border-color: <?php echo $btnBorColor ?>;
}

input[type="number"].osc-qty{
	border-color: <?php echo $inputBorColor ?>;
	background-color: <?php echo $inputBgColor ?>;
	color: <?php echo $inputTxtColor ?>;
	height: <?php echo $qtyHeight ?>px;
	line-height: <?php echo $qtyHeight ?>px;
}

input[type="number"].osc-qty, .osc-qtb-square{
	border-width: <?php echo $qtyBorsize ?>px;
	border-style: solid;
}
.osc-chng{
	background-color: <?php echo $btnBgColor ?>;
	color: <?php echo $btnTxtColor ?>;
	width: <?php echo $qtybtnsize ?>px;
}

.osc-qtb-circle .osc-chng{
	height: <?php echo $qtybtnsize ?>px;
	line-height: <?php echo $qtybtnsize ?>px;
}

/** Shortcode **/
.osc-sc-count{
	background-color: <?php echo $SCcountBG ?>;
	color: <?php echo $SCcountColor ?>;
}

.osc-sc-bki{
	font-size: <?php echo $SCbasketSize ?>px;
	color: <?php echo $SCbasketColor ?>;
}
.osc-sc-cont{
	color: <?php echo $SCtxtColor ?>;
}

.osc-sp-column li.osc-sp-prod-cont{
	width: <?php echo (100/$spColCount) ?>%;
}





<?php if( $gl['m-viewcart-del'] === 'yes' ): ?>
.added_to_cart{
	display: none!important;
}
<?php endif; ?>


span.osc-dtg-icon{
	<?php echo $openFrom ?>: calc(100% - 11px );
}


.osc-sp-product{
	background-color: <?php echo $spPrdBGColor ?>;
}



<?php if( $minBasketMob === 'yes' ): ?>

@media only screen and (max-width: 600px) {
	.osc-basket {
	    width: 40px;
	    height: 40px;
	}

	.osc-bki {
	    font-size: 20px;
	}

	span.osc-items-count {
	    width: 17px;
	    height: 17px;
	    line-height: 17px;
	    top: -7px;
	    left: -7px;
	}
}


<?php endif; ?>

.osc-markup dl.variation {
	display: <?php echo $bpVarFormat === 'one_line' ? 'flex' : 'block' ?>;
}


.osc-sl-savelater .osc-sl-body {
	background-color: <?php echo $savlBGColor ?>;
}

.osc-savl-left-col img, .osc-savl-left-col{
	max-width: <?php echo $savlImgWidth ?>px;
}

.osc-savl-column li.osc-savl-prod-cont{
	width: <?php echo (100/$savlColCount) ?>%;
}

.osc-savl-product{
	background-color: <?php echo $savlPrdBGColor ?>;
}

.osc-savl-column .osc-savl-prod-cont{
	width: <?php echo (100/$savlColCount) ?>%;
}


.osc-savl-right-col, .osc-savl-right-col .amount, .osc-savl-right-col a {
	font-size: <?php echo $savlFontSize ?>px;
	color: <?php echo $savlTxtColor ?>;
}

<?php if( $gl['m-tooltip'] !== 'yes' ): ?>
.osc-tooltip{
	display: none!important;
}
<?php endif; ?>




.osc-save, .osc-smr-del{
	font-size: <?php echo $bodyIconSize; ?>px
}

.osc-sm-sales{
	background-color: <?php echo $bpSalesColor ?>;
	color: <?php echo $bpSalestxtColor ?>;
	border: <?php echo $bpSalesBorder ?>;
}

<?php echo osc_bars()->bars_css(); ?>

<?php 

if( WC()->cart->get_cart_contents_count() === 0 ){

	$shortcodeEls = osc_frontend()->shortcodeEls;

	$hideEls = array();

	foreach ($gl['shbk-hide'] as $scElement ) {
		if( isset( $shortcodeEls[ $scElement ] ) ){
			$hideEls[] = $shortcodeEls[ $scElement ];
		}
	}

	if( !empty( $hideEls ) ){
		?>
		<?php echo implode(',', $hideEls ); ?>{
			display: none;
		}
		<?php
	}
}

?>

<?php if( $sy['scm-info-loc'] === "body_end_stick" ): ?>

.osc-body{
	display: flex;
	flex-direction: column;
}

.osc-body .osc-info-cont{
	margin-top: auto;
	margin-bottom: 5px;
}

<?php endif; ?>