<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/thechetanvaghela
 * @since             1.0.0
 * @package           Disable_Updates_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       Disable Updates by CV
 * Description:       Plugins, themes and the core update can be disabled from the setting page.
 * Version:           1.0.0
 * Author:            Chetan Vaghela
 * Author URI:        https://github.com/thechetanvaghela
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disable-updates-for-wordpress
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
define( 'DISABLE_UPDATES_FOR_WORDPRESS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-disable-updates-for-wordpress-activator.php
 */
function activate_disable_updates_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-disable-updates-for-wordpress-activator.php';
	Disable_Updates_For_Wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-disable-updates-for-wordpress-deactivator.php
 */
function deactivate_disable_updates_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-disable-updates-for-wordpress-deactivator.php';
	Disable_Updates_For_Wordpress_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_disable_updates_for_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_disable_updates_for_wordpress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-disable-updates-for-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_disable_updates_for_wordpress() {

	$plugin = new Disable_Updates_For_Wordpress();
	$plugin->run();

}
run_disable_updates_for_wordpress();
