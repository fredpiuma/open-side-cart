<?php
	
$options = apply_filters( 'osc_fw_aff_export_options', $adminObj->tabs, $adminObj->helper->slug );
$sections = $adminObj->sections;


?>

<div class="osc-fw-as-container">
	<div class="osc-fw-settings-container">

		<ul class="osc-fw-sc-tabs">
			<?php foreach( $tabs as $tab_id => $tab_data ): ?>
				<li data-tab="<?php echo esc_attr( $tab_id ); ?>" <?php if( $tab_data['pro'] === 'yes' ) echo 'class="osc-fw-as-is-pro"'; ?>><?php echo esc_html( $tab_data['title'] ); ?></li>
			<?php endforeach; ?>
		</ul>

		<form class="osc-fw-as-form">

			<?php foreach( $tabs as $tab_id => $tab_data ): ?>
				<div class="osc-fw-sc-tab-content <?php if( $tab_data['pro'] === 'yes' ) echo 'osc-fw-as-is-pro'; ?>" data-tab="<?php echo esc_attr( $tab_id ); ?>">

					<?php
					if( isset( $sections[ $tab_id ] ) && count($sections[ $tab_id ]) > 3 ){
						echo '<div class="osc-fw-sc-sections">';
						foreach ( $sections[$tab_id] as $section_id => $section_data ) {
							$ispro = ( isset( $section_data['pro'] ) && $section_data['pro'] === 'yes' ) ? 'class="osc-sec-pro"' : '';
							echo '<a href="#'.$tab_id.'_'.$section_id.'" '.$ispro.'>'.$section_data['title'].'</a>';
						}
						echo '</div>';
					}

					?>

					<?php do_action( 'osc_fw_tab_page_start', $tab_id, $tab_data ); ?>
					<?php $adminObj->create_settings_html( $tab_id ); ?>
					<?php do_action( 'osc_fw_tab_page_end', $tab_id, $tab_data ); ?>
				</div>
			<?php endforeach; ?>

			<div class="osc-fw-sc-bottom-btns">
				<?php if( $hasPRO ): ?>
					<a class="osc-fw-as-pro-toggle">Show Pro options</a>
					<a class="osc-fw-as-pro-toggle osc-fw-aspt-two">Hide Pro options</a>
				<?php endif; ?>
				<button type="submit" class="osc-fw-as-form-save">Save</button>
				<a class="osc-fw-as-form-reset" href="<?php echo esc_url( add_query_arg( 'reset', wp_create_nonce('reset') ) ) ?>">Reset</a>
				<div class="osc-fw-as-exim">
					<div class="osc-fw-as-eximbtns" style="display: none;">
						<span class="osc-fw-as-setexport" >Export Settings</span>
						<span class="osc-fw-as-setimport">Import Settings</span>
					</div>
					<span class="dashicons dashicons-move"></span>
				</div>
			</div>

		</form>

		<div class="osc-fw-as-modal">

			<div class="osc-fw-as-expimmodal">
				
				<div class="osc-fw-as-emod-cont">

					<span class="osc-fw-as-exipclose">X</span>

					<div class="osc-fw-as-excont">

						<div class="osc-fw-as-exoptions">

							<span>Export settings</span>

							<div class="osc-fw-as-expcheck">
								<?php

								foreach ( $options as $id => $data ) {
									if( !$data['option_key'] ) continue;
									?>
									<label>
										<?php esc_html_e( $data['title'] ); ?>
										<input type="checkbox" value="<?php echo esc_attr( $data['option_key'] ) ?>" checked>
									</label>
									<?php
								}

								?>
							</div>

							<i>Any unsaved changed will not be exported. Please make sure to save settings.</i>
						

							<button class="osc-fw-as-run-export">Export</button>

						</div>

						<div class="osc-fw-as-expdone">

							<b>Copy the value below and paste it into the 'Import settings' feature on the website you want to import it to</b>
							<i>Files/Image upload settings need to be done manually.</i>

							<textarea rows="10"></textarea>

						</div>
					</div>

					<div class="osc-fw-as-impcont">
						<b>Paste the copied value here.</b>
						<i>Files/Image upload settings need to be done manually.</i>
						<textarea rows="10"></textarea>
						<button class="osc-fw-as-run-import">Run Import</button>
						<span class="osc-fw-as-imported">Import Completed. Refreshing....</span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php if( $hasSidebar ): ?>
		<div class="osc-fw-as-sidebar">
			<span class="dashicons dashicons-admin-collapse osc-fw-as-sbar-close"></span>
			<div class="osc-fw-as-sidebar-content">
				<?php do_action( 'osc_fw_as_setting_sidebar_'.$adminObj->helper->slug ); ?>
			</div>
		</div>
	<?php endif; ?>

</div>