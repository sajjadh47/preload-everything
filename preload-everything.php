<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @package           Preload_Everything
 * @author            Sajjad Hossain Sagor <sagorh672@gmail.com>
 *
 * Plugin Name:       Preload Everything
 * Plugin URI:        https://wordpress.org/plugins/preload-everything/
 * Description:       Fasten Your Website Loading Speed By Preloading Internal Pages Ahead Of The Time For Your Visitors.
 * Version:           2.0.0
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Sajjad Hossain Sagor
 * Author URI:        https://sajjadhsagor.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       preload-everything
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'PRELOAD_EVERYTHING_PLUGIN_VERSION', '2.0.0' );

/**
 * Define Plugin Folders Path
 */
define( 'PRELOAD_EVERYTHING_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'PRELOAD_EVERYTHING_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'PRELOAD_EVERYTHING_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-preload-everything-activator.php
 *
 * @since    2.0.0
 */
function on_activate_preload_everything() {
	require_once PRELOAD_EVERYTHING_PLUGIN_PATH . 'includes/class-preload-everything-activator.php';

	Preload_Everything_Activator::on_activate();
}

register_activation_hook( __FILE__, 'on_activate_preload_everything' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-preload-everything-deactivator.php
 *
 * @since    2.0.0
 */
function on_deactivate_preload_everything() {
	require_once PRELOAD_EVERYTHING_PLUGIN_PATH . 'includes/class-preload-everything-deactivator.php';

	Preload_Everything_Deactivator::on_deactivate();
}

register_deactivation_hook( __FILE__, 'on_deactivate_preload_everything' );

/**
 * The core plugin class that is used to define admin-specific and public-facing hooks.
 *
 * @since    2.0.0
 */
require PRELOAD_EVERYTHING_PLUGIN_PATH . 'includes/class-preload-everything.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    2.0.0
 */
function run_preload_everything() {
	$plugin = new Preload_Everything();

	$plugin->run();
}

run_preload_everything();
