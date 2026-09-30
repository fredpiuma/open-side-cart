<script type="text/html" id="tmpl-osc-fw-as-btntheme">

	<?php $id = $field_id.'[{{data.theme_id}}]' ?>

	<?php

	$units = array(
		'px' => 'px',
		'%'  => '%',
		'em' => 'em',
		'rem'=> 'rem'
	);


	$fontWeight = array(
		'300' => 300,
		'400' => 400,
		'500' => 500,
		'600' => 600,
		'700' => 700,
	);

	$fontStyle = array(
		'normal' => 'Normal',
		'italic' => 'Italic',
	);

	$styles = array(
		'none'   => 'None',
		'hidden' => 'Hidden',
		'solid'  => 'Solid',
		'dashed' => 'Dashed',
		'dotted' => 'Dotted',
		'double' => 'Double',
		'groove' => 'Groove',
		'ridge'  => 'Ridge',
		'inset'  => 'Inset',
		'outset' => 'Outset',
	);
	?>

	<div class="osc-fw-btntheme osc-fw-accordion osc-fw-btn-setting" data-field_id="<?php echo $id ?>">

		<div class="osc-fw-acc-head osc-fw-theme-head">
	
			<div class="osc-fw-btntheme-title">{{data.title}}</div>
			<span class="dashicons dashicons-trash osc-fw-btntheme-delete"></span>

			<div class="osc-fw-btn-preview-wrap">

				<div class="osc-fw-btn-preview">
					<button type="button">Button</button>
				</div>

				<style id="{{data.theme_id}}"></style>

			</div>

			<div class="osc-fw-acc-ctas">
				<div class="osc-fw-acc-ctaedit"><span class="osc-fw-as-icon osc-fw-icon-edit"></span>Edit</div>
				<div class="osc-fw-acc-ctaclose osc-fw-acc-ctaedit"><span class="osc-fw-as-icon osc-fw-icon-close"></span>Close</div>
				<div class="osc-fw-acc-ctacopy"><span class="osc-fw-as-icon osc-fw-icon-copy"></span>Duplicate</div>
				<div class="osc-fw-acc-ctadel"><span class="osc-fw-as-icon osc-fw-icon-delete"></span></div>
			</div>
		</div>

		<div class="osc-fw-acc-cont">

			<div class="osc-fw-btntheme-head">
				<span class="osc-fw-btnset-desc">Customize the appearance of your button</span>
				<input type="text" value="{{data.title}}" name="<?php echo $id ?>[title]" class="osc-fw-btntheme-title-input">
			</div>

			<div class="osc-fw-tabs-cont">

				<input type="hidden" value="{{data.theme_id}}" name="<?php echo $id ?>[theme_id]" class="osc-fw-btntheme-id">

					<div class="osc-fw-setting-tabs">

						<span class="osc-fw-set-tab osc-fw-tabactive" data-xootab="normal"><span class="osc-fw-icon-light osc-fw-icon"></span>Normal</span>

						<span class="osc-fw-set-tab" data-xootab="hover"><span class="osc-fw-icon-cursor osc-fw-icon"></span>Hover</span>

					</div>

					<!-- NORMAL -->
					<div class="osc-fw-btn-group osc-fw-tabgroup osc-fw-tabactive" data-xootab="normal">

						<!-- Colors -->
						<div class="osc-fw-btn-row">

							<span class="osc-fw-btnrow-head"><span class="osc-fw-icon-paint osc-fw-icon"></span>Colors</span>

							<div class="osc-fw-row-settings">

								<div>
									<i>Background</i>
									<input type="text" class="osc-fw-as-color-input" name="<?php echo $id; ?>[bgColor]" value="{{data.bgColor}}" >
								</div>

								<div>
									<i>Text Color</i>
									<input type="text" class="osc-fw-as-color-input" name="<?php echo $id; ?>[txtColor]" value="{{data.txtColor}}" >
								</div>

							</div>

						</div>

						<!-- Size -->
						<div class="osc-fw-btn-row">

							<span class="osc-fw-btnrow-head"><span class="osc-fw-icon-ruler osc-fw-icon"></span>Size</span>

							<div class="osc-fw-row-settings">

								<div class="osc-fw-btnrowset-sizetype">

									<i>Size Type</i>

									<select name="<?php echo $id ?>[size_type]">
										<?php $adminObj->templatejs_select_options( 'size_type', array(
											'auto' 		=> 'Auto width & height',
											'custom' 	=> 'Custom Size'
										) ) ?>
									</select>

								</div>

								<div data-size_type="auto">

									<i>Padding ↨ </i>
									<input type="number" name="<?php echo $id; ?>[padding_v]" value="{{data.padding_v}}" >

								</div>

								<div data-size_type="auto">

									<i>Padding ⟷</i>
									<input type="number" name="<?php echo $id; ?>[padding_h]" value="{{data.padding_h}}" >

								</div>

								<div data-size_type="custom">

									<i>Custom Width</i>
									<input type="number" name="<?php echo $id; ?>[width]" value="{{data.width}}" >

								</div>

								<div data-size_type="custom">

									<i>Unit</i>

									<select name="<?php echo $id ?>[width_unit]">
										<?php $adminObj->templatejs_select_options( 'width_unit', $units ) ?>
									</select>

								</div>

								<div  data-size_type="custom">

									<i>Custom Height</i>
									<input type="number" name="<?php echo $id; ?>[height]" value="{{data.height}}" >	

								</div>



								<div data-size_type="custom">

									<i>Unit</i>
									<select name="<?php echo $id ?>[height_unit]">
										<?php $adminObj->templatejs_select_options( 'height_unit', $units ) ?>
									</select>

								</div>

							</div>

						</div>

						<!-- Text -->
						<div class="osc-fw-btn-row">

							<span class="osc-fw-btnrow-head"><span class="osc-fw-icon-font osc-fw-icon"></span>Text</span>

							<div class="osc-fw-row-settings">

								<div>

									<i>Weight</i>

									<select name="<?php echo $id ?>[text][fontWeight]">
										<?php $adminObj->templatejs_select_options( 'text.fontWeight', $fontWeight ) ?>
									</select>

								</div>

								<div>

									<i>Style</i>

									<select name="<?php echo $id ?>[text][fontStyle]">
										<?php $adminObj->templatejs_select_options( 'text.fontStyle', $fontStyle ) ?>
									</select>

								</div>

								<div>

									<i>Font Size</i>

									<input type="number" name="<?php echo $id; ?>[text][fontSize]" value="{{data.text.fontSize}}">

								</div>

								<div>

									<i>Unit</i>

									<select name="<?php echo $id ?>[text][fontSizeUnit]">
										<?php $adminObj->templatejs_select_options( 'text.fontSizeUnit', $units ) ?>
									</select>

								</div>

								<div>

									<i>Transform</i>

									<select name="<?php echo $id ?>[text][textTransform]">
										<?php $adminObj->templatejs_select_options( 'text.textTransform', array(
											'none' => 'None',
											'lowercase' => 'Lowercase',
											'uppercase' => 'Uppercase',
											'capitalize' => 'Capitalize'
										) ) ?>
									</select>

								</div>

							</div>

						</div>

						<!-- Border -->
						<div class="osc-fw-btn-row">

							<span class="osc-fw-btnrow-head"><span class="osc-fw-icon-border osc-fw-icon"></span>Border</span>
							
							<div class="osc-fw-row-settings">

								<div>
									<i>Size</i>
									<input name="<?php echo $id; ?>[border][size]" type="number" min="0" value="{{data.border.size}}">
								</div>

								<div>
									<i>Color</i>
									<input name="<?php echo $id; ?>[border][color]" type="text" class="osc-fw-as-color-input" value="{{data.border.color}}">
								</div>

								<div>

									<i>Style</i>
									<select name="<?php echo $id; ?>[border][style]">

										<?php $adminObj->templatejs_select_options( 'border.style', $styles ) ?>

									</select>


								</div>

								<div>
									<i>Radius</i>
									<input name="<?php echo $id; ?>[border][radius]" type="number" min="0" value="{{data.border.radius}}">
								</div>

							</div>
						</div>

					</div>

					<!-- HOVER -->
					<div class="osc-fw-btn-group osc-fw-tabgroup" data-xootab="hover">

						<div class="osc-fw-btn-row">

							<span class="osc-fw-btnrow-head"><span class="osc-fw-icon-paint osc-fw-icon"></span>Colors</span>

							<div class="osc-fw-row-settings">

								<div>
									<i>Background</i>
									<input type="text" class="osc-fw-as-color-input" name="<?php echo $id; ?>[hover][bgColor]" value="{{data.hover.bgColor}}">									
								</div>

								<div>
									<i>Text Color</i>
									<input type="text" class="osc-fw-as-color-input" name="<?php echo $id; ?>[hover][txtColor]" value="{{data.hover.txtColor}}" >
								</div>

							</div>

						</div>

						<div class="osc-fw-btn-row">

							<span class="osc-fw-btnrow-head"><span class="osc-fw-icon-border osc-fw-icon"></span>Border</span>
							
							<div class="osc-fw-row-settings">

								<div>
									<i>Size</i>
									<input name="<?php echo $id; ?>[hover][border][size]" type="number" min="0" value="{{data.hover.border.size}}">
								</div>

								<div>
									<i>Color</i>
									<input name="<?php echo $id; ?>[hover][border][color]" type="text" class="osc-fw-as-color-input" value="{{data.hover.border.color}}">
								</div>

								<div>

									<i>Style</i>
									<select name="<?php echo $id; ?>[hover][border][style]">

										<?php $adminObj->templatejs_select_options( 'hover.border.style', $styles ) ?>

									</select>


								</div>

								<div>
									<i>Radius</i>
									<input name="<?php echo $id; ?>[hover][border][radius]" type="number" min="0" value="{{data.hover.border.radius}}">
								</div>

							</div>
						</div>

					</div>

				</div>

		</div>

	</div>

</script>