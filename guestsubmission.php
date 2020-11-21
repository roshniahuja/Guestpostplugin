<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://about.me/roshniahuja
 * @since             1.0.0
 * @package           Guestsubmission
 *
 * @wordpress-plugin
 * Plugin Name:       GuestSubmission
 * Plugin URI:        guestsubmission
 * Description:       Creates a custom post type with features to create post from front end. Display pending post list using shortcode.
 * Version:           1.0.0
 * Author:            Roshni Ahuja
 * Author URI:        https://about.me/roshniahuja
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       guestsubmission
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GUESTSUBMISSION_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-guestsubmission-activator.php
 */
function activate_guestsubmission() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-guestsubmission-activator.php';
	Guestsubmission_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-guestsubmission-deactivator.php
 */
function deactivate_guestsubmission() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-guestsubmission-deactivator.php';
	Guestsubmission_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_guestsubmission' );
register_deactivation_hook( __FILE__, 'deactivate_guestsubmission' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-guestsubmission.php';
/****** Register event post type and event taxonomy******/
require plugin_dir_path( __FILE__ ) . 'includes/guestsubmission-cpt.php';
/****** Shortcodes ******/
require plugin_dir_path( __FILE__ ) . 'public/class-guestsubmission-shortcodes.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_guestsubmission() {

	$plugin = new Guestsubmission();
	$plugin->run();

}
run_guestsubmission();
