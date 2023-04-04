<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://racmanuel.dev
 * @since      1.0.0
 *
 * @package    Qr_Link_Generator_For_Wp
 * @subpackage Qr_Link_Generator_For_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two hooks to
 * enqueue the admin-facing stylesheet and JavaScript.
 * As you add hooks and methods, update this description.
 *
 * @package    Qr_Link_Generator_For_Wp
 * @subpackage Qr_Link_Generator_For_Wp/admin
 * @author     Manuel Ramirez Coronel <ra_cm@outlook.com>
 */

use chillerlan\QRCode\QRCode;

class Qr_Link_Generator_For_Wp_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The unique prefix of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_prefix    The string used to uniquely prefix technical functions of this plugin.
     */
    private $plugin_prefix;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name       The name of this plugin.
     * @param      string $plugin_prefix    The unique prefix of this plugin.
     * @param      string $version    The version of this plugin.
     */
    public function __construct($plugin_name, $plugin_prefix, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->plugin_prefix = $plugin_prefix;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_styles($hook_suffix)
    {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/qr-link-generator-for-wp-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_scripts($hook_suffix)
    {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/qr-link-generator-for-wp-admin.js', array('jquery'), $this->version, false);

    }

    /**
     * Hook in and register a submenu options page for the Page post-type menu.
     */
    public function qr_link_generator_for_wp_admin_settings()
    {

        /**
         * Registers options page menu item and form.
         */
        $cmb = new_cmb2_box(array(
            'id' => 'qr_link_generator_for_wp_settings',
            'title' => esc_html__('QR Link Generator for WP', 'cmb2'),
            'object_types' => array('options-page'),

            /*
             * The following parameters are specific to the options-page box
             * Several of these parameters are passed along to add_menu_page()/add_submenu_page().
             */

            'option_key' => 'qr_link_generator_for_wp_settings', // The option key and admin menu page slug.
            // 'icon_url'        => '', // Menu icon. Only applicable if 'parent_slug' is left empty.
            // 'menu_title'      => esc_html__( 'Options', 'cmb2' ), // Falls back to 'title' (above).
            'parent_slug' => 'options-general.php', // Make options page a submenu item of the themes menu.
            // 'capability'      => 'manage_options', // Cap required to view options-page.
            // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
            // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
            // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
            // 'save_button'     => esc_html__( 'Save Theme Options', 'cmb2' ), // The text for the options-page save button. Defaults to 'Save'.
            // 'disable_settings_errors' => true, // On settings pages (not options-general.php sub-pages), allows disabling.
            // 'message_cb'      => 'yourprefix_options_page_message_callback',
        ));

        $cmb->add_field(array(
            'name' => 'QR Link Generator for WP',
            'desc' => '',
            'type' => 'title',
            'id' => 'qr_link_generator_for_wp_title',
        ));

        $cmb->add_field(array(
            'name' => 'Active QR',
            'desc' => 'Check the box if you need show the QR in the Front-End of the page.',
            'id' => 'qr_link_generator_for_wp_active',
            'type' => 'multicheck',
            'options' => array(
                'post' => 'Posts',
                'page' => 'Pages',
                'product' => 'Products',
            ),
        ));

        $cmb->add_field(array(
            'name' => 'Display',
            'desc' => '',
            'type' => 'title',
            'id' => 'qr_link_generator_for_wp_display',
        ));

        $cmb->add_field(array(
            'name' => 'QR Code Size',
            'desc' => '',
            'default' => '200',
            'id' => 'qr_link_generator_for_wp_size',
            'type' => 'text',
            'attributes' => array(
                'type' => 'number',
                'pattern' => '\d*',
            ),
        ));

        $cmb->add_field(array(
            'name' => 'QR Code Alignament',
            'desc' => 'Select an option',
            'id' => 'qr_link_generator_for_wp_align',
            'type' => 'select',
            'show_option_none' => true,
            'default' => 'center',
            'options' => array(
                'center' => __('Center', 'cmb2'),
                'left' => __('Left', 'cmb2'),
                'right' => __('Right', 'cmb2'),
            ),
        ));

        $cmb->add_field(array(
            'name' => 'Text of Download Button',
            'desc' => '',
            'default' => 'Download QR Code',
            'id' => 'qr_link_generator_for_wp_text_download',
            'type' => 'text',
        ));

        $cmb->add_field(array(
            'name' => 'Hide button?',
            'desc' => 'Select an option',
            'id' => 'qr_link_generator_for_wp_hide_button',
            'type' => 'select',
            'show_option_none' => false,
            'default' => 'no',
            'options' => array(
                'yes' => __('Yes', 'cmb2'),
                'no' => __('No', 'cmb2'),
            ),
        ));

        $cmb->add_field(array(
            'name' => 'Button Color',
            'id' => 'qr_link_generator_for_wp_button_color',
            'type' => 'colorpicker',
            'default' => '#ffffff',
        ));

        $cmb->add_field(array(
            'name' => 'Button Background',
            'id' => 'qr_link_generator_for_wp_button_background',
            'type' => 'colorpicker',
            'default' => '#ffffff',
        ));

        $cmb->add_field(array(
            'name' => 'Display in Products',
            'desc' => '',
            'type' => 'title',
            'id' => 'qr_link_generator_for_wp_display_products',
        ));

        $cmb->add_field(array(
            'name' => 'QR Code Alignament (Product Page)',
            'desc' => 'Select an option',
            'id' => 'qr_link_generator_for_wp_align_product',
            'type' => 'select',
            'show_option_none' => false,
            'default' => 'center',
            'options' => array(
                'center' => __('Center', 'cmb2'),
                'left' => __('Left', 'cmb2'),
                'right' => __('Right', 'cmb2'),
            ),
        ));

        $cmb->add_field(array(
            'name' => 'Text of Product Tab',
            'desc' => '',
            'default' => 'QR Code',
            'id' => 'qr_link_generator_for_wp_text_tab',
            'type' => 'text',
        ));

        $cmb->add_field(array(
            'name' => 'Test Title',
            'desc' => 'This is a title description',
            'type' => 'title',
            'id' => 'wiki_test_title',
            'after_row' => $this->cmb_after_row_cb(),
        ));
    }

    public function cmb_after_row_cb()
    {
        $settings = get_option('qr_link_generator_for_wp_settings');

        $size = $settings['qr_link_generator_for_wp_size'];
        $align = $settings['qr_link_generator_for_wp_align'];
        $text_download = $settings['qr_link_generator_for_wp_text_download'];
        $button_color = $settings['qr_link_generator_for_wp_button_color'];
        $button_background_color = $settings['qr_link_generator_for_wp_button_background'];
        $button_hide = $settings['qr_link_generator_for_wp_hide_button'];
        $class = '';
        if ($button_hide == 'yes') {
            $class = 'none';
        }

        /** New Object for the QRCode - Library */
        $qrcode = new QRCode;
        $text = 'Hola';

        ob_start();

        echo "<pre>";
        print_r($settings);
        echo "</pre>";

        require 'partials/qr-link-generator-for-wp-admin-display.php';
        $html = ob_get_clean();
        return $html;
    }
}
