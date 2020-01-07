<?php

use WeglotWP\Services\Translate_Service_Weglot;
use WeglotWP\Services\Href_Lang_Service_Weglot;
use Weglot\Client\Api\Enum\BotType;
use Weglot\Parser\ConfigProvider\ManualConfigProvider;

class HreflangPageTest extends WP_UnitTestCase {
	public function test_hreflang_page() {
		add_filter( 'weglot_get_api_key', function() {
			return ApiKey::get_api_key();
		});
		add_filter( 'weglot_get_options', function( $options ) {
			$options['language_from'] = 'en';
			$options['languages'] = [
				[
					'language_to' => 'fr',
					'enabled' => true,
				],
				[
					'language_to' => 'es',
					'enabled' => true,
				],
			];
			$options['allowed'] = true;
			return $options;
		});

		add_filter( 'weglot_translate_current_language', function() {
			return 'fr';
		});

		add_filter( 'weglot_parser_config_provider', function() {
			$config = new ManualConfigProvider( 'http://weglot-plugin.local', BotType::HUMAN, 'Homepage' );
			return $config;
		});

		add_filter( 'weglot_check_link_server_host', function() {
			return 'weglot-plugin.local';
		});

		$href_lang_service = new Href_Lang_Service_Weglot();
		$out               = $href_lang_service->generate_href_lang_tags(); //phpcs:ignore

		$this->assertNotFalse( strpos( $out, 'hreflang="fr"' ) );
		$this->assertNotFalse( strpos( $out, 'hreflang="es"' ) );
	}

	public function test_hreflang_private_page() {
		add_filter( 'weglot_get_api_key', function() {
			return ApiKey::get_api_key();
		});

		add_filter( 'weglot_get_options', function( $options ) {
			$options['language_from'] = 'en';
			$options['languages'] = [
				[
					'language_to' => 'fr',
					'enabled' => true,
				],
				[
					'language_to' => 'es',
					'enabled' => false,
				],
			];
			$options['allowed'] = true;
			return $options;
		});

		add_filter( 'weglot_translate_current_language', function() {
			return 'fr';
		});

		add_filter( 'weglot_parser_config_provider', function() {
			$config = new ManualConfigProvider( 'http://weglot-plugin.local', BotType::HUMAN, 'Homepage' );
			return $config;
		});

		add_filter( 'weglot_check_link_server_host', function() {
			return 'weglot-plugin.local';
		});

		$href_lang_service = new Href_Lang_Service_Weglot();
		$out               = $href_lang_service->generate_href_lang_tags(); //phpcs:ignore

		$this->assertNotFalse( strpos( $out, 'hreflang="fr"' ) );
		$this->assertFalse( strpos( $out, 'hreflang="es"' ) );
	}

	public function test_hreflang_with_autoswitch() {
		global $wp_query;
		$wp_query->is_home = true;

		add_filter( 'weglot_get_api_key', function() {
			return ApiKey::get_api_key();
		});

		add_filter( 'weglot_get_options', function( $options ) {
			$options['language_from'] = 'en';
			$options['languages'] = [
				[
					'language_to' => 'fr',
					'enabled' => true,
				],
				[
					'language_to' => 'es',
					'enabled' => true,
				],
			];
			$options['auto_switch'] = true;
			$options['allowed'] = true;
			return $options;
		});

		add_filter( 'weglot_translate_current_language', function() {
			return 'fr';
		});

		add_filter( 'weglot_parser_config_provider', function() {
			$config = new ManualConfigProvider( 'http://weglot-plugin.local/fr/', BotType::HUMAN, 'Homepage' );
			return $config;
		});

		add_filter( 'weglot_check_link_server_host', function() {
			return 'weglot-plugin.local';
		});

		$href_lang_service = new Href_Lang_Service_Weglot();
		$out               = $href_lang_service->generate_href_lang_tags(); //phpcs:ignore

		$this->assertFalse( strpos( $out, 'no_lredirect=true' ) );
		$this->assertFalse( strpos( $out, ' hreflang="en" href="?no_lredirect=true"' ) );
		$this->assertNotFalse( strpos( $out, ' hreflang="es" href="http://example.org/es/"' ) );
	}
}
