<?php

namespace Lastweets\Api;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lastweets\Options;

/**
 * Get latest tweets of an account
 *
 * @param string $account The twitter username
 * @param int $amount Number of tweets to retrieve
 * @return mixed Array of tweets or null
 */
function get_latest_tweets( $account = 'psaikali', $amount = 1, $retweets = false ) {
	$amount  = (int) $amount;
	$account = ltrim( (string) $account, '@' );

	if ( strlen( $account ) === 0 || $amount === 0 ) {
		return null;
	}

	if ( api_keys_are_defined() ) {
		$transient_key  = "lastweets_data_{$account}_{$amount}_" . (int) $retweets;
		$transient_name = sanitize_key( $transient_key );

		if ( false === ( $tweets = get_transient( $transient_name ) ) ) {
			$settings = [
				'oauth_access_token'        => Options\get( 'lastweets_access_token' ),
				'oauth_access_token_secret' => Options\get( 'lastweets_access_token_secret' ),
				'consumer_key'              => Options\get( 'lastweets_consumer_key' ),
				'consumer_secret'           => Options\get( 'lastweets_consumer_secret' ),
			];

			$params = "?count={$amount}&result_type=recent&tweet_mode=extended&include_rts=false&q=from:{$account}";

			if ( ! $retweets ) {
				$params .= ' AND -filter:retweets AND -filter:replies';
				$params .= '&exclude_replies=true';
			}

			$url = 'https://api.twitter.com/1.1/search/tweets.json';

			try {
				$twitter = new \TwitterAPIExchange( $settings );
				$tweets   = json_decode( $twitter->setGetfield( $params )->buildOauth( $url, 'GET' )->performRequest() );
			} catch ( \Exception $e ) {
				return null;
			}

			if ( isset( $tweets->error ) || isset( $tweets->errors ) || ! isset( $tweets->statuses ) || empty( $tweets->statuses ) ) {
				return null;
			}

			$tweets = array_slice( $tweets->statuses, 0, $amount );

			set_transient( $transient_name, $tweets, (int) Options\get( 'lastweets_fetch_every' ) * MINUTE_IN_SECONDS );
		}

		if ( is_array( $tweets ) ) {
			return $tweets;
		}
	}

	return null;
}

/**
 * Check if API keys are defined.
 *
 * @return boolean
 */
function api_keys_are_defined() {
	return (
		strlen( trim( Options\get( 'lastweets_consumer_key' ) ) ) > 0 &&
		strlen( trim( Options\get( 'lastweets_consumer_secret' ) ) ) > 0 &&
		strlen( trim( Options\get( 'lastweets_access_token' ) ) ) > 0 &&
		strlen( trim( Options\get( 'lastweets_access_token_secret' ) ) ) > 0
	);
}
