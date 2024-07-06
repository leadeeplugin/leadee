<?php
/**
 * Plugin Name: Leadee
 * Contributors: leadeeplugin
 * Description: Leadee is a user-friendly plugin that collecting leads from Contact Form 7, WPForms, Ninja Forms, and allows you to analyze them in a user-friendly dashboard.
 * Plugin URI: https://leadee.io?utm_source=refferal&utm_medium=wp_admin&utm_content=plugin_url
 * Author: Leadee.io
 * Version: 1.0.4
 * Stable tag: 1.0.4
 * Tested up to: 6.6
 * Author URI: https://leadee.io?utm_source=refferal&utm_medium=wp_admin&utm_content=author_url
 *
 * Text Domain: leadee
 *
 * @package Leadee
 * @category Core
 *
 * License:            GNU v3
 * License URI:        https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'LEADEE_VERSION', '1.0.4' );

define( 'LEADEE_PLUGIN', __FILE__ );

define( 'LEADEE_PLUGIN_DIR', untrailingslashit( dirname( LEADEE_PLUGIN ) ) );

define(
	'LEADEE_PLUGIN_URL',
	untrailingslashit( plugins_url( '', LEADEE_PLUGIN ) )
);

define( 'LEADEE_PLUGIN_BASENAME', plugin_basename( LEADEE_PLUGIN ) );

define( 'LEADEE_PLUGIN_NAME', trim( dirname( LEADEE_PLUGIN_BASENAME ), '/' ) );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-leadee-activator.php
 */
function leadee_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-leadee-activator.php';
	( new LEADEE_Activator() )->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-leadee-deactivator.php
 */
function leadee_deactivate() {
	require_once LEADEE_PLUGIN_DIR . '/includes/class-leadee-deactivator.php';
	LEADEE_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'leadee_activate' );
register_deactivation_hook( __FILE__, 'leadee_deactivate' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and core-facing site hooks.
 */
require LEADEE_PLUGIN_DIR . '/includes/class-leadee.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function leadee_run() {
	$plugin = new LEADEE_MainInit();
	$plugin->leadee_start();
}

// i18n plugin settings for current user

function leadee_load_textdomain() {
	$locale = get_user_locale();
	if ( $locale !== null && $locale !== '' ) {
		$mofile = plugin_dir_path( __FILE__ ) . 'languages/leadee-' . $locale . '.mo';
		load_textdomain( 'leadee', $mofile );
	}
}

add_action( 'plugins_loaded', 'leadee_load_textdomain' );

leadee_run();
