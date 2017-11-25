<?php
namespace GenWoo;
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
		$this->genwoo_genesis_layout_support();
		// check Genesis SEO settings support
		$this->genwoo_genesis_seo_support();
		// check single product breadcrumb
		$this->genwoo_single_product_bc();
		// remove shop page title
		$this->genwoo_hide_shop_title();
		// check shop page breadcrumb
		$this->genwoo_shop_page_bc();
		// configure products per row in shop page
		$this->genwoo_configure_shop_row_products();
		// hide sku in single product
		$this->genwoo_single_product_hide_sku();
		// additioanl information/review tab headings
		$this->genwoo_product_tab_headings();
		// remove product tabs - Fixes #1
		$this->genwoo_hide_products_tabs();
		// check studiopress simple sidebar support
		//$this->genwoo_sp_ss_support();
		// check studiopress simple menu support
		//$this->genwoo_sp_sm_support();
		// change add to cart text - single product
		$this->genwoo_add_to_cart_text_single();
		// change add to cart text - archive/shop page - for SIMPLE product
		$this->genwoo_add_to_cart_text_archive_simple();
		// change add to cart text - archive/shop page - for EXTERNAL product
		$this->genwoo_add_to_cart_text_archive_external();
		// change add to cart text - archive/shop page - for GROUPED product
		$this->genwoo_add_to_cart_text_archive_grouped();
		// change add to cart text - archive/shop page - for VARIABLE product
		$this->genwoo_add_to_cart_text_archive_variable();
		// change return to shop button url
		$this->genwoo_return_to_shop_url();
		// Continue shopping url
		$this->genwoo_continue_shopping_url();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'css/genesis-woocoomerce-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->genesis_woocoomerce, plugin_dir_url( __FILE__ ) . 'js/genesis-woocoomerce-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	* This function removes the woocommerce breadcrumb.
	* 
	* We just need genesis breadcrumb so there is no need of woocommece breadcrumb
	*
	* @since 1.0.0
	* @modified 3.0
	*/
	public function genwoo_remove_breadcrumb(){
		$is_woo_bc_removed = (isset($this->options['genwoo_remove_woo_bc']) ? $this->options['genwoo_remove_woo_bc'] : false);
		if($is_woo_bc_removed){
			// Remove Woocommerce breadcrumb from the equation in the begginng
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
		}		
		
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
	private function genwoo_genesis_layout_support(){ 
		$is_genesis_layout = (isset($this->options['genwoo_checkbox_genesis_layout_support']) ? $this->options['genwoo_checkbox_genesis_layout_support'] : false);
		if($is_genesis_layout){
			add_post_type_support( 'product',  'genesis-layouts' );
		}
	}

	/**
	* Genesis SEO support
	* @since 1.0.0
	*/
	private function genwoo_genesis_seo_support(){
		$is_genesis_seo = (isset($this->options['genwoo_checkbox_genesis_seo_support']) ? $this->options['genwoo_checkbox_genesis_seo_support'] : false);
		if($is_genesis_seo){
			add_post_type_support( 'product', array( 'genesis-seo' ) );
		}
	}	
	
	/**
	* Remove woocommerce sidebars
	* @since 1.0.0
	*/	
	public function genwoo_remove_sidebar(){
		$is_remove_sidebar = (isset($this->options['genwoo_remove_sidebar']) ? $this->options['genwoo_remove_sidebar'] : false);
		if($is_remove_sidebar){ 
			// Unhook WooCommerce Sidebar - use Genesis Sidebars instead
			remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );				
		}
	}
	
	/**
	* Remove woocommerce wrapper - before
	* @since 1.0.0
	*/	
	public function	genwoo_remove_wrapper_before(){
		$is_remove_wrapper = (isset($this->options['genwoo_remove_wrapper']) ? $this->options['genwoo_remove_wrapper'] : false);
		if($is_remove_wrapper){ 
			// Unhook WooCommerce wrappers
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );				
		}
	}
	
	/**
	* Remove woocommerce wrapper - after
	* @since 1.0.0
	*/	
	public function	genwoo_remove_wrapper_after(){
		$is_remove_wrapper = (isset($this->options['genwoo_remove_wrapper']) ? $this->options['genwoo_remove_wrapper'] : false);
		if($is_remove_wrapper){ 
			// Unhook WooCommerce wrappers
			remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );				
		}
	}
	
	/**
	* Remove result count in shop page
	* @since 1.0.0
	*/
	public function genwoo_remove_result_count(){
		$is_remove_count = (isset($this->options['genwoo_remove_result_count']) ? $this->options['genwoo_remove_result_count'] : false);
		if($is_remove_count){  
			// Remove the action
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );				
		}
	}
	
	/**
	* Remove sorting dropdown
	* @since 1.0.0
	*/
	public function genwoo_remove_sorting_dropdown(){
		$is_remove_dropdown = (isset($this->options['genwoo_hide_shop_dropdown']) ? $this->options['genwoo_hide_shop_dropdown'] : false);
		if($is_remove_dropdown){  
			// Remove the action
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );				
		}
	}
	
	/**
	* Configure number of products in shop row
	* @since 1.0.0
	*/
	public function genwoo_configure_shop_row_products(){
		add_filter('loop_shop_columns', array($this, 'genwoo_loop_columns'));				
	}
	
	/**
	* Single product breadcrumb
	* @since 1.0.0
	*/	
	private function genwoo_single_product_bc(){
		$is_single_product_bc = (isset($this->options['genwoo_single_product_bc']) ? $this->options['genwoo_single_product_bc'] : false);

		if($is_single_product_bc){ 
			add_filter( 'genesis_single_crumb', array($this,'genwoo_single_product_crumb'), 10, 2 );				
		}
	}
	
	/**
	* Hide sku in single product page
	* @since 1.0.0
	*/
	private function genwoo_single_product_hide_sku(){
		add_filter( 'wc_product_sku_enabled', array($this,'genwoo_remove_product_page_sku'), 10, 1 );				
	}
	
	/**
	* Additional information tab heading
	* @since 3.0
	* @reference https://docs.woothemes.com/document/editing-product-data-tabs/#doc-title
	*/
	private function genwoo_product_tab_headings(){
		add_filter( 'woocommerce_product_tabs', array( $this, 'genwoo_product_tab_headings_change' ), 98, 1 );
	}
	
	/**
	* This function helps to hide different product tabs
	* @since 1.0.0
	*/
	private function genwoo_hide_products_tabs(){
		add_filter( 'woocommerce_product_tabs', array( $this, 'genwoo_remove_product_tabs' ), 99, 1 );
	}
	
	
	/**
	* Hide shop page title 
	* @since 1.0.0
	*/
	private function genwoo_hide_shop_title(){
		$is_hide_title = (isset($this->options['genwoo_hide_shop_title']) ? $this->options['genwoo_hide_shop_title'] : false);

		if($is_hide_title){ 
			add_filter( 'woocommerce_show_page_title' , array($this, 'genwoo_hide_shop_page_title') );				
		}
	}
	
	/**
	* Shop page product breadcrumb
	* @since 1.0.0
	*/	
	private function genwoo_shop_page_bc(){
		$is_shop_page_bc = (isset($this->options['genwoo_shop_page_bc']) ? $this->options['genwoo_shop_page_bc'] : false);
		if($is_shop_page_bc){ 
			add_filter( 'genesis_archive_crumb', array($this,'genwoo_shop_page_crumb'), 10, 2 );				
		}
	}	
	
	/**
	* Enable  studiopress Simple Sidebar support
	* @since 1.0.0
	*/
	/*private function genwoo_sp_ss_support(){
		$is_sp_ss_support = (isset($this->options['genwoo_checkbox_sp_ss_support']) ? $this->options['genwoo_checkbox_sp_ss_support'] : false);
		if($is_sp_ss_support){
			add_post_type_support( 'product', array( 'genesis-simple-sidebars') );
			if ( in_array( 'genesis-simple-sidebars/plugin.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
				
			}// Always end if properly - no short ends	
		}
	}*/
	
	/**
	* Enable studiopress Simple Menu support
	* @since 1.0.0
	*/
	/*private function genwoo_sp_sm_support(){
		$is_sp_sm_support = (isset($this->options['genwoo_checkbox_sp_sm_support']) ? $this->options['genwoo_checkbox_sp_sm_support'] : false);
		if($is_sp_sm_support){
			add_post_type_support( 'product', array( 'genesis-simple-menus' ) );
			if ( in_array( 'genesis-simple-menus/simple-menu.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
				
			}// Always end if properly - no short ends
		}
	}*/
	
	/**
	* Change Add to cart text for - single product page
	* @since 2.0.0
	*/
	private function genwoo_add_to_cart_text_single(){
		$is_change_add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_single']) ? $this->options['genwoo_add_to_cart_text_single'] : false);
		if($is_change_add_to_cart_text){
			add_filter( 'woocommerce_product_single_add_to_cart_text', array($this, 'dq_woo_custom_cart_button_text'), 10 ); // 2.1 +
		}
	}

	/**
	* Change Add to cart text for - product archive page - for SIMPLE product type
	* @since 4.0
	*/
	private function genwoo_add_to_cart_text_archive_simple(){
		$is_change_add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_simple']) ? $this->options['genwoo_add_to_cart_text_archive_simple'] : false);
		if($is_change_add_to_cart_text){
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'dq_woo_archive_custom_cart_button_text_simple' ), 10 ); // 2.1 +
		}
	}

	/**
	* Change Add to cart text for - product archive page - for EXTERNAL product type
	* @since 4.0
	*/
	private function genwoo_add_to_cart_text_archive_external(){
		$is_change_add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_external']) ? $this->options['genwoo_add_to_cart_text_archive_external'] : false);
		if($is_change_add_to_cart_text){
			add_filter( 'woocommerce_product_single_add_to_cart_text', array( $this, 'dq_woo_archive_custom_cart_button_text_external' ), 10, 2 ); // 2.1 +
		}
	}

	/**
	* Change Add to cart text for - product archive page - for GROUPED product type
	* @since 4.0
	*/
	private function genwoo_add_to_cart_text_archive_grouped(){
		$is_change_add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_grouped']) ? $this->options['genwoo_add_to_cart_text_archive_grouped'] : false);
		if($is_change_add_to_cart_text){
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'dq_woo_archive_custom_cart_button_text_grouped' ), 10 ); // 2.1 +
		}
	}

	/**
	* Change Add to cart text for - product archive page - for VARIABLE product type
	* @since 4.0
	*/
	private function genwoo_add_to_cart_text_archive_variable(){
		$is_change_add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_variable']) ? $this->options['genwoo_add_to_cart_text_archive_variable'] : false);
		if($is_change_add_to_cart_text){
			add_filter( 'woocommerce_product_add_to_cart_text', array( $this, 'dq_woo_archive_custom_cart_button_text_variable' ), 10 ); // 2.1 +
		}
	}
	
	/**
	* This is a Callback function to Modify single product breadcrumb.
	* 
	* this method is being called from genwoo_single_product_bc()
	* @since 1.0.0
	*/
	function genwoo_single_product_crumb($crumb, $args){
		
		if(is_woocommerce() && is_product()){
			$crumb = '';
			// shop page id
			$shop_page_id = wc_get_page_id( 'shop' );
			// shop page url
			$shop_page_url = get_permalink( $shop_page_id );
			// shop page title
			$shop_page_title = get_the_title( $shop_page_id );			
			// Check permalink
			$permalinks   = get_option( 'woocommerce_permalinks' );
			// Shop page object
			$shop_page    = get_post( $shop_page_id );
			// global post object
			global $post;			
			
			if ( 'product' === get_post_type( $post ) ) {
				// If permalinks contain the shop page in the URI prepend the breadcrumb with shop
				if ( $shop_page_id && $shop_page && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && get_option( 'page_on_front' ) != $shop_page_id ) {
					$crumb = $this->add_crumb($crumb, '', get_the_title( $shop_page ), get_permalink( $shop_page ) );
				}
				if ( $terms = wc_get_product_terms( $post->ID, 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) ) ) {
					$main_term = apply_filters( 'woocommerce_breadcrumb_main_term', $terms[0], $terms );					
					$ancestors = get_ancestors( $main_term->term_id, 'product_cat' );
					$ancestors = array_reverse( $ancestors );			
					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor, 'product_cat' );
			
						if ( ! is_wp_error( $ancestor ) && $ancestor ) {
							$crumb = $this->add_crumb( $crumb,$args['sep'],  $ancestor->name, get_term_link( $ancestor ) );
						}
					}

					$crumb = $this->add_crumb($crumb, $args['sep'], $main_term->name, get_term_link( $main_term ) );
				}
			} elseif ( 'post' != get_post_type( $post ) ) {
				$post_type = get_post_type_object( get_post_type( $post ) );
				$crumb = $this->add_crumb( $crumb, $args['sep'], $post_type->labels->singular_name, get_post_type_archive_link( get_post_type( $post ) ) );
			} else {
				$cat = current( get_the_category( $post ) );
				if ( $cat ) {
					$ancestors = get_ancestors( $main_term->term_id, 'post_category' );
					$ancestors = array_reverse( $ancestors );			
					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor, $taxonomy );
			
						if ( ! is_wp_error( $ancestor ) && $ancestor ) {
							$crumb = $this->add_crumb( $crumb, $args['sep'], $ancestor->name, get_term_link( $ancestor ) );
						}
					}
					$crumb = $this->add_crumb($crumb,$args['sep'], $cat->name, get_term_link( $cat ) );
				}
			}			
			$crumb = $this->add_crumb( $crumb, $args['sep'], get_the_title( $post ) );
			// Reapply the filter
			return apply_filters( 'gencwooc_single_product_crumb', $crumb, $args );
		}
		
		return $crumb;
	}
	
	
	/**
	* This is a Callback function to Modify Shop page breadcrumb.
	* 
	* this method is being called from genwoo_shop_page_bc()
	* @since 1.0.0
	*/
	function genwoo_shop_page_crumb($crumb, $args){
		if(is_woocommerce()){			
			// shop page id
			$shop_page_id = wc_get_page_id( 'shop' );
			// shop page url
			$shop_page_url = get_permalink( $shop_page_id );
			// shop page title
			$shop_page_title = get_the_title( $shop_page_id );			
			// Check permalink
			$permalinks   = get_option( 'woocommerce_permalinks' );
			// Shop page object
			$shop_page    = get_post( $shop_page_id );
			
			// if its a shop page
			if(is_shop()){
				if ( get_option( 'page_on_front' ) == wc_get_page_id( 'shop' ) ) {
					return;
				}
		
				$_name = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
				
				if ( ! $_name ) {
					$product_post_type = get_post_type_object( 'product' );
					$_name = $product_post_type->labels->singular_name;
				}
				$crumb = '';
				$crumb = $this->add_crumb( $crumb, '', $_name, get_post_type_archive_link( 'product' ) );
				
				return apply_filters( 'gencwooc_product_archive_crumb', $crumb, $args );
			}
			
			if(is_product_category() || is_product_tag()){
				
				$crumb = '';
				// If permalinks contain the shop page in the URI prepend the breadcrumb with shop
				if ( $shop_page_id && $shop_page && strstr( $permalinks['product_base'], '/' . $shop_page->post_name ) && get_option( 'page_on_front' ) != $shop_page_id ) {
					$crumb = $this->add_crumb($crumb, '', get_the_title( $shop_page ), get_permalink( $shop_page ) );
				}
				$category_obj = $GLOBALS['wp_query']->get_queried_object();
				if ($category_obj && 0 != $category_obj->parent && $category_obj->taxonomy === 'product_cat') {
					$ancestors = get_ancestors( $category_obj->term_id, $category_obj->taxonomy );
					$ancestors = array_reverse( $ancestors );
				
					foreach ( $ancestors as $ancestor ) {
						$ancestor = get_term( $ancestor, $category_obj->taxonomy );
			
						if ( ! is_wp_error( $ancestor ) && $ancestor ) {
							$crumb = $this->add_crumb($crumb, $args['sep'], $ancestor->name, get_term_link( $ancestor ) );
						}
					}
					$crumb = $this->add_crumb($crumb, $args['sep'], single_cat_title( '', false ), get_category_link( $category_obj->term_id ) );
				
				}else{
					
					$crumb = $this->add_crumb($crumb, $args['sep'], single_cat_title( '', false ), get_category_link( $category_obj->term_id ) );
				}
		
				return apply_filters( 'gencwooc_product_archive_crumb', $crumb, $args );
		    }
								
		}		
		
		return $crumb;
	}
	
	/**
	 * Add a crumb so we don't get lost
	 * 
	 * Please check WC_Breadcrumb->add_crumb() method
	 * This function is taken from Woocommerce class, Modified it for our requirements 
	 * @param string $name
	 * @param string $link
	 * @since 1.0.0
	 */
	private function add_crumb( $crumb, $sep, $name, $link = '' ) {
		if($link){
			$crumb .= $sep.'<a href="'.$link.'">'.$name.'</a>';
		}else{
		    $crumb .= $sep.''.$name;
		}
		
		return $crumb;
	}
	
	/**
	* This function removes the page title from shop page
	*
	* @return false to hide title
	* @since 1.0.0
	*/
	function genwoo_hide_shop_page_title(){
		return false;
	}
	
	/**
	* This function returns the number of products to show in shop page 
	* 
	* @since 1.0.0
	* @return number of products
	*/
	function genwoo_loop_columns(){
		$row_products = (isset($this->options['genwoo_shop_row_products']) ? $this->options['genwoo_shop_row_products'] : false);
		if($row_products){ 
			return $row_products;
		}
		return 4; 
	}
	
	/**
	* This function hide sku in product page if configured
	*
	* @since 1.0.0
	*/
	function genwoo_remove_product_page_sku($enabled){
		$disable_sku = (isset($this->options['genwoo_single_product_hide_sku']) ? $this->options['genwoo_single_product_hide_sku'] : false);
		if( $disable_sku && !is_admin() ){ 
			return false;
		}
		return $enabled;
	}
	

	/**
	* This function changes the product tabs heading for additional information and review tabs
	*
	* @since 3.0
	* @reference https://docs.woothemes.com/document/editing-product-data-tabs/#doc-title
	*/
	function genwoo_product_tab_headings_change($tabs){
		$description_heading = (isset($this->options['genwoo_description_tab_heading']) ? $this->options['genwoo_description_tab_heading'] : false );
		if($description_heading){ 
			if(isset($tabs['description'])){
				$tabs['description']['title'] = $description_heading;   // Rename the description 
				// calling filter function to change heading too
				add_filter( 'woocommerce_product_description_heading', array($this, 'genwoo_product_description_tab_heading'), 10, 1 );
			}
		}

		$addinfo_tab_heading = (isset($this->options['genwoo_addinfo_tab_heading']) ? $this->options['genwoo_addinfo_tab_heading'] : false );
		if($addinfo_tab_heading){
			if(isset($tabs['additional_information'])){
				$tabs['additional_information']['title'] = $addinfo_tab_heading;   // Rename the additional information tab
				// calling filter function to change heading too
				add_filter( 'woocommerce_product_additional_information_heading', array($this, 'genwoo_product_additional_info_tab_heading'), 10, 1 );
			}			
		}

		$review_tab_heading = (isset($this->options['genwoo_review_tab_heading']) ? $this->options['genwoo_review_tab_heading'] : false );
		if($review_tab_heading){
			if(isset($tabs['reviews'])){
				$tabs['reviews']['title'] = $review_tab_heading;   // Rename the additional information tab
				// there is no filter to change review tab heading
				// check file single-product-review.php for filters in woocommerce
				// community support will be appriciated 
			}
		}

		return $tabs;
	}

	/**
	* This function changes the product descriptin heading
	*
	* @since 1.0.0
	*/
	function genwoo_product_description_tab_heading($title){
		$description_heading = (isset($this->options['genwoo_description_tab_heading']) ? $this->options['genwoo_description_tab_heading'] : false);
		if($description_heading){ 
			return $description_heading;
		}
		return $title;
	}

	/**
	* This function change product additional information tab heading
	*
	* @since 3.0
	*/
	function genwoo_product_additional_info_tab_heading($title){
		$addinfo_tab_heading = (isset($this->options['genwoo_addinfo_tab_heading']) ? $this->options['genwoo_addinfo_tab_heading'] : false);
		if($addinfo_tab_heading){
			return $addinfo_tab_heading;
		}
		return $title;
	}

	/**
	* This function removes the product tabs
	*
	* @since 1.0.0
	*/
	function genwoo_remove_product_tabs($tabs){
		
		$remove_description_tab = (isset($this->options['genwoo_hide_description_tab']) ? $this->options['genwoo_hide_description_tab'] : false);
		if($remove_description_tab){
			unset( $tabs['description'] );  // Remove the description tab
		}
		
		$remove_add_info_tab = (isset($this->options['genwoo_hide_additional_information_tab']) ? $this->options['genwoo_hide_additional_information_tab'] : false);
		if($remove_add_info_tab){
			unset( $tabs['additional_information'] );  	// Remove the additional information tab
		}
		
		$remove_review_tab = (isset($this->options['genwoo_hide_review_tab']) ? $this->options['genwoo_hide_review_tab'] : false);
		if($remove_review_tab){
			unset( $tabs['reviews'] ); 	// Remove the reviews tab
		}
		
		return $tabs;
	}

	/**
	* This function changes the cart button text for single product
	* @since 2.0.0
	*/
	function dq_woo_custom_cart_button_text(){
		$add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_single']) ? $this->options['genwoo_add_to_cart_text_single'] : false);
		if($add_to_cart_text){ 
			return __( $add_to_cart_text, 'woocommerce' );
		}
		return 'Add to cart';
	}

	/**
	* This function changes the cart button text for SIMPLE product
	* @since 4.0
	*/
	function dq_woo_archive_custom_cart_button_text_simple(){
		$add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_simple']) ? $this->options['genwoo_add_to_cart_text_archive_simple'] : false);
		if($add_to_cart_text){ 
			global $product;
			// get product type	
			$product_type = $product->get_type();
			if($product_type === 'simple'):
				return __( $add_to_cart_text, 'woocommerce' );
			endif;
		}
		return 'Add to cart';
	}

	/**
	* This function changes the cart button text for EXTERNAL product
	* @since 4.0
	*/
	function dq_woo_archive_custom_cart_button_text_external( $button_text, $product ){
		$add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_external']) ? $this->options['genwoo_add_to_cart_text_archive_external'] : false);
		if($add_to_cart_text){ 
			if( $product->get_type() == 'external' ):
				return __( $add_to_cart_text, 'woocommerce' );
			endif;
		}
		return $button_text;
	}

	/**
	* This function changes the cart button text for GROUPED product
	* @since 4.0
	*/
	function dq_woo_archive_custom_cart_button_text_grouped(){
		$add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_grouped']) ? $this->options['genwoo_add_to_cart_text_archive_grouped'] : false);
		if($add_to_cart_text){ 
			global $product;
			// get product type	
			$product_type = $product->get_type();
			if($product_type === 'grouped'):
				return __( $add_to_cart_text, 'woocommerce' );
			endif;
		}
		return 'Add to cart';
	}

	/**
	* This function changes the cart button text for VARIABLE product
	* @since 4.0
	*/
	function dq_woo_archive_custom_cart_button_text_variable(){
		$add_to_cart_text = (isset($this->options['genwoo_add_to_cart_text_archive_variable']) ? $this->options['genwoo_add_to_cart_text_archive_variable'] : false);
		if($add_to_cart_text){ 
			global $product;
			// get product type	
			$product_type = $product->get_type();
			if($product_type === 'variable'):
				return __( $add_to_cart_text, 'woocommerce' );
			endif;
		}
		return 'Add to cart';
	}

	/**
	* This function checks whether the return to shop button url is set in admin
	* @since 4.0
	*/
	function genwoo_return_to_shop_url(){
		$return_to_shop_url = (isset($this->options['genwoo_return_to_shop_url']) ? $this->options['genwoo_return_to_shop_url'] : false);
		if($return_to_shop_url){ 
			add_filter( 'woocommerce_return_to_shop_redirect', array( $this, 'genwoo_empty_cart_redirect_url' ) );
		}
	}

	/**
	* This function changes return to shop button url
	* @since 4.0
	*/
	function genwoo_empty_cart_redirect_url(){
		$return_to_shop_url = (isset($this->options['genwoo_return_to_shop_url']) ? $this->options['genwoo_return_to_shop_url'] : false);
		return $return_to_shop_url;
	}

	/**
	* This function checks whether the continue shop button url is set in admin
	* @since 4.0
	*/
	function genwoo_continue_shopping_url(){
		$continue_shopping_url = (isset($this->options['genwoo_continue_shopping_url']) ? $this->options['genwoo_continue_shopping_url'] : false);
		if($continue_shopping_url){ 
			add_filter( 'woocommerce_continue_shopping_redirect', array( $this, 'genwoo_woocommerce_continue_shopping_redirect' ) );
		}
	}

	/**
	* This function changes continue shopping button url
	* @since 4.0
	*/
	function genwoo_woocommerce_continue_shopping_redirect() {
		$continue_shopping_url = (isset($this->options['genwoo_continue_shopping_url']) ? $this->options['genwoo_continue_shopping_url'] : false);
		return $continue_shopping_url;
	}
}
