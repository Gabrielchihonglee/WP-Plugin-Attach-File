<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/Gabrielchihonglee
 * @since             1.0.0
 * @package           Wpattach
 *
 * @wordpress-plugin
 * Plugin Name:       WP Attach
 * Plugin URI:        https://github.com/Gabrielchihonglee/wpattach
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Gabriel Chi Hong Lee
 * Author URI:        https://github.com/Gabrielchihonglee
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpattach
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpattach-activator.php
 */
function activate_wpattach() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpattach-activator.php';
	Wpattach_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpattach-deactivator.php
 */
function deactivate_wpattach() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpattach-deactivator.php';
	Wpattach_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpattach' );
register_deactivation_hook( __FILE__, 'deactivate_wpattach' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpattach.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wpattach() {

	$plugin = new Wpattach();
	$plugin->run();

}
run_wpattach();
