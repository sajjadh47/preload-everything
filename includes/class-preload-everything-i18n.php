<?php
/**
 * This file contains the definition of the Preload_Everything_I18n class, which
 * is used to load the plugin's internationalization.
 *
 * @package       Preload_Everything
 * @subpackage    Preload_Everything/includes
 * @author        Sajjad Hossain Sagor <sagorh672@gmail.com>
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since    2.0.0
 */
class Preload_Everything_I18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since     2.0.0
	 * @access    public
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'preload-everything',
			false,
			dirname( PRELOAD_EVERYTHING_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
