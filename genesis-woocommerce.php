<?php

/**
 * Genesis Woocommerce Plugin
 *
 * This plugin is built to connect Genesis child theme with Woocommerce just a better way.
 * This plugin creates option in customizer so theme developer can easily change the things the way they wants.
 * This plugin is opensouce and available on GitHub.
 * 
 * Special (Many)thanks to provide plugin boilerplater for this. https://github.com/DevinVinson/WordPress-Plugin-Boilerplate
 *
 * @link              http://www.developersd.com
 * @since             1.0.0
 * @package           genesis_woocommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Genesis Woocommerce
 * Plugin URI:        http://www.developersq.com/
 * Description:       Connect your Genesis child theme better way with Woocommerce.
 * Version:           1.0.0
 * Author:            Aakash Dodiya
 * Author URI:        http://wwww.developersq.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       genesis-woocommerce
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
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

register_activation_hook( __FILE__, 'activate_genesis_woocoomerce' );
register_deactivation_hook( __FILE__, 'deactivate_genesis_woocoomerce' );

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
run_genesis_woocoomerce();
