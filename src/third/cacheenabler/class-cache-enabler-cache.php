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
		$this->cache_enabler_active      = weglot_get_service( 'Cache_Enabler_Active' );
		$this->generate_switcher_service = weglot_get_service( 'Generate_Switcher_Service_Weglot' );
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
		add_action( 'wp_head', [ $this, 'buffer_start' ] );
	}

	/**
	 * @since 3.1.4
	 * @return bool
	 */
	public function bypass_cache( $bypass_cache ) {

		if ( $bypass_cache || ! function_exists( 'weglot_get_current_and_original_language' ) ) {
			return $bypass_cache;
		}

		$languages = weglot_get_current_and_original_language();

		if ( ( $languages['current'] !== $languages['original'] ) ) {
			if ( ! defined( 'DONOTCACHEPAGE' ) ) {
				define( 'DONOTCACHEPAGE', 1 );
			}
			return true;
		}

		return $bypass_cache;
	}

	/**
	 * @since 3.1.4
	 * @return void
	 */
	public function buffer_start() {
		ob_start( [ $this, 'add_default_switcher' ] );
	}

	/**
	 * @since 3.1.4
	 * @return string
	 */
	public function add_default_switcher( $dom ) {

		if ( ! function_exists( 'weglot_get_current_and_original_language' ) ) {
			return;
		}

		return $this->generate_switcher_service->generate_switcher_from_dom( $dom );
	}

}
