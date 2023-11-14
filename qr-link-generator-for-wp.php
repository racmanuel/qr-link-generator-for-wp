<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress or ClassicPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://racmanuel.dev
 * @since             1.0.0
 * @package           Qr_Link_Generator_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       QR Link Generator for WP
 * Plugin URI:        https://plugin.com/qr-link-generator-for-wp-uri/
 * Description:       Plugin to Generate QR Code with link inserted by the user in front-end with a form.
 * Version:           1.0.3
 * Author:            Manuel Ramirez Coronel
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Tested up to:      6.2
 * Author URI:        https://racmanuel.dev/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       qr-link-generator-for-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Composer: autoload.php
 */
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';

/**
 * Current plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('QR_LINK_GENERATOR_FOR_WP_VERSION', '1.0.3');

/**
 * Define the Plugin basename
 */
define('QR_LINK_GENERATOR_FOR_WP_BASE_NAME', plugin_basename(__FILE__));

/**
 * The code that runs during plugin activation.
 *
 * This action is documented in includes/class-qr-link-generator-for-wp-activator.php
 * Full security checks are performed inside the class.
 */
function qr_link_generator_for_wp_activate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-qr-link-generator-for-wp-activator.php';
    Qr_Link_Generator_For_Wp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 *
 * This action is documented in includes/class-qr-link-generator-for-wp-deactivator.php
 * Full security checks are performed inside the class.
 */
function qr_link_generator_for_wp_deactivate()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-qr-link-generator-for-wp-deactivator.php';
    Qr_Link_Generator_For_Wp_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'qr_link_generator_for_wp_activate');
register_deactivation_hook(__FILE__, 'qr_link_generator_for_wp_deactivate');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-qr-link-generator-for-wp.php';

/**
 * Initialize the plugin tracker
 *
 * @return void
 */
function appsero_init_tracker_qr_link_generator_for_wp()
{
    if (!class_exists('Appsero\Client')) {
        require_once __DIR__ . '/appsero/src/Client.php';
    }
    $client = new Appsero\Client('b3aea394-ea2b-4f4b-bed4-be54a9daf1fd', 'QR Link Generator for WP', __FILE__);
    // Active insights
    $client->insights()->init();

}
appsero_init_tracker_qr_link_generator_for_wp();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * Generally you will want to hook this function, instead of callign it globally.
 * However since the purpose of your plugin is not known until you write it, we include the function globally.
 *
 * @since    1.0.0
 */
function qr_link_generator_for_wp_run()
{

    $plugin = new Qr_Link_Generator_For_Wp();
    $plugin->run();

}
qr_link_generator_for_wp_run();
