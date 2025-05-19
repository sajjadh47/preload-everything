<?php
/**
 * This file contains the definition of the Preload_Everything_Admin class, which
 * is used to load the plugin's admin-specific functionality.
 *
 * @package       Preload_Everything
 * @subpackage    Preload_Everything/admin
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Preload_Everything_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The plugin options api wrapper object.
	 *
	 * @since     2.0.0
	 * @access    private
	 * @var       array $settings_api Holds the plugin options api wrapper class object.
	 */
	private $settings_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of this plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = new Sajjad_Dev_Settings_API();
	}

	/**
	 * Adds a settings link to the plugin's action links on the plugin list table.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     array $links The existing array of plugin action links.
	 * @return    array $links The updated array of plugin action links, including the settings link.
	 */
	public function add_plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=preload-everything' ) ), __( 'Settings', 'preload-everything' ) );

		return $links;
	}

	/**
	 * Adds the plugin settings page to the WordPress dashboard menu.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Preload Everything', 'preload-everything' ),
			__( 'Preload Everything', 'preload-everything' ),
			'manage_options',
			'preload-everything',
			array( $this, 'menu_page' ),
			'dashicons-admin-tools'
		);
	}

	/**
	 * Renders the plugin menu page content.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function menu_page() {
		$this->settings_api->show_forms();
	}

	/**
	 * Initializes admin-specific functionality.
	 *
	 * This function is hooked to the 'admin_init' action and is used to perform
	 * various administrative tasks, such as registering settings, enqueuing scripts,
	 * or adding admin notices.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function admin_init() {
		// set the settings.
		$this->settings_api->set_sections( $this->get_settings_sections() );

		$this->settings_api->set_fields( $this->get_settings_fields() );

		// initialize settings.
		$this->settings_api->admin_init();
	}

	/**
	 * Returns the settings sections for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings sections, where each section is an array
	 *                  with 'id' and 'title' keys.
	 */
	public function get_settings_sections() {
		$settings_sections = array(
			array(
				'id'    => 'pre_ev_basic_settings',
				'title' => __( 'General Settings', 'preload-everything' )
			),
		);

		/**
		 * Filters the plugin settings sections.
		 *
		 * This filter allows you to modify the plugin settings sections.
		 * You can use this filter to add/remove/edit any settings sections.
		 *
		 * @since     2.0.0
		 * @param     array $settings_sections Default settings sections.
		 * @return    array $settings_sections Modified settings sections.
		 */
		return apply_filters( 'pre_ev_settings_sections', $settings_sections );
	}

	/**
	 * Returns all the settings fields for the plugin settings page.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @return    array An array of settings fields, organized by section ID.  Each
	 *                  section ID is a key in the array, and the value is an array
	 *                  of settings fields for that section. Each settings field is
	 *                  an array with 'name', 'label', 'type', 'desc', and other keys
	 *                  depending on the field type.
	 */
	public function get_settings_fields() {
		$settings_fields = array(
			'pre_ev_basic_settings' => array(
				array(
					'name'  => 'enable_plugin',
					'label' => __( 'Enable Preloading', 'preload-everything' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will enable the plugin functionality.', 'preload-everything' ),
				),
				array(
					'name'    => 'preloading_url_host',
					'label'   => __( 'Enable Preloading For', 'preload-everything' ),
					'type'    => 'select',
					'options' => array(
						'internal'          => __( 'Internal Links Only', 'preload-everything' ),
						'external'          => __( 'External Links Only', 'preload-everything' ),
						'internal_external' => __( 'Internal & External Links', 'preload-everything' ),
					),
					'desc'    => __( 'Select the preloading target links.', 'preload-everything' ),
				),
				array(
					'name'    => 'cache_lifetime',
					'label'   => __( 'Cache Lifetime', 'preload-everything' ),
					'type'    => 'number',
					'desc'    => __( 'Enter cache lifetime in minutes.', 'preload-everything' ),
					'default' => 30,
				),
				array(
					'name'  => 'enable_lazy_loading',
					'label' => __( 'Enable Lazy Loading', 'preload-everything' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Checking this box will enable Lazy Loading. This will only preload those links which are in viewport, same way image lazy loading works. It is recommended to enable this as it will minimize unnecessary preloads.', 'preload-everything' ),
				),
			),
		);

		/**
		 * Filters the plugin settings fields.
		 *
		 * This filter allows you to modify the plugin settings fields.
		 * You can use this filter to add/remove/edit any settings field.
		 *
		 * @since     2.0.0
		 * @param     array $settings_fields Default settings fields.
		 * @return    array $settings_fields Modified settings fields.
		 */
		return apply_filters( 'pre_ev_settings_fields', $settings_fields );
	}
}
