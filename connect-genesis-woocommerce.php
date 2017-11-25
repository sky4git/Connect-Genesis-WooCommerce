<?php
namespace GenWoo;
/**
 * WooCommerce Customizer(with Genesis support)
 *
 * This plugin is built to connect Genesis child theme with Woocommerce just a better way.
 * It provides options so that store setting can easily be managed in genesis websites. The plugin will override
 * single-product.php and archive-product.php breadcrumbs if selected. 
 * Other Plugin feature:
 * 1: Enable Woocommerce support option
 * 2: Enable Genesis layouts for product option
 * 3: Enable Genesis SEO support option
 * 4: Remove woocommerce sidebar option
 * 5: Remove woocommerce wrapper support option
 * 6: Hide SKU in product page option
 * 7: Product description tab heading change option
 * 8: Hide Description/addtional information/review tab options
 * 9: Remove result count option
 * 10: Hide shop page title/sorting dropdown option
 * 11: Configure number of product in a row option
 * 12: Option to change add to cart text in shop page and in product page
 * 
 * Special (Many)thanks to provide plugin boilerplater for this. https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * @link              http://www.developersq.com
 * @since             1.0.0
 * @package           connect_genesis_woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Customizer(with Genesis support)
 * Plugin URI:        http://www.developersq.com/
 * Description:       Customize WooCommerce for Genesis and/or Custom theme.
 * Version:           6
 * Author:            DevelopersQ
 * Author URI:        http://wwww.developersq.com/
 * License:           GPL-3.0
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       genesis-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-genesis-woocoomerce-activator.php
 */
function activate_genesis_woocoomerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-genesis-woocoomerce-activator.php';
	Genesis_Woocommerce_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-genesis-woocoomerce-deactivator.php
 */
function deactivate_genesis_woocoomerce() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-genesis-woocoomerce-deactivator.php';
	Genesis_Woocommerce_Deactivator::deactivate();
}

register_activation_hook( __FILE__, '\GenWoo\activate_genesis_woocoomerce' );
register_deactivation_hook( __FILE__, '\GenWoo\deactivate_genesis_woocoomerce' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-genesis-woocoomerce.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_genesis_woocoomerce() {

	$plugin = new Genesis_Woocommerce();
	$plugin->run();

}
\GenWoo\run_genesis_woocoomerce();