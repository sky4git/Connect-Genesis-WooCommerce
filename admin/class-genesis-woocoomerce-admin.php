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
		if ( 'toplevel_page_genesis-woocommerce' === $screen->base ){ 
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
		if ( 'toplevel_page_genesis-woocommerce' === $screen->base ){
			wp_enqueue_script( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'js/genesis-woocoomerce-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'material', plugin_dir_url( __FILE__ ) . 'js/material.min.js', '', $this->version, false );		
		}
	}
	
	/**
	 * Add options page in admin.
	 *
	 * @since    1.0.0
	 */
	public function genwoo_create_menu() {

		/**
		 * This function creates plguin's setting menu page in admin
		 */
		add_object_page( __( 'WooCommerce + Genesis Settings', 'genesis-woocommerce' ),
		__( 'WooCommerce + Genesis Settings', 'genesis-woocommerce' ),
		'administrator', 'genesis-woocommerce',
		array( $this, 'genwoo_settings_page' ), 'dashicons-share-alt' );

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
			__( 'Woocommerce settings for your Genesis child theme', 'genesis-woocommerce' ), 
			array($this, 'genwoo_settings_general_section_callback'), 
			'genwoo_settings'
		);		
		//* Declare woocoomerce support
		add_settings_field( 
			'genwoo_checkbox_declare_woo_support', 
			__( 'Declare Woocommerce Support', 'genesis-woocommerce' ), 
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
			__( 'Modify single product breadcrumb', 'genesis-woocommerce' ), 
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
			__( 'Modify Shop page breadcrumb', 'genesis-woocommerce' ), 
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