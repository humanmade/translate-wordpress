<?php

use WeglotWP\Services\Translate_Service_Weglot;
use WeglotWP\Services\Href_Lang_Service_Weglot;
use Weglot\Client\Api\Enum\BotType;
use Weglot\Parser\ConfigProvider\ManualConfigProvider;

class LinkTest extends WP_UnitTestCase {
	public function test_link_hashtag() {
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

		$translate_page_weglot = new Translate_Service_Weglot();
		$translate_page_weglot->set_original_language( 'en' );
		$translate_page_weglot->set_current_language( 'fr' );
		$content               = $translate_page_weglot->weglot_treat_page( file_get_contents( __DIR__ . '/templates/twentyseventeen.html' ) ); //phpcs:ignore

		$dom = \WGSimpleHtmlDom\str_get_html(
			$content,
			true,
			true,
			WG_DEFAULT_TARGET_CHARSET,
			false
		);

		$this->assertEquals( 'http://weglot-plugin.local/fr/#propos' ,  $dom->find( '#menu-item-53 a', 0 )->href );
	}
}
