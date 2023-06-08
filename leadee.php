<?php
/**
 * Plugin Name: Leadee
 * Description: Leadee is a user-friendly plugin that collecting leads from Contact Form 7, WPForms, Ninja Forms, and allows you to analyze them in a user-friendly dashboard.
 * Plugin URI: https://leadee.io?utm_source=refferal&utm_medium=wp_admin&utm_content=plugin_url
 * Author: Leadee.io
 * Version: 0.5.0
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

define('LEADEE_VERSION', '0.5.0');

define('LEADEE_PLUGIN', __FILE__);

define('LEADEE_PLUGIN_DIR', untrailingslashit(dirname(LEADEE_PLUGIN)));

define('LEADEE_PLUGIN_URL',
    untrailingslashit(plugins_url('', LEADEE_PLUGIN))
);

define('LEADEE_PLUGIN_BASENAME', plugin_basename(LEADEE_PLUGIN));

define('LEADEE_PLUGIN_NAME', trim(dirname(LEADEE_PLUGIN_BASENAME), '/'));

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-leadee-activator.php
 */
function activate_leadee()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-leadee-activator.php';
    (new leadee_Activator)->activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-leadee-deactivator.php
 */
function deactivate_leadee()
{
    require_once LEADEE_PLUGIN_DIR . '/includes/class-leadee-deactivator.php';
    leadee_Deactivator::deactivate();
}

function update_any_when_plugin_upgrade($upgrader_object, $options)
{
    require_once LEADEE_PLUGIN_DIR . '/includes/class-leadee-updater.php';
    leadee_Updater::update_plugin($upgrader_object, $options);
}

register_activation_hook(__FILE__, 'activate_leadee');
register_deactivation_hook(__FILE__, 'deactivate_leadee');
add_action('upgrader_process_complete', 'update_any_when_plugin_upgrade', 10, 2);

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
 *
 */
function run_leadee()
{
    $plugin = new leadee();
    $plugin->run();
}

//i18n plugin settings for current user

function leadee_load_textdomain()
{
    $locale = get_user_locale();
    if ($locale !== null && $locale !== '') {
        $mofile = plugin_dir_path(__FILE__) . 'languages/leadee-' . $locale . '.mo';
        load_textdomain('leadee', $mofile);
    }
}

add_action('plugins_loaded', 'leadee_load_textdomain');

run_leadee();
