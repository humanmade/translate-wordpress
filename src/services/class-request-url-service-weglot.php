<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Util\Url;
use Weglot\Util\Server;

use WeglotWP\Models\Mediator_Service_Interface_Weglot;

/**
 * Request URL
 *
 * @since 2.0
 */
class Request_Url_Service_Weglot implements Mediator_Service_Interface_Weglot {
	/**
	 * @since 2.0
	 *
	 * @var string
	 */
	protected $weglot_url = null;

	/**
	 * @since 2.0
	 * @see Mediator_Service_Interface_Weglot
	 * @param array $services
	 * @return void
	 */
	public function use_services( $services ) {
		$this->option_services = $services['Option_Service_Weglot'];
	}

	/**
	 * Use for abstract \Weglot\Util\Url
	 *
	 * @param string $url
	 * @return Weglot\Util\Url
	 */
	public function create_url_object( $url ) {
		return new Url(
			$url,
			$this->option_services->get_option( 'original_language' ),
			$this->option_services->get_option( 'destination_language' ),
			$this->get_home_wordpress_directory()
		);
	}

	/**
	 * @since 2.0
	 *
	 * @return string
	 */
	public function init_weglot_url() {
		$exclude_urls = $this->option_services->get_option( 'exclude_urls' );

		$this->weglot_url = new Url(
			$this->get_full_url(),
			$this->option_services->get_option( 'original_language' ),
			$this->option_services->get_option( 'destination_language' ),
			$this->get_home_wordpress_directory()
		);

		// @todo : Add : $this->weglot_url->setExcludedUrls( $exclude_urls ); phpcs:ignore

		return $this;
	}

	/**
	 * Get request URL in process
	 * @since 2.0
	 * @return \Weglot\Util\Url
	 */
	public function get_weglot_url() {
		if ( null === $this->weglot_url ) {
			$this->init_weglot_url();
		}

		return $this->weglot_url;
	}

	/**
	 * Abstraction of \Weglot\Util\Url
	 * @since 2.0
	 * @return string
	 */
	public function get_current_language() {
		return $this->get_weglot_url()->detectCurrentLanguage();
	}

	/**
	 * Abstraction of \Weglot\Util\Url
	 * @since 2.0
	 *
	 * @return boolean
	 */
	public function is_translatable_url() {
		return $this->get_weglot_url()->isTranslable();
	}


	/**
	 * @since 2.0
	 *
	 * @return string
	 * @param mixed $use_forwarded_host
	 */
	public function get_full_url( $use_forwarded_host = false ) {
		return Server::fullUrl($_SERVER, $use_forwarded_host); //phpcs:ignore
	}


	/**
	 * @todo : Change this when weglot-php included
	 *
	 * @param string $code
	 * @return boolean
	 */
	public function is_language_rtl( $code ) {
		$rtls = [ 'ar', 'he', 'fa' ];
		if ( in_array( $code, $rtls, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @since 2.0
	 *
	 * @return string|null
	 */
	public function get_home_wordpress_directory() {
		$opt_siteurl   = trim( get_option( 'siteurl' ), '/' );
		$opt_home      = trim( get_option( 'home' ), '/' );
		if ( empty( $opt_siteurl ) || empty( $opt_home ) ) {
			return null;
		}

		if (
			( substr( $opt_home, 0, 7 ) === 'http://' && strpos( substr( $opt_home, 7 ), '/' ) !== false) || ( substr( $opt_home, 0, 8 ) === 'https://' && strpos( substr( $opt_home, 8 ), '/' ) !== false ) ) {
			$parsed_url = parse_url( $opt_home ); // phpcs:ignore
			$path       = isset( $parsed_url['path'] ) ? $parsed_url['path'] : '/';
			return $path;
		}

		return null;
	}
}


