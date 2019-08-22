<?php

namespace WeglotWP\Actions\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Models\Hooks_Interface_Weglot;

use WeglotWP\Helpers\Helper_Pages_Weglot;
use WeglotWP\Helpers\Helper_Tabs_Admin_Weglot;

/**
 * Register pages administration
 *
 * @since 2.0
 *
 */
class Pages_Weglot implements Hooks_Interface_Weglot {

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->option_services     = weglot_get_service( 'Option_Service_Weglot' );
		$this->language_services   = weglot_get_service( 'Language_Service_Weglot' );
		$this->button_services     = weglot_get_service( 'Button_Service_Weglot' );
		$this->user_api_services   = weglot_get_service( 'User_Api_Service_Weglot' );
		$this->wc_active_services  = weglot_get_service( 'Wc_Active' );
		return $this;
	}

	/**
	 * @see Hooks_Interface_Weglot
	 *
	 * @since 2.0
	 * @return void
	 */
	public function hooks() {
		add_action( 'admin_menu', [ $this, 'weglot_plugin_menu' ] );
	}

	/**
	 * Add menu and sub pages
	 *
	 * @see admin_menu
	 *
	 * @since 2.0
	 * @return void
	 */
	public function weglot_plugin_menu() {
		add_menu_page( 'Weglot', 'Weglot', 'manage_options', Helper_Pages_Weglot::SETTINGS, [ $this, 'weglot_plugin_settings_page' ], WEGLOT_URL_DIST . '/images/weglot_fav_bw.png' );
	}

	/**
	 * Page settings
	 *
	 * @since 2.0
	 *
	 * @return void
	 */
	public function weglot_plugin_settings_page() {
		$this->tabs       = Helper_Tabs_Admin_Weglot::get_full_tabs();
		$this->tab_active = Helper_Tabs_Admin_Weglot::SETTINGS;

		delete_transient( 'weglot_cache_cdn' );

		if ( isset( $_GET['tab'] ) ) { // phpcs:ignore
			$this->tab_active = sanitize_text_field( wp_unslash( $_GET['tab'] ) ); // phpcs:ignore
		}

		$this->options = $this->option_services->get_options();

		try {
			$user_info = $this->user_api_services->get_user_info();
			$this->option_services->set_option_by_key( 'allowed', $user_info['allowed'] );
		} catch ( \Exception $e ) {
			// If an exception occurs, do nothing, keep wg_allowed.
		}

		include_once WEGLOT_TEMPLATES_ADMIN_PAGES . '/settings.php';
	}
}
