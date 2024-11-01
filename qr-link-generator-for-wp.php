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
 * Version:           1.0.7
 * Author:            Manuel Ramirez Coronel
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Tested up to:      6.6
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
define('QR_LINK_GENERATOR_FOR_WP_VERSION', '1.0.7');

/**
 * Define the Plugin basename
 */
define('QR_LINK_GENERATOR_FOR_WP_BASE_NAME', plugin_basename(__FILE__));

if (!function_exists('qr_link_for_wp')) {
    // Create a helper function for easy SDK access.
    function qr_link_for_wp()
    {
        global $qr_link_for_wp;

        if (!isset($qr_link_for_wp)) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/vendor/freemius/wordpress-sdk/start.php';

            $qr_link_for_wp = fs_dynamic_init(array(
                'id' => '16955',
                'slug' => 'qr-link-generator-for-wp',
                'type' => 'plugin',
                'public_key' => 'pk_81b744e45b5e9176a049ad30eee68',
                'is_premium' => false,
                'has_addons' => false,
                'has_paid_plans' => false,
                'menu' => array(
                    'slug' => 'qr_link_generator_for_wp_settings',
                    'account' => false,
                    'parent' => array(
                        'slug' => 'options-general.php',
                    ),
                ),
            ));
        }

        return $qr_link_for_wp;
    }

    // Init Freemius.
    qr_link_for_wp();
    // Signal that SDK was initiated.
    do_action('qr_link_for_wp_loaded');
}

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

// Not like register_uninstall_hook(), you do NOT have to use a static function.
qr_link_for_wp()->add_action('after_uninstall', 'qr_link_generator_for_wp_uninstall');

function qr_link_generator_for_wp_uninstall()
{

    if (!defined('WP_UNINSTALL_PLUGIN')
        || empty($_REQUEST)
        || !isset($_REQUEST['plugin'])
        || !isset($_REQUEST['action'])
        || 'qr-link-generator-for-wp/qr-link-generator-for-wp.php' !== $_REQUEST['plugin']
        || 'delete-plugin' !== $_REQUEST['action']
        || !check_ajax_referer('updates', '_ajax_nonce')
        || !current_user_can('activate_plugins')
    ) {

        exit;

    }

    /**
     * It is now safe to perform your uninstall actions here.
     *
     * @see https://developer.wordpress.org/plugins/plugin-basics/uninstall-methods/#method-2-uninstall-php
     */

    delete_option('qr_link_generator_for_wp_settings');

}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-qr-link-generator-for-wp.php';

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
