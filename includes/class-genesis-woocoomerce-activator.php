<?php
namespace GenWoo;
/**
 * Fired during plugin activation
 *
 * @link       http://www.developersq.com
 * @since      1.0.0
 *
 * @package    Genesis_Woocommerce
 * @subpackage Genesis_Woocommerce/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Genesis_Woocommerce
 * @subpackage Genesis_Woocommerce/includes
 * @author     Aakash Dodiya <hello@developersq.com>
 */
class Genesis_Woocommerce_Activator {

	/**
	 * Check activation conditions
	 *
	 * If WooCommerce is not active die gracefully.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$message = '';
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$message .= sprintf( '<br /><br />%s', __( 'Install and activate the WooCommerce plugin.', 'gencwooc') );

			wp_die( $message, 'WooCommerce with Genesis Plugin', array( 'back_link' => true ) );
		}

		
	}

}
