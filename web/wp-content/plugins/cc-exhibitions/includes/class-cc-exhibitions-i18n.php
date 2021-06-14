<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.neilarnold.com
 * @since      1.0.0
 *
 * @package    Cc_Exhibitions
 * @subpackage Cc_Exhibitions/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cc_Exhibitions
 * @subpackage Cc_Exhibitions/includes
 * @author     Neil P. Arnold <neilparnold@gmail.com>
 */
class Cc_Exhibitions_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cc-exhibitions',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
