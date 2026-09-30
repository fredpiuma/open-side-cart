<?php

class OSC_Admin_Settings{

	protected static $_instance = null;

	public $installedPlugins = array();

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){

		$this->hooks();

	}

	public function hooks(){

		if( current_user_can( 'manage_options' ) ){
			add_action( 'init', array( $this, 'generate_settings' ), 0 );
			add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		}
		
		add_action( 'osc_fw_tab_page_start', array( $this, 'info_tab_html' ), 10, 2 );
		add_filter( 'plugin_action_links_' . OSC_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );

		if( osc_helper()->admin->is_settings_page() ){

			add_action( 'osc_fw_as_enqueue_scripts', array( $this, 'enqueue_custom_scripts' ) );

			add_action( 'admin_footer', array( $this, 'sidecart_preview' ) );

			add_action( 'osc_fw_tab_page_start', array( $this, 'preview_info' ), 5 );


			add_action( 'osc_fw_admin_setting_field_callback_html', array( $this, 'pattern_selector_field' ), 10, 4 );

			add_action( 'osc_fw_admin_setting_field_callback_html', array( $this, 'header_layout_setting_html' ), 10, 4 );


			if( get_option('osc-pattern-init') === false ){
				add_action( 'osc_fw_tab_page_end', array( $this, 'popup_pattern_selector' ), 10, 2 );
				add_filter('admin_body_class', array( $this, 'admin_body_class') );
			}

			add_action( 'osc_fw_tab_page_start', array( $this, 'rewards_html' ) );
			add_action( 'admin_footer', array( $this, 'rewards_preview' ) );

		}
	
		if( get_option('osc-pattern-init') === false ){
			add_action( 'osc_fw_admin_settings_open-side-cart_saved', array( $this, 'popup_initialised' ) );
		}		

		add_action( 'update_option_osc-gl-options', array( $this, 'register_checkpoint_strings_translation' ) );

		if( isset( $_GET['page'] ) && $_GET['page'] === 'mlang_strings' ){
			add_action( 'init', array( $this, 'register_checkpoint_strings_translation' ) );
		}

		add_action( 'wp_ajax_osc_el_install', array( $this, 'install_loginpopup' ) );
		add_action( 'wp_ajax_osc_el_request_just_to_init_save_settings',  array( $this, 'el_request_just_to_init_save_settings' ) );

		add_action( 'wp_ajax_osc_product_search_fill_defaults', array( $this, 'product_search_fill_defaults' ) );
		
		add_filter( 'osc_admin_settings', array( $this, 'filter_settings' ), 10, 2 );
		
	}


	public function filter_settings( $settings, $type ){
	
		if( $type === 'style' && get_option( 'osc-had-old-btn-layout',true ) !== "yes" ){
			foreach  ($settings as $index => $setting ) {
				if( in_array( $setting['id'], array( 'scf-btns-theme', 'scf-btn-border', 'scf-btn-bgcolor', 'scf-btn-txtcolor', 'sscf-btnhv-border', 'scf-btnhv-bgcolor', 'scf-btnhv-txtcolor', 'scf-btn-newlayout' ) ) ){
					unset( $settings[$index] );
				}
			}
		}

		return $settings;
	}

	public function product_search_fill_defaults(){

		if ( !wp_verify_nonce( $_POST['osc_nonce'], 'osc-nonce' ) ) {
			die('cheating');
		}

		$product_ids = $_POST['product_ids'];

		if( !$product_ids || empty( $product_ids ) ) return;

		$optionsHTML = '';

		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( is_object( $product ) ) {
				$optionsHTML .= '<option value="' . esc_attr( $product_id ) . '"' . selected( true, true, false ) . '>' . esc_html( wp_strip_all_tags( $product->get_formatted_name() ) ) . '</option>';
			}
		}

		echo $optionsHTML;
		
		die();

	}

	public function rewards_preview(){
		if( !osc_helper()->admin->is_settings_page() ) return;
		$base_id = 'osc-rewards-options[bars][%$]';
		include OSC_PATH.'/admin/templates/rewards/progressbar.php';
		include OSC_PATH.'/admin/templates/rewards/checkpoint.php';
	}

	public function rewards_html($tab_id){
		if( $tab_id !== 'rewards' ) return;
		include OSC_PATH.'/admin/templates/osc-rewards.php';
	}


	public function register_checkpoint_strings_translation(){

		$bars = osc_helper()->get_rewards_option('bars');
		$bars = !is_array( $bars ) ? array() : $bars;
		
		foreach ( $bars as $index => $bar ) {

			osc_helper()->register_string_for_translation( $bar['settings']['comptxt'], $bar['settings']['barTitle'].' | Completed Text' );

			if( isset($bar['checkpoints'] ) && !empty( $bar['checkpoints'] ) ){

				$points = $bar['checkpoints'];

				foreach ( $points as $point_index => $point) {
					osc_helper()->register_string_for_translation( $point['title'], $bar['settings']['barTitle'].' | checkpoint-'.$point_index.'-title' );
					osc_helper()->register_string_for_translation( $point['remaining'], $bar['settings']['barTitle'].' | checkpoint-'.$point_index.'-remaining' );	
				}

			}
		}


	}


	public function is_plugin_installed( $plugin_slug ){

		if( isset( $this->installedPlugins[$plugin_slug] ) ){
			return $this->installedPlugins[$plugin_slug];
		}

		$installed = false;

		// Load the necessary WordPress plugin functions
		if (!function_exists('get_plugins')) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		// Get the list of all installed plugins
		$all_plugins = get_plugins();

		// Check if the plugin is in the list of installed plugins
		foreach ($all_plugins as $plugin_path => $plugin_data) {
			if (strpos($plugin_path, $plugin_slug . '/') === 0) {
				$installed = true; // Plugin is installed
				break;
			}
		}


		$this->installedPlugins[$plugin_slug] = $installed;

		return $installed;

	}


	public function preview_info($tab_id){
		if( !osc_helper()->admin->is_settings_page() || $tab_id === 'pro' || $tab_id === 'info' || $tab_id === 'rewards' ) return;
		?>
		<div class="osc-fw-as-preview-info"><span class="dashicons dashicons-laptop"></span> Updates live in customizer</div>
		<?php
	}

	public function sidecart_preview(){
		if( !osc_helper()->admin->is_settings_page() ) return;
		osc_helper()->get_template( 'osc-preview.php', array(), OSC_PATH.'/admin/templates/preview/' );
	}



	public function enqueue_custom_scripts( $slug ){

		if( $slug !== 'open-side-cart' ) return;

		wp_enqueue_style( 'osc-fw-aff-fa', OSC_URL.'/library/fontawesome5/css/all.min.css' ); //Font Awesome
		wp_enqueue_style( 'osc-fw-aff-fa-picker', OSC_URL.'/library/fontawesome-iconpicker/dist/css/fontawesome-iconpicker.min.css', array(), '1.0', 'all' ); //Font Awesome Icon Picker
		wp_enqueue_script( 'osc-fw-aff-fa-pickers', OSC_URL.'/library/fontawesome-iconpicker/dist/js/fontawesome-iconpicker.js', array( 'jquery'), '1.0', false );

		wp_enqueue_script('jquery-ui-sortable');

		wp_enqueue_style( 'osc-magic', OSC_URL.'/library/magic/dist/magic.min.css', array(), '1.0' );
		wp_enqueue_script( 'masonry-js', OSC_URL.'/library/masonry/masonry.js', array(), OSC_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
	


		wp_enqueue_script('wc-enhanced-select'); // For product search

		wp_enqueue_style( 'osc-admin-fonts', OSC_URL . '/assets/css/osc-fonts.css', array(), OSC_VERSION );
		wp_enqueue_style( 'osc-admin-style', OSC_URL . '/admin/assets/osc-admin-style.css', array(), OSC_VERSION );
		wp_enqueue_script( 'osc-admin-js', OSC_URL . '/admin/assets/osc-admin-js.js', array( 'jquery' ), OSC_VERSION, true );

		wp_localize_script( 'osc-admin-js', 'osc_admin_params', array(
			'adminurl'  => admin_url().'admin-ajax.php',
			'nonce' 	=> wp_create_nonce('osc-nonce'),
			'isMobile' 	=> wp_is_mobile() ? 'yes' : 'no',
			'bars' 		 => osc_helper()->get_rewards_option('bars'),
			'barDefaults' => osc_bars()->get_bar_defaults()
		) );
	}


	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' 	=> '<a href="' . admin_url( 'admin.php?page=open-side-cart-settings' ) . '">Settings</a>',
			'support' 	=> '<a href="https://github.com/fredpiuma/open-side-cart/issues" target="__blank">Support</a>',
		);

		return array_merge( $action_links, $links );
	}


	public function el_request_just_to_init_save_settings(){
		wp_die();
	}


	public function install_loginpopup(){

		// Check for nonce security      
		if ( !wp_verify_nonce( $_POST['osc_nonce'], 'osc-nonce' ) ) {
			die('cheating');
		}

		try {

			$plugin_slug = 'easy-login-woocommerce';

			include_once ABSPATH . 'wp-admin/includes/file.php';
			include_once ABSPATH . 'wp-admin/includes/misc.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/plugin.php';

			if( !$this->is_plugin_installed( 'easy-login-woocommerce' ) ){

				// Initialize the WP Filesystem
				if (false === WP_Filesystem()) {
					throw new Exception( "Could not initialize WP_Filesystem.", 'filesystem_error' ) ;
				}

				// Set the plugin URL from the WordPress repository
				$plugin_zip_url = "https://downloads.wordpress.org/plugin/{$plugin_slug}.latest-stable.zip";

				// Download the plugin ZIP file
				$download_result = download_url($plugin_zip_url);

				if (is_wp_error($download_result)) {
					throw new OSC_FW_Exception( $download_result );
				}

				// Prepare for installation
				$skin 				= new Automatic_Upgrader_Skin();
				$plugin_upgrader 	= new Plugin_Upgrader($skin);
				$install_result 	= $plugin_upgrader->install($plugin_zip_url);

				// Clean up the downloaded file
				unlink($download_result);

				// Return the result of the installation
				if (is_wp_error($install_result)) {
					throw new OSC_FW_Exception( $install_result );
				}

				//Default setting when installed using side cart
				if( get_option( 'xoo-el-version' ) === false ){

					$firsttime_download = 'yes';

					update_option( 'xoo-el-sy-options', array(
						'sy-popup-style' 	=> 'slider',
						'sy-popup-width' 	=> 500,
					) );
					
					update_option( 'xoo-el-gl-options', array(
						'm-form-pattern' 	=> 'single',
						'm-nav-pattern'  	=> 'links',
						'ao-enable' 		=> 'no'
					) );

					update_option('xoo-el-settings-init', 'yes');

				}
				

			}

			// Activate the plugin after installation
			$activate_result = activate_plugin($plugin_slug . '/xoo-el-main.php');

			if (is_wp_error($activate_result)) {
				throw new OSC_FW_Exception( $activate_result );
			}

			wp_send_json( array(
				'notice' 				=> 'Plugin installed successfully.<br>For now everything is already setup, but if you want to customize the settings, you can access them <a href="'.admin_url( 'admin.php?page=easy-login-woocommerce-settings' ).'" target="_blank">[here]</a>',
				'firsttime_download' 	=> isset( $firsttime_download ) ? 'yes' : 'no'
			) );

		} catch (OSC_FW_Exception $e) {
			wp_send_json( array(
				'error' 	=> 'yes',
				'notice' 	=> $e->getMessage()
			) );
		}

		
	}



	public function generate_settings(){
		osc_helper()->admin->auto_generate_settings();
	}


	public function add_menu_pages(){

		$args = array(
			'menu_title' 	=> 'Side Cart',
			'icon' 			=> 'dashicons-cart',
		);

		osc_helper()->admin->register_menu_page( $args );

	}


	public function info_tab_html( $tab_id, $tab_data ){
		if( $tab_id !== 'info' || !osc_helper()->admin->is_settings_page() ) return;
		osc_helper()->get_template( 'osc-tab-info.php', array(), OSC_PATH.'/admin/templates/' );
	}


	public function popup_pattern_selector( $tab_id, $tab_data ){
		if( $tab_id !== 'general' ) return;
		?>
		<div class="osc-admin-popup">
			<div class="osc-adpop">
				<?php $this->pattern_selector_field_html(); ?>
				<span class="osc-adpopup-head">Choose your Product Layout</span>
				<span>You can change this later from "Style -> Side Cart Body"</span>
				<button type="button" class="osc-adpopup-go button-primary button">Let's Go!</button>
			</div>
			<div class="osc-adpop-opac"></div>
		</div>
		<?php
	}


	public function pattern_selector_field_html(){
		?>
		<div class="osc-pattern-cont">
			<img class="osc-patimg" src="<?php echo OSC_URL; ?>/admin/assets/images/pattern-card.jpg ?>" data-pattern="cards">
			<img class="osc-patimg" src="<?php echo OSC_URL; ?>/admin/assets/images/pattern-row.jpg ?>" data-pattern="rows" >
		</div>
		<?php
	}

	public function admin_body_class( $classes ){
		$classes .= ' osc-adpopup-active';
		return $classes;
	}

	public function popup_initialised(){
		update_option( 'osc-pattern-init', 'yes' );
	}

	public function pattern_selector_field( $field, $field_id, $value, $args ){
		if( $field_id !== 'osc-sy-options[scb-playout]' ) return $field;
		ob_start();
		$this->pattern_selector_field_html();
		$customField = ob_get_clean();
		return $field. $customField;
	}


	public function header_layout_setting_html( $field, $field_id, $value, $args ){

		if( $field_id !== 'osc-sy-options[sch-layout]' ) return $field;

		$defaults = array(
			'left' 		=> array( 'basket', 'heading' ),
			'center' 	=> array(),
			'right'		=> array( 'save', 'close' ),
		);

		if( !$value || empty($value) ){
			$value = $defaults;
		}
		else{
			$defaults = array_map(function() {
			    return array();
			}, $defaults);
			
			$value = osc_fw_recursive_parse_args( $value, $defaults );
		}

		

		$html = array(
			'basket' 	=> '<span class="osc-icon-shopping-bag1 oschl-icon"></span>',
			'heading' 	=> 'Heading',
			'save' 		=> '<span class="osc-icon-heart1 oschl-icon"></span>',
			'close' 	=> '<span class="osc-icon-del1 oschl-icon"></span>',
		);

		ob_start();

		?>
		<div class="osch-layout-cont osc-fw-as-setting osc-fw-as-has-preview">

			<?php foreach ($value as $location => $elements ): ?>

				<div>
					<span><?php echo $location; ?></span>
					<ul id="oscH-<?php echo $location; ?>" class="oscHconnectedSortable" data-name="<?php echo $location; ?>">

						<?php foreach( $elements as $element ): ?>
							<li>
								<?php echo $html[ $element ] ?>
								<input type="hidden" name="osc-sy-options[sch-layout][<?php echo $location ?>][]" value="<?php echo $element ?>">
							</li>

						<?php endforeach; ?>
					</ul>
				</div>

			<?php endforeach; ?>

		</div>

		<?php

		return ob_get_clean();

	}

	public function bar_selectedoptions( $name, $options ){
		foreach ( $options as $option_value => $title) {
			?>
			<option value="<?php echo $option_value ?>" {{ data.<?php echo $name; ?> == '<?php echo $option_value ?>' ? 'selected' : '' }} ><?php echo $title ?></option>
			<?php
		}
	}

	public function default_info_text(){

		ob_start();

		?>
		<div style="padding:5px 20px;">
			<table style="margin:auto;border-collapse:collapse;border:none; width: 350px;  text-align: center; background-color: transparent;" border="0">
				<tr>
					<td align="center" style="padding:5px 12px;font-size:12px;border:none;text-align: center;background-color: transparent;">
						<div align="center" style="text-align:center;" a>
							<img src="<?php echo OSC_URL.'/admin/assets/images/info/secure.png'?>" style="width:30px;height:auto;" class="aligncenter">
						</div>
						<i>Secure Checkout</i>
					</td>
					<td align="center" style="padding:5px 12px;font-size:12px;border:none;text-align: center; background-color: transparent;">
						<div align="center" style="text-align:center;">
							<img src="<?php echo OSC_URL.'/admin/assets/images/info/shipping.png'?>" style="width:30px;height:auto;" class="aligncenter">
						</div>
						<i>Fast Shipping</i>
					</td>
					<td align="center" style="padding:5px 12px;font-size:12px;border:none;text-align: center;background-color: transparent;">
						<div align="center" style="text-align:center;">
							<img src="<?php echo OSC_URL.'/admin/assets/images/info/returns.png'?>" style="width:30px;height:auto;" class="aligncenter">
						</div>
						<i>Easy Returns</i>
					</td>
				</tr>
			</table>
		</div>

		<?php
		return ob_get_clean();
	}


}

function osc_admin_settings(){
	return OSC_Admin_Settings::get_instance();
}
osc_admin_settings();