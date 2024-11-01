<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://anderskristo.me
 * @since      1.0.0
 *
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/includes
 * @author     Anders Kristoffersson <anderskristo@gmail.com>
 */
class Super_Mailchimp_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'super-mailchimp',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
