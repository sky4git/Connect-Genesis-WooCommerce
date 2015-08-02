<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.developersq.com
 * @since      1.0.0
 *
 * @package    Genesis_Woocommerce
 * @subpackage Genesis_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Genesis_Woocommerce
 * @subpackage Genesis_Woocommerce/public
 * @author     Aakash <hello@developersq.com>
 */
class Genesis_Woocommerce_Public {

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
	* Plugin options array
	* @since 	1.0.0
	* @access 	private
	* @var 		array   of plugin options
	*/
	Private $options;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $genesis_woocoomerce       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $genesis_woocoomerce, $version ) {

		$this->genesis_woocoomerce = $genesis_woocoomerce;
		$this->version = $version;
		// Load all options for Genesis woocommerce settings
		$this->options = get_option('genwoo_settings');
		// Check Woocommerce support
		$this->genwoo_declare_support();
		// check Genesis layout support
		$this->genwoo_gensis_layout_support();
		// check Genesis SEO settings support
		$this->genwoo_gensis_seo_support();
		// check studiopress simple sidebar support
		$this->genwoo_sp_ss_support();
		// check studiopress simple menu support
		$this->genwoo_sp_sm_support();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'css/genesis-woocoomerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_script( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'js/genesis-woocoomerce-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	* Declare woocommerce support
	* @since 1.0.0
	*/
	private function genwoo_declare_support(){
		$is_support_enabled = (isset($this->options['genwoo_checkbox_declare_woo_support']) ? $this->options['genwoo_checkbox_declare_woo_support'] : false);
		if($is_support_enabled){
			add_theme_support( 'woocommerce' );
		}
	}
	
	/**
	* Genesis Layout support
	* @since 1.0.0
	*/
	private function genwoo_gensis_layout_support(){ 
		$is_genesis_layout = (isset($this->options['genwoo_checkbox_genesis_layout_support']) ? $this->options['genwoo_checkbox_genesis_layout_support'] : false);
		if($is_genesis_layout){
			add_post_type_support( 'product',  'genesis-layouts' );
		}
	}

	/**
	* Genesis SEO support
	* @since 1.0.0
	*/
	private function genwoo_gensis_seo_support(){
		$is_genesis_seo = (isset($this->options['genwoo_checkbox_genesis_seo_support']) ? $this->options['genwoo_checkbox_genesis_seo_support'] : false);
		if($is_genesis_seo){
			add_post_type_support( 'product', array( 'genesis-seo' ) );
		}
	}
		
	/**
	* Enable  studiopress Simple Sidebar support
	* @since 1.0.0
	*/
	private function genwoo_sp_ss_support(){
		$is_sp_ss_support = (isset($this->options['genwoo_checkbox_sp_ss_support']) ? $this->options['genwoo_checkbox_sp_ss_support'] : false);
		if($is_sp_ss_support){
			add_post_type_support( 'product', array( 'genesis-simple-sidebars') );
			if ( in_array( 'genesis-simple-sidebars/plugin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
				//require_once( GCW_SP_DIR . '/genesis-simple-sidebars.php' );
			}// Always end if properly - no short ends	
		}
	}
	
	/**
	* Enable studiopress Simple Menu support
	* @since 1.0.0
	*/
	private function genwoo_sp_sm_support(){
		$is_sp_sm_support = (isset($this->options['genwoo_checkbox_sp_sm_support']) ? $this->options['genwoo_checkbox_sp_sm_support'] : false);
		if($is_sp_sm_support){
			add_post_type_support( 'product', array( 'genesis-simple-menus' ) );
			if ( in_array( 'genesis-simple-menus/simple-menu.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
				//require_once( GCW_SP_DIR . '/genesis-simple-menus.php' );
			}// Always end if properly - no short ends
		}
	}
	
}
