<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://about.me/roshniahuja
 * @since      1.0.0
 *
 * @package    Guestsubmission
 * @subpackage Guestsubmission/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Guestsubmission
 * @subpackage Guestsubmission/includes
 * @author     Roshni Ahuja <roshniahuja14@gmail.com>
 */
class Guestsubmission_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'guestsubmission',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
