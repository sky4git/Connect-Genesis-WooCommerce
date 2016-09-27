<?php
namespace GenWoo;
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.developersq.com
 * @since      1.0.0
 *
 * @package    Genesis_Woocommerce
 * @subpackage Genesis_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Genesis_Woocommerce
 * @subpackage Genesis_Woocommerce/admin
 * @author     Aakash Dodiya <hello@developersq.com>
 */
class Genesis_Woocommerce_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $genesis_woocoomerce    The ID of this plugin.
	 */
	private $genesis_woocoomerce;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $genesis_woocoomerce       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $genesis_woocoomerce, $version ) {

		$this->genesis_woocoomerce = $genesis_woocoomerce;
		$this->version = $version;
		//$this->load_dependencies();
	}

	/**
	 * Load the required dependencies for the admin section
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * This file require for all Display things
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'partials/genesis-woocoomerce-admin-display.php';
	}	

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() { 
		/** @var \WP_Screen $screen */
    	$screen = get_current_screen(); 
		// load the styles for the current page only
		if ( 'woocommerce_page_genesis-woocommerce' === $screen->base ){ 
			wp_enqueue_style( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'css/genesis-woocoomerce-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'material', plugin_dir_url( __FILE__ ) . 'css/material.min.css', array(), $this->version, 'all' );
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		/** @var \WP_Screen $screen */
    	$screen = get_current_screen();
		// load the scripts for the current page only
		if ( 'woocommerce_page_genesis-woocommerce' === $screen->base ){
			wp_enqueue_script( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'js/genesis-woocoomerce-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'material', plugin_dir_url( __FILE__ ) . 'js/material.min.js', '', $this->version, false );		
		}
	}
	
	/**
	 * Add options page in admin.
	 *
	 * @since    1.0.0
	 * @update   2.1
	 */
	public function genwoo_create_menu() {

		/**
		 * This function creates plguin's setting menu page in admin - under woocommerce
		 */
		add_submenu_page( 'woocommerce', 'Customizer', 'Customizer', 'manage_options', 'genesis-woocommerce', array( $this, 'genwoo_settings_page' ) );

	}
	
	public function genwoo_settings_page() { 
		?>
		<form action='options.php' method='post' class='form-area'>
			
			<?php
			settings_fields( 'genwoo_settings' );
			do_settings_sections( 'genwoo_settings' );
			submit_button();
			?>
			
		</form>
		<?php	
		$this->genwoo_misc_section();		
	} 
	
	public function genwoo_settings_init(  ) { 

		register_setting( 'genwoo_settings', 'genwoo_settings', array($this, 'genwoo_validate_inputs'));
		
		//*------------------------- GENERAL SECTION ----------------------------------*//
		add_settings_section(
			'genwoo_general_section', 
			__( 'Customize WooCommerce settings', 'genesis-woocommerce' ), 
			array($this, 'genwoo_settings_general_section_callback'), 
			'genwoo_settings'
		);		
		//* Declare woocoomerce support
		add_settings_field( 
			'genwoo_checkbox_declare_woo_support', 
			__( 'Declare woocommerce Support', 'genesis-woocommerce' ), 
			array($this, 'genwoo_checkbox_declare_woo_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);	
		// Enable Genesis posttype support for products
		add_settings_field( 
			'genwoo_checkbox_genesis_layout_support', 
			__( 'Enable Genesis layout support for products (Genesis Theme)', 'genesis-woocommerce' ), 
			array($this, 'genwoo_checkbox_genesis_layout_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);		
		// Enable Genesis SEO support for products
		add_settings_field( 
			'genwoo_checkbox_genesis_seo_support', 
			__( 'Enable Genesis seo support for products (Genesis Theme)', 'genesis-woocommerce' ), 
			array($this, 'genwoo_checkbox_genesis_seo_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);		
		// Remove woocommerce sidebar
		add_settings_field( 
			'genwoo_remove_sidebar', 
			__( 'Remove woocommerce sidebar', 'genesis-woocommerce' ), 
			array($this, 'genwoo_remove_sidebar_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);
		// Remove woocommerce wrapper
		add_settings_field( 
			'genwoo_remove_wrapper', 
			__( 'Remove woocommerce wrapper before & after main content', 'genesis-woocommerce' ), 
			array($this, 'genwoo_remove_wrapper_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);
		// Remove woocommerce menu
		add_settings_field( 
			'genwoo_remove_woo_bc', 
			__( 'Remove woocommerce breadcrumbs', 'genesis-woocommerce' ), 
			array($this, 'genwoo_remove_woo_bc_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);
		// return to shop url
		add_settings_field( 
			'genwoo_return_to_shop_url', 
			__( 'Return to Shop url', 'genesis-woocommerce' ), 
			array($this, 'genwoo_return_to_shop_url_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);
		// continue shopping url
		add_settings_field( 
			'genwoo_continue_shopping_url', 
			__( 'Continue shopping url', 'genesis-woocommerce' ), 
			array($this, 'genwoo_continue_shopping_url_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);

		
		//*--------------------------- WOOCOMMERCE SINGLE PRODUCT SECTION --------------------------*//
		add_settings_section(
			'genwoo_woo_product_section', 
			__( 'Single Product Settings', 'genesis-woocommerce' ), 
			array($this, 'genwoo_settings_woo_product_section_callback'), 
			'genwoo_settings'
		);
		// Single Product breadcrumbs 
		add_settings_field( 
			'genwoo_single_product_bc', 
			__( 'Modify single product Genesis breadcrumbs (Genesis Theme)', 'genesis-woocommerce' ), 
			array($this, 'genwoo_single_product_bc_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Single product sku hide
		add_settings_field( 
			'genwoo_single_product_hide_sku', 
			__( 'Hide SKU in product page', 'genesis-woocommerce' ), 
			array($this, 'genwoo_single_product_hide_sku_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Description tab heading
		add_settings_field( 
			'genwoo_description_tab_heading', 
			__( 'Product description tab heading', 'genesis-woocommerce' ), 
			array($this, 'genwoo_description_tab_heading_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Additional information tab heading
		add_settings_field( 
			'genwoo_addinfo_tab_heading', 
			__( 'Additional information tab heading', 'genesis-woocommerce' ), 
			array($this, 'genwoo_addinfo_tab_heading_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Review tab heading
		add_settings_field( 
			'genwoo_review_tab_heading', 
			__( 'Review tab heading', 'genesis-woocommerce' ), 
			array($this, 'genwoo_review_tab_heading_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Hide Description tab
		add_settings_field( 
			'genwoo_hide_description_tab', 
			__( 'Hide description tab', 'genesis-woocommerce' ), 
			array($this, 'genwoo_hide_description_tab_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Hide additional information tab
		add_settings_field( 
			'genwoo_hide_additional_information_tab', 
			__( 'Hide additional information tab', 'genesis-woocommerce' ), 
			array($this, 'genwoo_hide_additional_information_tab_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Hide review tab
		add_settings_field( 
			'genwoo_hide_review_tab', 
			__( 'Hide review tab', 'genesis-woocommerce' ), 
			array($this, 'genwoo_hide_review_tab_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		// Change Add to cart button text for single product
		add_settings_field( 
			'genwoo_add_to_cart_text_single', 
			__( 'Change Add to cart text for product page', 'genesis-woocommerce' ), 
			array($this, 'genwoo_add_to_cart_text_single_render'), 
			'genwoo_settings', 
			'genwoo_woo_product_section' 
		);
		//*--------------------------- WOOCOMMERCE SHOP PAGE SECTION --------------------------*//
		add_settings_section(
			'genwoo_woo_shop_section', 
			__( 'Shop page settings', 'genesis-woocommerce' ), 
			array($this, 'genwoo_settings_woo_shop_page_section_callback'), 
			'genwoo_settings'
		);
		// Shop page breadcrumbs - Modify shop page/product category breadcrumb Genesis way 
		add_settings_field( 
			'genwoo_shop_page_bc', 
			__( 'Modify Shop page Genesis breadcrumbs (Genesis Theme)', 'genesis-woocommerce' ), 
			array($this, 'genwoo_shop_page_bc_render'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);		
		// Remove Result count text
		add_settings_field( 
			'genwoo_remove_result_count', 
			__( 'Remove result count text', 'genesis-woocommerce' ), 
			array($this, 'genwoo_remove_result_count_render'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Hide shop page title
		add_settings_field( 
			'genwoo_hide_shop_page_title', 
			__( 'Hide shop page title', 'genesis-woocommerce' ), 
			array($this, 'genwoo_hide_shop_title_render'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Hide sorting dropdown
		add_settings_field( 
			'genwoo_hide_shop_dropdown', 
			__( 'Remove sorting dropdown from shop page', 'genesis-woocommerce' ), 
			array($this, 'genwoo_hide_shop_dropdown_render'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Number of products in shop per row
		add_settings_field( 
			'genwoo_shop_row_products', 
			__( 'Number of products in a row', 'genesis-woocommerce' ), 
			array($this, 'genwoo_shop_row_products_render'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Change Add to cart button text for product archive - SIMPLE product
		add_settings_field( 
			'genwoo_add_to_cart_text_archive_simple', 
			__( 'Change Add to cart text for simple product', 'genesis-woocommerce' ), 
			array($this, 'genwoo_add_to_cart_text_archive_render_simple'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Change Add to cart button text for product archive - EXTERNAL product
		add_settings_field( 
			'genwoo_add_to_cart_text_archive_external', 
			__( 'Change Add to cart text for external product', 'genesis-woocommerce' ), 
			array($this, 'genwoo_add_to_cart_text_archive_render_external'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Change Add to cart button text for product archive - GROUPED product
		add_settings_field( 
			'genwoo_add_to_cart_text_archive_grouped', 
			__( 'Change Add to cart text for grouped product', 'genesis-woocommerce' ), 
			array($this, 'genwoo_add_to_cart_text_archive_render_grouped'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
		// Change Add to cart button text for product archive - VARIABLE product
		add_settings_field( 
			'genwoo_add_to_cart_text_archive_variable', 
			__( 'Change Add to cart text for variable product', 'genesis-woocommerce' ), 
			array($this, 'genwoo_add_to_cart_text_archive_render_variable'), 
			'genwoo_settings', 
			'genwoo_woo_shop_section' 
		);
				
	}

	// Declare woocommerce support checkbox
	function genwoo_checkbox_declare_woo_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_checkbox_declare_woo_support]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_checkbox_declare_woo_support]' name='genwoo_settings[genwoo_checkbox_declare_woo_support]' <?php (isset($options['genwoo_checkbox_declare_woo_support']) ? checked( $options['genwoo_checkbox_declare_woo_support'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// Enable genesis layout support for products 
	function genwoo_checkbox_genesis_layout_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_checkbox_genesis_layout_support]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_checkbox_genesis_layout_support]' name='genwoo_settings[genwoo_checkbox_genesis_layout_support]' <?php (isset($options['genwoo_checkbox_genesis_layout_support']) ? checked( $options['genwoo_checkbox_genesis_layout_support'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// Enable genesis seo support for products 
	function genwoo_checkbox_genesis_seo_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_checkbox_genesis_seo_support]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_checkbox_genesis_seo_support]' name='genwoo_settings[genwoo_checkbox_genesis_seo_support]' <?php (isset($options['genwoo_checkbox_genesis_seo_support']) ? checked( $options['genwoo_checkbox_genesis_seo_support'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>		
		<?php
	}
	
	// Remove wooocommerce sidebar render
	function genwoo_remove_sidebar_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_remove_sidebar]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_remove_sidebar]' name='genwoo_settings[genwoo_remove_sidebar]' <?php (isset($options['genwoo_remove_sidebar']) ? checked( $options['genwoo_remove_sidebar'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>		
		<?php
	}
	
	// Remove woocommerce wrapper
	function genwoo_remove_wrapper_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_remove_wrapper]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_remove_wrapper]' name='genwoo_settings[genwoo_remove_wrapper]' <?php (isset($options['genwoo_remove_wrapper']) ? checked( $options['genwoo_remove_wrapper'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>		
		<?php
	}
	
	// Remove woocommerce breadcrumbs
	function genwoo_remove_woo_bc_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_remove_woo_bc]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_remove_woo_bc]' name='genwoo_settings[genwoo_remove_woo_bc]' <?php (isset($options['genwoo_remove_woo_bc']) ? checked( $options['genwoo_remove_woo_bc'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>		
		<?php
	}

	// Return to shop button url
	function genwoo_return_to_shop_url_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_return_to_shop_url]' name='genwoo_settings[genwoo_return_to_shop_url]'  value='<?php echo (isset($options['genwoo_return_to_shop_url']) ?  $options['genwoo_return_to_shop_url'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_return_to_shop_url]">Return to shop button url...</label>
		</div>	
		<?php
	}

	// continue shopping button url
	function genwoo_continue_shopping_url_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_continue_shopping_url]' name='genwoo_settings[genwoo_continue_shopping_url]'  value='<?php echo (isset($options['genwoo_continue_shopping_url']) ?  $options['genwoo_continue_shopping_url'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_continue_shopping_url]">Continue shopping button url...</label>
		</div>	
		<?php
	}

	// Single product breadcrumb modify
	function genwoo_single_product_bc_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_single_product_bc]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_single_product_bc]' name='genwoo_settings[genwoo_single_product_bc]' <?php (isset($options['genwoo_single_product_bc']) ? checked( $options['genwoo_single_product_bc'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>
		<?php	
	}
	
	// Product shop page section breadcrumbs
	function genwoo_shop_page_bc_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_shop_page_bc]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_shop_page_bc]' name='genwoo_settings[genwoo_shop_page_bc]' <?php (isset($options['genwoo_shop_page_bc']) ? checked( $options['genwoo_shop_page_bc'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// Remove result count text
	function genwoo_remove_result_count_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_remove_result_count]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_remove_result_count]' name='genwoo_settings[genwoo_remove_result_count]' <?php (isset($options['genwoo_remove_result_count']) ? checked( $options['genwoo_remove_result_count'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// hide page title
	function genwoo_hide_shop_title_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_hide_shop_title]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_hide_shop_title]' name='genwoo_settings[genwoo_hide_shop_title]' <?php (isset($options['genwoo_hide_shop_title']) ? checked( $options['genwoo_hide_shop_title'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// hide sorting dropdown
	function genwoo_hide_shop_dropdown_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_hide_shop_dropdown]">
			<input type='checkbox' class="mdl-switch__input" id='genwoo_settings[genwoo_hide_shop_dropdown]' name='genwoo_settings[genwoo_hide_shop_dropdown]' <?php (isset($options['genwoo_hide_shop_dropdown']) ? checked( $options['genwoo_hide_shop_dropdown'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// number of products per row
	function genwoo_shop_row_products_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='number' class="mdl-textfield__input" id='genwoo_settings[genwoo_shop_row_products]' name='genwoo_settings[genwoo_shop_row_products]' value='<?php echo (isset($options['genwoo_shop_row_products']) ? $options['genwoo_shop_row_products'] : 4 ); ?>' min="1" max="10" >
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_shop_row_products]">Number...</label>
		</div>
		<?php
	}
	
	// hide sku in single product
	function genwoo_single_product_hide_sku_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_single_product_hide_sku]">
			<input type='checkbox' class="mdl-switch__input" id='genwoo_settings[genwoo_single_product_hide_sku]' name='genwoo_settings[genwoo_single_product_hide_sku]' <?php (isset($options['genwoo_single_product_hide_sku']) ? checked( $options['genwoo_single_product_hide_sku'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// product description tab heading
	function genwoo_description_tab_heading_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_description_tab_heading]' name='genwoo_settings[genwoo_description_tab_heading]'  value='<?php echo (isset($options['genwoo_description_tab_heading']) ?  $options['genwoo_description_tab_heading'] : 'Product Description'); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_description_tab_heading]">Product Description...</label>
		</div>	
		<?php
	}

	// additional information tab heading
	function genwoo_addinfo_tab_heading_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_addinfo_tab_heading]' name='genwoo_settings[genwoo_addinfo_tab_heading]'  value='<?php echo (isset($options['genwoo_addinfo_tab_heading']) ?  $options['genwoo_addinfo_tab_heading'] : 'Additional Information'); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_addinfo_tab_heading]">Additional Information</label>
		</div>	
		<?php
	}

	// review tab heading
	function genwoo_review_tab_heading_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_review_tab_heading]' name='genwoo_settings[genwoo_review_tab_heading]'  value='<?php echo (isset($options['genwoo_review_tab_heading']) ?  $options['genwoo_review_tab_heading'] : 'Reviews'); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_review_tab_heading]">Reviews</label>
		</div>	
		<?php
	}
	// hide product description tab
	function genwoo_hide_description_tab_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_hide_description_tab]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_hide_description_tab]' name='genwoo_settings[genwoo_hide_description_tab]' <?php (isset($options['genwoo_hide_description_tab']) ? checked( $options['genwoo_hide_description_tab'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// hide product additional information tab
	function genwoo_hide_additional_information_tab_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_hide_additional_information_tab]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_hide_additional_information_tab]' name='genwoo_settings[genwoo_hide_additional_information_tab]' <?php (isset($options['genwoo_hide_additional_information_tab']) ? checked( $options['genwoo_hide_additional_information_tab'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}
	
	// hide product review tab
	function genwoo_hide_review_tab_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<label class="mdl-switch mdl-js-switch mdl-js-ripple-effect" for="genwoo_settings[genwoo_hide_review_tab]">
			<input type='checkbox' class='mdl-switch__input' id='genwoo_settings[genwoo_hide_review_tab]' name='genwoo_settings[genwoo_hide_review_tab]' <?php (isset($options['genwoo_hide_review_tab']) ? checked( $options['genwoo_hide_review_tab'], 1 ) : ''); ?> value='1'>
			<span class="mdl-switch__label"></span>
		</label>	
		<?php
	}

	// Change Add to cart text - single product
	function genwoo_add_to_cart_text_single_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_add_to_cart_text_single]' name='genwoo_settings[genwoo_add_to_cart_text_single]'  value='<?php echo (isset($options['genwoo_add_to_cart_text_single']) ?  $options['genwoo_add_to_cart_text_single'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_add_to_cart_text_single]">Add to cart</label>
		</div>		
		<?php	
	}

	// Change Add to cart text - archive product - SIMPLE
	function genwoo_add_to_cart_text_archive_render_simple(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_add_to_cart_text_archive_simple]' name='genwoo_settings[genwoo_add_to_cart_text_archive_simple]'  value='<?php echo (isset($options['genwoo_add_to_cart_text_archive_simple']) ?  $options['genwoo_add_to_cart_text_archive_simple'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_add_to_cart_text_archive_simple]">Add to cart</label>
		</div>		
		<?php	
	}

	// Change Add to cart text - archive product - EXTERNAL
	function genwoo_add_to_cart_text_archive_render_external(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_add_to_cart_text_archive_external]' name='genwoo_settings[genwoo_add_to_cart_text_archive_external]'  value='<?php echo (isset($options['genwoo_add_to_cart_text_archive_external']) ?  $options['genwoo_add_to_cart_text_archive_external'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_add_to_cart_text_archive_external]">Add to cart</label>
		</div>		
		<?php	
	}
	
	// Change Add to cart text - archive product - GROUPED
	function genwoo_add_to_cart_text_archive_render_grouped(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_add_to_cart_text_archive_grouped]' name='genwoo_settings[genwoo_add_to_cart_text_archive_grouped]'  value='<?php echo (isset($options['genwoo_add_to_cart_text_archive_grouped']) ?  $options['genwoo_add_to_cart_text_archive_grouped'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_add_to_cart_text_archive_grouped]">Add to cart</label>
		</div>		
		<?php	
	}

	// Change Add to cart text - archive product - VARIABLE
	function genwoo_add_to_cart_text_archive_render_variable(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_add_to_cart_text_archive_variable]' name='genwoo_settings[genwoo_add_to_cart_text_archive_variable]'  value='<?php echo (isset($options['genwoo_add_to_cart_text_archive_variable']) ?  $options['genwoo_add_to_cart_text_archive_variable'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_add_to_cart_text_archive_variable]">Add to cart</label>
		</div>		
		<?php	
	}
	
	/**
	* This function is just a simple call back function for General section for our setting
	*
	* @since	1.0.0
	*/
	public function genwoo_settings_general_section_callback() { 
		echo __( 'Please select the things you need.', 'genesis-woocommerce' );
	}
	
	/**
	* This function is just a simple call back function for Single product section for our setting
	*
	* @since	1.0.0
	*/
	function genwoo_settings_woo_product_section_callback(){
		echo __( 'Single Product Breadcrumbs settings.', 'genesis-woocommerce' );
	} 
	
	/**
	* This function is just simple call back function for shop page section
	*
	* @since 1.0.0
	*/
	function genwoo_settings_woo_shop_page_section_callback(){
		echo __( 'Shop page settings.', 'genesis-woocommerce' );
	}
	

	/**
	* This function wil Sanitize all data
	* Thanks to http://code.tutsplus.com/tutorials/the-complete-guide-to-the-wordpress-settings-api-part-7-validation-sanitisation-and-input-i--wp-25289
	* 
	* @since 	1.0.0
	*/	
	public function genwoo_validate_inputs( $input ) {
 
	    // Create our array for storing the validated options
	    $output = array();
	     
	    // Loop through each of the incoming options
	    foreach( $input as $key => $value ) {
	         
	        // Check to see if the current option has a value. If so, process it.
	        if( isset( $input[$key] ) ) {
	         
	            // Strip all HTML and PHP tags and properly handle quoted strings
	            $output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
	             
	        } // end if
	         
	    } // end foreach
	     
	    // Return the array processing any additional functions filtered by this action
	    return apply_filters( 'genwoo_validate_inputs', $output, $input );
 
	}
	
	/**
	* Misc section
	* @since 1.0.0
	* @update 2.1
	*/
	private function genwoo_misc_section(){ 
		?>
			<div class='linkdiv'>
				<a href='https://www.developersq.com/?utm_source=connect-genesis-woocommerce&utm_medium=banner&utm_campaign=plugin' target='_blank' class='banner1'>
					
				</a>
				<hr />
				<a href='https://www.developersq.com/learn-reactjs-basics/?utm_source=connect-genesis-woocommerce&utm_medium=banner&utm_campaign=plugin' target='_blank' class='banner2'>
					
				</a>
			</div>
		<?php	
	}
	

}

?>