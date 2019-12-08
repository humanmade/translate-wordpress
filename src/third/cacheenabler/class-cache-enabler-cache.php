<?php

namespace WeglotWP\Third\CacheEnabler;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WeglotWP\Helpers\Helper_Is_Admin;
use WeglotWP\Models\Hooks_Interface_Weglot;

/**
 * Cache_Enabler_Cache
 *
 * @since 3.1.4
 */
class Cache_Enabler_Cache implements Hooks_Interface_Weglot {

	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function __construct() {
		$this->cache_enabler_active = weglot_get_service( 'Cache_Enabler_Active' );
	}

	/**
	 * @since 3.1.4
	 * @see Hooks_Interface_Weglot
	 * @return void
	 */
	public function hooks() {

		if ( ! $this->cache_enabler_active->is_active() ) {
			return;
		}

		add_filter( 'bypass_cache', [ $this, 'bypass_cache' ] );
		add_filter( 'get_footer', [ $this, 'add_default_switcher' ] );
	}

	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function bypass_cache( $bypass_cache ) {

		if ( ! function_exists( 'weglot_get_current_and_original_language' ) ) {
			return $bypass_cache;
		}

		$languages = weglot_get_current_and_original_language();

		return ( $languages['current'] !== $languages['original'] );
	}


	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function add_default_switcher() {

		if ( ! function_exists( 'weglot_get_current_and_original_language' ) ) {
			return;
		}

		$languages = weglot_get_current_and_original_language();

		if ( $languages['current'] === $languages['original'] ) {
			echo weglot_get_button_selector_html( 'weglot-default' );
		}
	}

}
