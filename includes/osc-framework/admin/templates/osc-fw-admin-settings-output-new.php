<?php
	
$options = apply_filters( 'osc_fw_aff_export_options', $adminObj->tabs, $adminObj->helper->slug );
$sections = $adminObj->sections;


?>

<div class="osc-fw-as-container">


		
	<div class="osc-fw-as-topbar">

		<div class="osc-fw-as-tbar-second">

			<ul class="osc-fw-sc-tbar-tabs">

				<?php foreach( $tabs as $tab_id => $tab_data ): ?>

					<li data-tab="<?php echo esc_attr( $tab_id ); ?>" <?php if( $tab_data['pro'] === 'yes' ) echo 'class="osc-fw-as-is-pro"'; ?>>

						<?php if( isset( $tab_data['args']['icon'] ) ): ?>

							<span class="osc-fw-as-icon <?php echo esc_html( $tab_data['args']['icon'] ); ?>"></span>

						<?php endif; ?>

						<span><?php echo esc_html( $tab_data['title'] ); ?></span>

					</li>

				<?php endforeach; ?>

			</ul>

		</div>
	</div>



	<div class="osc-fw-settings-container">

		<div class="osc-fw-as-setsidebar">

			<div class="osc-fw-as-setsec-container">

				<?php foreach( $tabs as $tab_id => $tab_data ): ?>

					<?php if( !isset( $sections[ $tab_id ] ) ) continue; ?>
					
					<div class="osc-fw-as-setsections" data-tab="<?php echo $tab_id ?>">

						<?php foreach ( $sections[$tab_id] as $section_id => $section_data ): ?>

		
							<a href="#<?php echo $tab_id.'_'.$section_id ?>" class="osc-fw-as-setsbar-section <?php echo ( isset( $section_data['pro'] ) && $section_data['pro'] === 'yes' ) ? 'osc-fw-sec-pro' : '' ?>">

								<?php if( isset( $section_data['args']['icon'] ) ): ?>

									<span class="osc-fw-as-icon <?php echo esc_html( $section_data['args']['icon'] ); ?>"></span>

								<?php endif; ?>

								<span class="osc-fw-as-setbar-sectitle"><?php echo $section_data['title']; ?></span>

							</a>

						<?php endforeach; ?>

					</div>
				
				

				<?php endforeach; ?>

			</div>

			<div class="osc-fw-as-setbar-help">
				<div><span class="osc-fw-as-icon osc-fw-icon-help"></span>Need Help?</div>
				<span>Check our documentation or contact support.</span>
				<a href="https://github.com/fredericomdecastro/open-side-cart/issues" class="osc-fw-btn osc-fw-btn-secondary" target="__blank"><span class="osc-fw-as-icon osc-fw-icon-window"></span>Contact</a>
			</div>

		</div>

		<div class="osc-fw-as-setmain">
			<form class="osc-fw-as-form">

				<?php foreach( $tabs as $tab_id => $tab_data ): ?>
					<div class="osc-fw-sc-tab-content" data-tab="<?php echo esc_attr( $tab_id ); ?>">

						<?php do_action( 'osc_fw_tab_page_start', $tab_id, $tab_data ); ?>
						<?php $adminObj->create_settings_html( $tab_id ); ?>
						<?php do_action( 'osc_fw_tab_page_end', $tab_id, $tab_data ); ?>
					</div>
				<?php endforeach; ?>

				<div class="osc-fw-sc-bottom-btns">

					<?php if( $hasPRO ): ?>
						<a class="osc-fw-as-pro-toggle osc-fw-btn osc-fw-btn-primary "><span class="osc-fw-as-icon osc-fw-icon-crown"></span>Show Pro options</a>
						<a class="osc-fw-as-pro-toggle osc-fw-aspt-two osc-fw-btn osc-fw-btn-primary"><span class="osc-fw-as-icon osc-fw-icon-crown"></span>Hide Pro options</a>
					<?php endif; ?>

					

					<a class="osc-fw-as-form-reset osc-fw-btn osc-fw-btn-secondary" href="<?php echo esc_url( add_query_arg( 'reset', wp_create_nonce('reset') ) ) ?>"><span class="osc-fw-as-icon osc-fw-icon-refresh"></span>Reset</a>

					<div class="osc-fw-as-exim">
						<div class="osc-fw-as-eximbtns" style="display: none;">
							<span class="osc-fw-as-setexport" >Export Settings</span>
							<span class="osc-fw-as-setimport">Import Settings</span>
						</div>
						<button class="osc-fw-btn osc-fw-btn-secondary" type="button"><span class="osc-fw-as-icon osc-fw-icon-export"></span>Move Settings</button>
					</div>

					<button type="submit" class="osc-fw-as-form-save osc-fw-btn osc-fw-btn-primary"><span class="osc-fw-as-icon osc-fw-icon-save"></span>Save</button>

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