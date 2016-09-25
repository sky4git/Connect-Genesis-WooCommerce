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
		add_submenu_page( 'woocommerce', 'WooCommerce + Genesis Settings', 'WooCommerce + Genesis Settings', 'manage_options', 'genesis-woocommerce', array( $this, 'genwoo_settings_page' ) );

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
			__( 'WooCommerce settings for your Genesis child theme', 'genesis-woocommerce' ), 
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
			__( 'Enable Genesis layout support for products', 'genesis-woocommerce' ), 
			array($this, 'genwoo_checkbox_genesis_layout_render'), 
			'genwoo_settings', 
			'genwoo_general_section' 
		);		
		// Enable Genesis SEO support for products
		add_settings_field( 
			'genwoo_checkbox_genesis_seo_support', 
			__( 'Enable Genesis seo support for products', 'genesis-woocommerce' ), 
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
			__( 'Modify single product Genesis breadcrumbs', 'genesis-woocommerce' ), 
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
			__( 'Modify Shop page Genesis breadcrumbs', 'genesis-woocommerce' ), 
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
		// Change Add to cart button text for product archive
		add_settings_field( 
			'genwoo_add_to_cart_text_archive', 
			__( 'Change Add to cart text for shop/archive page', 'genesis-woocommerce' ), 
			array($this, 'genwoo_add_to_cart_text_archive_render'), 
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

	// Change Add to cart text - archive product
	function genwoo_add_to_cart_text_archive_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<div class="mdl-textfield mdl-js-textfield">
			<input type='text' class="mdl-textfield__input" id='genwoo_settings[genwoo_add_to_cart_text_archive]' name='genwoo_settings[genwoo_add_to_cart_text_archive]'  value='<?php echo (isset($options['genwoo_add_to_cart_text_archive']) ?  $options['genwoo_add_to_cart_text_archive'] : ''); ?>'>
			<label class="mdl-textfield__label" for="genwoo_settings[genwoo_add_to_cart_text_archive]">Add to cart</label>
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
				<a href='https://www.developersq.com' target='_blank' class='banner1'>
					<img width="250" height="250" title="" alt="" src="data:image/jpeg;base64,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" />
				</a>
				<hr />
				<a href='https://www.developersq.com/learn-reactjs-basics/' target='_blank' class='banner2'>
					<img width="250" height="250" title="" alt="" src="data:image/jpeg;base64,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" />
				</a>
			</div>
		<?php	
	}
	

}

?>