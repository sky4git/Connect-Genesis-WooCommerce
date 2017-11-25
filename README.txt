=== WooCommerce Customizer (with Genesis support) ===
Contributors: developersq
Donate link: https://www.developersq.com
Tags: genesis, woocommerce
Requires at least: 4.0
Tested up to: 4.9
Stable tag: 5.1
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Customize WooCommerce for Genesis and/or Custom theme.

== Description ==

Customize WooCommerce for Genesis and/or Custom theme.

It provides options so that store setting can easily be managed in Genesis and/or Custom theme websites. 

Other Plugin features:

1. Supports WooCommerce 3.x +
1. Enable Woocommerce support option for your theme
1. Enable Genesis layouts for product option (For Genesis theme)
1. Enable Genesis SEO support option (For Genesis theme)
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
1. Option to enable/disable woocommercer breadcrumbs (For Genesis Theme)
1. Change Return to Shop button redirect url.
1. Change Continue Shopping button redirect url.

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
3. Setting menu located in WooCommerce > Customizer.


== Frequently Asked Questions ==

= Should I Activate WooCoommerce before activating this plugin? =

Yes, Please. 

== Screenshots ==

1. Plugin options menu
2. Single product settings
3. Shop page settings

== Changelog ==

= 5.1 =
* Compatibiity improved for WooCommerce 3.x +

= 4.0 =
* Setting menu moved to WooCommerce submenu. Accessible from WooCommerce > Customizer
* Option to change Return to shop url added.
* Option to change Continue shopping url added.
* Options to change 'Add to cart' text for multiple product types.
* Plugin name updated from previousely 'WooCommerce with Genesis theme' to 'WooCommerce Customizer (with Genesis support)'.

= 3.1 =
* Bug fixed: Notice error was appearing when there was no additional information data available to show on product page. Added additional checks to verify array entries exists for product tabs.  https://github.com/sky4git/Connect-Genesis-WooCommerce/issues/2

= 3.0 =
* Enabled an option to remove woocommerce breadcrumbs. https://github.com/sky4git/Connect-Genesis-WooCommerce/issues/1
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
