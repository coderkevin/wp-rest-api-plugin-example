<?php /*

********************************************************************************

Plugin Name: REST API Plugin - JQuery Example
Plugin URI:  https://github.com/coderkevin/wp-rest-api-plugin-jquery-example
Description: Example code for using the WordPress REST API in a plugin
Version:     0.1.0
Author:      Kevin Killingsworth
Author URI:  https://github.com/coderkevin
License:     GPL-3.0+
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Requires at least: 4.4

Text Domain: rest-api-plugin-jquery-example
Domain Path: /languages/

*******************************************************************************/

defined( 'ABSPATH' ) or die( 'No direct access.' );

/**
 * Main class.
 */
class REST_API_Plugin_JQuery_Example {

	const VERSION = '0.1.0';
	const TEXT_DOMAIN = 'rest-api-plugin-jquery-example';

	public function __construct() {
		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Initialize localization and admin menu.
	 */
	public function init() {
		$this->load_plugin_textdomain();
		add_action( 'admin_menu', array( $this, 'attach_menu' ) );
	}

	/**
	 * Load Localization files.
	 *
	 * Note: first loaded translation overrides following.
	 *
	 * This look for:
	 *   WP_LANG_DIR/rest-api-plugin-jquery-example/rest-api-plugin-jquery-example-LOCALE.mo
	 *   WP_LANG_DIR/plugins/rest-api-plugin-jquery-example-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		$domain = REST_API_Plugin_JQuery_Example::TEXT_DOMAIN;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
		$mo_file = $domain . '-' . get_locale() . '.mo';

		load_textdomain( $domain, WP_LANG_DIR . '/' + $domain + '/' . $mo_file );
		load_plugin_textdomain( $domain, false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Attaches the admin menu we'll use for our plugin.
	 */
	public function attach_menu() {
		$domain = REST_API_Plugin_JQuery_Example::TEXT_DOMAIN;

		add_menu_page(
			__( 'REST API Plugin Example Using jQuery', $domain ),
			__( 'REST API Example', $domain ),
			'read',
			'rest-api-plugin-jquery-example',
			array( $this, 'output_page' ),
			null,
			null
		);
	}

	/**
	 * Outputs the scripts and html for our page.
	 */
	public function output_page() {
		$domain = REST_API_Plugin_JQuery_Example::TEXT_DOMAIN;

		wp_enqueue_script( 'jquery' );

		wp_enqueue_script(
			'rest-api-plugin-jquery-example-js',
			plugins_url( 'assets/js/rest-example-jquery.js', __FILE__ ),
			array(),
			REST_API_Plugin_JQuery_Example::VERSION,
			true
		);

		wp_localize_script( 'rest-api-plugin-jquery-example-js', 'screen_data', array(
			'api_root' => esc_url_raw( rest_url() ),
			'api_nonce' => wp_create_nonce( 'wp_rest' ),
			'i18n' => array(
				'post'       => __( 'Post', $domain ),
				'author'     => __( 'Author', $domain ),
				'status'     => __( 'Status', $domain ),
				'publish'    => __( 'Published', $domain ),
				'future'     => __( 'Scheduled', $domain ),
				'draft'      => __( 'Draft', $domain ),
				'pending'    => __( 'Pending Review', $domain ),
				'private'    => __( 'Private', $domain ),
				'loading'    => __( '(loading)', $domain ),
			)
		) );

		?>
			<div class="rest-api-example">
				<h1><?php _e( 'REST API Plugin Example Using jQuery', $domain ) ?></h1>
				<p><?php _e( 'This is the example page for using the REST API from a plugin using jQuery!', $domain ) ?></p>
				<div id="post-search">
					<form id="search-form" class="search">
						<label>
							<?php _e( 'Search Posts:', $domain ) ?>
							<input id="search-box" type="search"></input>
						</label>
						<span id="search-message"></span>
					</form>

					<table id="post-table">
					</table>
				</div>
			</div>
		<?php
	}

}

return new REST_API_Plugin_JQuery_Example();

