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

        $this->plugin_name   = $plugin_name;
        $this->plugin_prefix = $plugin_prefix;
        $this->version       = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_styles($hook_suffix)
    {

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/qr-link-generator-for-wp-admin.css', [], $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     * @param string $hook_suffix The current admin page.
     */
    public function enqueue_scripts($hook_suffix)
    {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/qr-link-generator-for-wp-admin.js', ['jquery'], $this->version, false);

    }

    /**
     * Hook in and register a submenu options page for the Page post-type menu.
     */
    public function qr_link_generator_for_wp_admin_settings()
    {
        $cmb = new_cmb2_box([
            'id'           => 'qr_link_generator_for_wp_settings',
            'title'        => esc_html__('QR Link Generator for WP', 'qr-link-generator-for-wp'),
            'object_types' => ['options-page'],
            'option_key'   => 'qr_link_generator_for_wp_settings',

                                                                              // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
                                                                              // 'menu_title'      => esc_html__( 'Options', 'myprefix' ), // Falls back to 'title' (above).
            'parent_slug'  => 'options-general.php',                          // Make options page a submenu item of the themes menu.
                                                                              // 'capability'      => 'manage_options', // Cap required to view options-page.
                                                                              // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
                                                                              // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
                                                                              // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
            'save_button'  => esc_html__('Save', 'qr-link-generator-for-wp'), // The text for the options-page save button. Defaults to 'Save'.
        ]);

        // 🔹 Sección: Frontend Display Texts
        $cmb->add_field([
            'name' => __('Frontend Texts', 'qr-link-generator-for-wp'),
            'type' => 'title',
            'id'   => 'qr_link_generator_for_wp_section_frontend_texts',
        ]);

        $cmb->add_field([
            'name'    => __('Input Placeholder', 'qr-link-generator-for-wp'),
            'desc'    => __('Text that appears inside the input before the user types.', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_input_placeholder',
            'type'    => 'text',
            'default' => __('Insert your content here.', 'qr-link-generator-for-wp'),
        ]);

        $cmb->add_field([
            'name'    => __('Tooltip Text', 'qr-link-generator-for-wp'),
            'desc'    => __('This text appears as a tooltip in the input field.', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_tooltip_text',
            'type'    => 'textarea_small',
            'default' => __('Insert here your content can be a URL or any text that is converted to QR Code. The QR Code changes automatically when changing the content of the field.', 'qr-link-generator-for-wp'),
        ]);

        $cmb->add_field([
            'name'    => __('Credit Line Text', 'qr-link-generator-for-wp'),
            'desc'    => __('Text shown below the QR with a link to your site.', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_credit_text',
            'type'    => 'textarea_small',
            'default' => __('Made with %1$s and Code by %2$s', 'qr-link-generator-for-wp'),
        ]);

        // 🔹 Sección: Activación general
        $cmb->add_field([
            'name' => __('Active QR in WooCommerce Tabs', 'qr-link-generator-for-wp'),
            'type' => 'title',
            'id'   => 'qr_link_generator_for_wp_section_general',
        ]);

        $cmb->add_field([
            'name' => __('Active QR', 'qr-link-generator-for-wp'),
            'desc' => __('Check the box if you need to show the QR Code in WooCommerce products.', 'qr-link-generator-for-wp'),
            'id'   => 'qr_link_generator_for_wp_active',
            'type' => 'checkbox',
        ]);

        // 🔹 Sección: Apariencia
        $cmb->add_field([
            'name' => __('QR Appearance in WooCommerce Product Tabs', 'qr-link-generator-for-wp'),
            'type' => 'title',
            'id'   => 'qr_link_generator_for_wp_section_display',
        ]);

        $cmb->add_field([
            'name'       => __('QR Code Size (px)', 'qr-link-generator-for-wp'),
            'id'         => 'qr_link_generator_for_wp_size',
            'type'       => 'text',
            'default'    => '200',
            'attributes' => [
                'type'    => 'number',
                'pattern' => '\d*',
            ],
        ]);

        $cmb->add_field([
            'name'             => __('QR Code Alignment', 'qr-link-generator-for-wp'),
            'id'               => 'qr_link_generator_for_wp_align',
            'type'             => 'select',
            'default'          => 'center',
            'show_option_none' => false,
            'options'          => [
                'center' => __('Center', 'qr-link-generator-for-wp'),
                'left'   => __('Left', 'qr-link-generator-for-wp'),
                'right'  => __('Right', 'qr-link-generator-for-wp'),
            ],
        ]);

        $cmb->add_field([
            'name'    => __('Text of Download Button', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_text_download',
            'type'    => 'text',
            'default' => __('Download QR Code', 'qr-link-generator-for-wp'),
        ]);

        $cmb->add_field([
            'name'             => __('Hide Button?', 'qr-link-generator-for-wp'),
            'id'               => 'qr_link_generator_for_wp_hide_button',
            'type'             => 'select',
            'default'          => 'no',
            'show_option_none' => false,
            'options'          => [
                'yes' => __('Yes', 'qr-link-generator-for-wp'),
                'no'  => __('No', 'qr-link-generator-for-wp'),
            ],
        ]);

        $cmb->add_field([
            'name'    => __('Button Text Color', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_button_color',
            'type'    => 'colorpicker',
            'default' => '#ffffff',
        ]);

        $cmb->add_field([
            'name'    => __('Button Background Color', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_button_background',
            'type'    => 'colorpicker',
            'default' => '#ffffff',
        ]);

        $cmb->add_field([
            'name'             => __('QR Code Alignment (Product Page)', 'qr-link-generator-for-wp'),
            'id'               => 'qr_link_generator_for_wp_align_product',
            'type'             => 'select',
            'default'          => 'center',
            'show_option_none' => false,
            'options'          => [
                'center' => __('Center', 'qr-link-generator-for-wp'),
                'left'   => __('Left', 'qr-link-generator-for-wp'),
                'right'  => __('Right', 'qr-link-generator-for-wp'),
            ],
        ]);

        $cmb->add_field([
            'name'    => __('Product Tab Label', 'qr-link-generator-for-wp'),
            'id'      => 'qr_link_generator_for_wp_text_tab',
            'type'    => 'text',
            'default' => __('QR Code', 'qr-link-generator-for-wp'),
        ]);
    }
}
