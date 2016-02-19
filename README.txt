=== WooCommerce With Genesis Theme ===
Contributors: developersq
Donate link: https://www.developersq.com
Tags: genesis, woocommerce
Requires at least: 4.0
Tested up to: 4.4.2
Stable tag: 3.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin is built to connect Genesis child theme with WooCommerce just a better way.

== Description ==

This plugin is built to connect Genesis child theme with Woocommerce just a better way.

It provides options so that store setting can easily be managed in genesis websites. The plugin will override
single-product.php and archive-product.php breadcrumbs if selected. 

Other Plugin features:

1. Enable Woocommerce support option
1. Enable Genesis layouts for product option
1. Enable Genesis SEO support option
1. Remove woocommerce sidebar option
1. Remove woocommerce wrapper support option
1. Hide SKU in product page option
1. Product description tab heading change option
1. Hide Description/addtional information/review tab options
1. Remove result count option
1. Hide shop page title/sorting dropdown option
1. Configure number of product in a row option
1. Option to change 'Add to cart' text in shop and product page
1. Change Description/Additional information/ review tab heading
1. Option to enable/disable woocommercer breadcrumbs

Please contribute in [gitHub](https://github.com/sky4git/connect-genesis-woocommerce "Connect Genesis WooCommerce").

= Website =
https://www.developersq.com/

= Bug Submission and Forum Support =
https://github.com/sky4git/connect-genesis-woocommerce/issues

== Installation ==

Please install and activate WooCommerce before activating this plugin.

e.g.

1. Upload `connect-genesis-woocommerce` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Frequently Asked Questions ==

= Should I Activate WooCoommerce before activating this plugin? =

Yes, Please. 

= Is this plugin been tested with non-Genesis themes? =

No, But in most cases it uses wordpress standard hooks, so probably it will be good.

== Screenshots ==

1. Plugin options menu
2. Single product settings
3. Shop page settings

== Changelog ==

= 3.0 =
* Enabled an option to remove woocommerce breadcrumbs.
* Options are added for Additional information/review tab heading change.

= 2.1 =
* Replace add_object_page method with add_menu_page to remain compatible with WordPress 4.5
* Other minor housekeeping 

= 2.0 =
* UI update to Material Design.
* Option for 'Add to cart' text change added.
* Better checking for WooCommerce availability.
* Plugin is now its in own namespace to limit or eliminate ambiguity.
* Plugin now requires PHP version 5.5 at least.

= 1.0 =
* Plugin Released.
