<?php

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

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Genesis_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Genesis_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'css/genesis-woocoomerce-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Genesis_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Genesis_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'js/genesis-woocoomerce-admin.js', array( 'jquery' ), $this->version, false );

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
		add_object_page( __( 'Genesis Woocommerce Settings', 'genesis-woocommerce' ),
		__( 'Genesis Woocommerce', 'genesis-woocommerce' ),
		'administrator', 'genesis-woocommerce',
		array( $this, 'genwoo_settings_page' ), 'dashicons-share-alt' );

	}
	
	public function genwoo_settings_page() { 
		?>
		<form action='options.php' method='post'>
			
			<h2>Genesis Woocommerce</h2>
			
			<?php
			settings_fields( 'genwoo_settings' );
			do_settings_sections( 'genwoo_settings' );
			submit_button();
			?>
			
		</form>
		<?php		
	} 
	
	public function genwoo_settings_init(  ) { 

		register_setting( 'genwoo_settings', 'genwoo_settings', array($this, 'genwoo_validate_inputs'));
		
		//*------------------------- GENERAL SECTION ----------------------------------*//
		add_settings_section(
			'genwoo_general_section', 
			__( 'Woocoomerce settings for your genesis child theme', 'genesis-woocommerce' ), 
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
		
		//*--------------------------- WOOCOMMERCE BREADCRUMBS SECTION --------------------------*//
		add_settings_section(
			'genwoo_woocom_section', 
			__( 'Woocoomerce breadcrumbs', 'genesis-woocommerce' ), 
			array($this, 'genwoo_settings_woocom_section_callback'), 
			'genwoo_settings'
		);
		// Delimiter
		add_settings_field( 
			'genwoo_woocom_bc_delimiter', 
			__( 'Woocoommerce Breadcrumb Delimiter', 'genesis-woocommerce' ), 
			array($this, 'genwoo_woocom_bc_delimiter_render'), 
			'genwoo_settings', 
			'genwoo_woocom_section' 
		);
		// Wrap before
		add_settings_field( 
			'genwoo_woocom_bc_wrap_before', 
			__( 'Woocoommerce Breadcrumb Wrap Before', 'genesis-woocommerce' ), 
			array($this, 'genwoo_woocom_bc_wrap_before_render'), 
			'genwoo_settings', 
			'genwoo_woocom_section' 
		);
		// Wrap after
		add_settings_field( 
			'genwoo_woocom_bc_wrap_after', 
			__( 'Woocoommerce Breadcrumb Wrap After', 'genesis-woocommerce' ), 
			array($this, 'genwoo_woocom_bc_wrap_after_render'), 
			'genwoo_settings', 
			'genwoo_woocom_section' 
		);
		// Just Before 
		add_settings_field( 
			'genwoo_woocom_bc_before', 
			__( 'Woocoommerce Breadcrumb Before', 'genesis-woocommerce' ), 
			array($this, 'genwoo_woocom_bc_before_render'), 
			'genwoo_settings', 
			'genwoo_woocom_section' 
		);
		// Just After
		add_settings_field( 
			'genwoo_woocom_bc_after', 
			__( 'Woocoommerce Breadcrumb After', 'genesis-woocommerce' ), 
			array($this, 'genwoo_woocom_bc_after_render'), 
			'genwoo_settings', 
			'genwoo_woocom_section' 
		);
		// Home text
		add_settings_field( 
			'genwoo_woocom_bc_home', 
			__( 'Woocoommerce Breadcrumb Home Text', 'genesis-woocommerce' ), 
			array($this, 'genwoo_woocom_bc_home_render'), 
			'genwoo_settings', 
			'genwoo_woocom_section' 
		);		
		
		//*------------------------------- STUDIOPRESS SECTION ---------------------------------*//
		add_settings_section(
			'genwoo_sp_section', 
			__( 'Studio press plugins', 'genesis-woocommerce' ), 
			array($this, 'genwoo_settings_sp_section_callback'), 
			'genwoo_settings'
		);
		// Enable Studiopress simple sidebar plugin support
		add_settings_field( 
			'genwoo_checkbox_sp_ss_support', 
			__( 'Enable Studiopress Simple Sidebar plugin support', 'genesis-woocommerce' ), 
			array($this, 'genwoo_checkbox_sp_ss_render'), 
			'genwoo_settings', 
			'genwoo_sp_section' 
		);		
		// Enable Studiopress simple menus plugin support
		add_settings_field( 
			'genwoo_checkbox_sp_sm_support', 
			__( 'Enable Studiopress Simple Menu plugin support', 'genesis-woocommerce' ), 
			array($this, 'genwoo_checkbox_sp_sm_render'), 
			'genwoo_settings', 
			'genwoo_sp_section' 
		);
		
		/*	
		add_settings_field( 
			'genwoo_text_field_0', 
			__( 'Settings field description', 'genesis-woocommerce' ), 
			array($this, 'genwoo_text_field_0_render'), 
			'pluginPage', 
			'genwoo_pluginPage_section' 
		);
	
	
		add_settings_field( 
			'genwoo_textarea_field_2', 
			__( 'Settings field description', 'genesis-woocommerce' ), 
			array($this,'genwoo_textarea_field_2_render'), 
			'pluginPage', 
			'genwoo_pluginPage_section' 
		);
	
		add_settings_field( 
			'genwoo_select_field_3', 
			__( 'Settings field description', 'genesis-woocommerce' ), 
			array($this,'genwoo_select_field_3_render'), 
			'pluginPage', 
			'genwoo_pluginPage_section' 
		);	*/
	}

	// Declare woocommerce support checkbox
	function genwoo_checkbox_declare_woo_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='checkbox' name='genwoo_settings[genwoo_checkbox_declare_woo_support]' <?php (isset($options['genwoo_checkbox_declare_woo_support']) ? checked( $options['genwoo_checkbox_declare_woo_support'], 1 ) : ''); ?> value='1'>
		<?php
	}
	
	// Enable genesis layout support for products 
	function genwoo_checkbox_genesis_layout_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='checkbox' name='genwoo_settings[genwoo_checkbox_genesis_layout_support]' <?php (isset($options['genwoo_checkbox_genesis_layout_support']) ? checked( $options['genwoo_checkbox_genesis_layout_support'], 1 ) : ''); ?> value='1'>
		<?php
	}
	
	// Enable genesis seo support for products 
	function genwoo_checkbox_genesis_seo_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='checkbox' name='genwoo_settings[genwoo_checkbox_genesis_seo_support]' <?php (isset($options['genwoo_checkbox_genesis_seo_support']) ? checked( $options['genwoo_checkbox_genesis_seo_support'], 1 ) : ''); ?> value='1'>
		<?php
	}
	
	// Enable  studiopress Simple Sidebar support
	function genwoo_checkbox_sp_ss_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='checkbox' name='genwoo_settings[genwoo_checkbox_sp_ss_support]' <?php (isset($options['genwoo_checkbox_sp_ss_support']) ? checked( $options['genwoo_checkbox_sp_ss_support'], 1 ) : ''); ?> value='1'>
		<?php
	}

	// Enable studiopress Simple Menu Support
	function genwoo_checkbox_sp_sm_render(){
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='checkbox' name='genwoo_settings[genwoo_checkbox_sp_sm_support]' <?php (isset($options['genwoo_checkbox_sp_sm_support']) ? checked( $options['genwoo_checkbox_sp_sm_support'], 1 ) : ''); ?> value='1'>
		<?php
	}
	
	// Woocommerce breadcrumbs delimiter
	function genwoo_woocom_bc_delimiter_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='text' name='genwoo_settings[genwoo_woocom_bc_delimiter]' value='<?php echo (isset($options['genwoo_woocom_bc_delimiter']) ? $options['genwoo_woocom_bc_delimiter'] : '&#47;'); ?>'>
		<?php	
	}
	
	// Woocommerce breadcrumbs wrap before
	function genwoo_woocom_bc_wrap_before_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='text' name='genwoo_settings[genwoo_woocom_bc_wrap_before]' value='<?php echo (isset($options['genwoo_woocom_bc_wrap_before']) ? $options['genwoo_woocom_bc_wrap_before'] : '<div class="breadcrumb wrap" itemprop="breadcrumb">'); ?>'>
		<span>default Woocommerce uses &lt;nav&gt; tag</span>
		<?php	
	}
	
	// Woocommerce breadcrumb after
	function genwoo_woocom_bc_wrap_after_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='text' name='genwoo_settings[genwoo_woocom_bc_wrap_after]' value='<?php echo (isset($options['genwoo_woocom_bc_wrap_before']) ? $options['genwoo_woocom_bc_wrap_after'] : '</div>'); ?>'>
		<span>default Woocommerce uses &lt;/nav&gt; tag</span>
		<?php	
	}

	// Woocommerce breadcrumbs before
	function genwoo_woocom_bc_before_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='text' name='genwoo_settings[genwoo_woocom_bc_before]' value='<?php echo (isset($options['genwoo_woocom_bc_before']) ? $options['genwoo_woocom_bc_before'] : ''); ?>'>
		<?php	
	}
	
	// Woocommerce breadcrumbs after
	function genwoo_woocom_bc_after_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='text' name='genwoo_settings[genwoo_woocom_bc_after]' value='<?php echo (isset($options['genwoo_woocom_bc_after']) ? $options['genwoo_woocom_bc_after'] : ''); ?>'>
		<?php	
	}
	
	// Woocommerce breadcrumbs Home text
	function genwoo_woocom_bc_home_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<input type='text' name='genwoo_settings[genwoo_woocom_bc_home]' value='<?php echo (isset($options['genwoo_woocom_bc_home']) ? $options['genwoo_woocom_bc_home'] : ''); ?>'>
		<?php	
	}
	
	/*function genwoo_textarea_field_2_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<textarea cols='40' rows='5' name='genwoo_settings[genwoo_textarea_field_2]'> 
			<?php echo $options['genwoo_textarea_field_2']; ?>
	 	</textarea>
		<?php	
	}


	function genwoo_select_field_3_render(  ) { 	
		$options = get_option( 'genwoo_settings' );
		?>
		<select name='genwoo_settings[genwoo_select_field_3]'>
			<option value='1' <?php selected( $options['genwoo_select_field_3'], 1 ); ?>>Option 1</option>
			<option value='2' <?php selected( $options['genwoo_select_field_3'], 2 ); ?>>Option 2</option>
		</select>	
	<?php	
	}*/

	/**
	* This function is just a simple call back function for General section for our setting
	*
	* @since	1.0.0
	*/
	public function genwoo_settings_general_section_callback() { 
		echo __( 'Please select the things you need.', 'genesis-woocommerce' );
	}
	
	/**
	* This function is just a simple call back function for Woocommerce section for our setting
	*
	* @since	1.0.0
	*/
	public function genwoo_settings_woocom_section_callback() { 
		echo __( 'WooCommerce Breadcrumbs settings.', 'genesis-woocommerce' );
	}
	
	/**
	* This function is just a simple call back function for SP section for our setting
	*
	* @since	1.0.0
	*/
	public function genwoo_settings_sp_section_callback() { 
		echo __( 'Lets take care of studiopress things.', 'genesis-woocommerce' );
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
	
}
