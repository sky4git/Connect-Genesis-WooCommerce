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
		$this->options = get_option('genwoo_settings');
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
	public function genwoo_declare_support(){
		//var_dump(isset($this->options['genwoo_checkbox_declare_woo_support']));
		$is_support_enabled = (isset($this->options['genwoo_checkbox_declare_woo_support']) ? $this->options['genwoo_checkbox_declare_woo_support'] : false);
		if($is_support_enabled){
			add_theme_support( 'woocommerce' );
		}
	}

}
