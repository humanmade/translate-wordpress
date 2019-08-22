<?php

namespace WeglotWP\Services;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Weglot\Client\Api\WordEntry;
use Weglot\Client\Api\Enum\WordType;
use Weglot\Client\Client;
use Weglot\Client\Endpoint\Translate;
use Weglot\Client\Api\TranslateEntry;
use Weglot\Client\Api\Enum\BotType;

use WeglotWP\Helpers\Helper_Json_Inline_Weglot;


/**
 *
 * @since 2.0
 */
class Other_Translate_Service_Weglot {
	protected $max_chars = 500;

	/**
	 * @since 3.0.0
	 */
	public function __construct() {
		$this->parser_services = weglot_get_service( 'Parser_Service_Weglot' );
	}

	/**
	 * @since 2.0
	 *
	 * @param string $content
	 * @return string
	 */
	public function translate_words( $content ) {
		$words = apply_filters( 'weglot_words_translate', [] );

		if ( empty( $words ) || ! is_array( $words ) ) {
			return $content;
		}

		// TranslateEntry
		$params = [
			'language_from' => weglot_get_original_language(),
			'language_to'   => weglot_get_current_language(),
			'request_url'   => weglot_get_current_full_url(),
			'bot'           => BotType::HUMAN,
		];

		$translate = new TranslateEntry( $params );

		$word_collection = $translate->getInputWords();

		foreach ( $words as $value ) {
			if ( strlen( $value ) > $this->max_chars && preg_match( '/\s|。|｡|︒/u', $value ) === false ) {
				continue;
			}

			$value = Helper_Json_Inline_Weglot::format_for_api( $value );
			$word_collection->addOne( new WordEntry( $value, WordType::TEXT ) );
		}

		$client           = $this->parser_services->get_client();
		$translate        = new Translate( $translate, $client );

		$object = $translate->handle();

		foreach ( $object->getInputWords() as $key => $input_word ) {
			$from_input = $input_word->getWord();

			$input_unformat_from_api = apply_filters( 'weglot_other_words_input_unformat_from_api', true );
			$output_unformat_from_api = apply_filters( 'weglot_other_words_unformat_from_api', true );
			$from_input               = $from_input = $input_word->getWord();
			$to_output                = $object->getOutputWords()[ $key ]->getWord();

			if ( $input_unformat_from_api ) {
				$from_input  = Helper_Json_Inline_Weglot::unformat_from_api( $from_input );
			}
			if ( $output_unformat_from_api ) {
				$to_output  = Helper_Json_Inline_Weglot::unformat_from_api( $to_output );
			}

			$from_input = preg_quote( $from_input ); // To avoid special char like | to be interpreted as regex.
			if ( ! preg_match( "#<[^>\"']*" . $from_input . "[^>\"']*>#", $content ) ) {
				$content = preg_replace( "#\b$from_input\b#", $to_output, $content );
			}
		}

		return $content;
	}
}
