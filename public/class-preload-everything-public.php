<?php
/**
 * This file contains the definition of the Preload_Everything_Public class, which
 * is used to load the plugin's public-facing functionality.
 *
 * @package       Preload_Everything
 * @subpackage    Preload_Everything/public
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version and other methods.
 *
 * @since    2.0.0
 */
class Preload_Everything_Public {
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
	 * Initialize the class and set its properties.
	 *
	 * @since     2.0.0
	 * @access    public
	 * @param     string $plugin_name The name of the plugin.
	 * @param     string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function enqueue_scripts() {
		$plugin_enabled = Preload_Everything::get_option( 'enable_plugin', 'pre_ev_basic_settings', 'off' );

		if ( 'on' === $plugin_enabled ) {
			wp_enqueue_script( $this->plugin_name, PRELOAD_EVERYTHING_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name,
				'PreloadEverything',
				array(
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'allowedHosts'      => Preload_Everything::get_option( 'preloading_url_host', 'pre_ev_basic_settings', 'internal' ),
					'cacheLifetime'     => Preload_Everything::get_option( 'cache_lifetime', 'pre_ev_basic_settings', 60 ),
					'enableLazyLoading' => 'on' === Preload_Everything::get_option( 'enable_lazy_loading', 'pre_ev_basic_settings', 'off' ),
				)
			);
		}
	}
}
