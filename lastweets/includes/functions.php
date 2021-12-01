<?php

namespace Lastweets\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Lastweets\Api;

/**
 * Display latest tweets
 *
 * @param string $account The twitter account to fetch tweets from.
 * @param integer $amount The number of tweets to display.
 * @param string $style The style of the tweets (oembed widget or custom display).
 * @param boolean $retweets Whether to display original tweets only, or include retweets too.
 * @return void
 */
function display_latest_tweets( $account = 'psaikali', $amount = 1, $style = 'oembed', $retweets = false ) {
	$tweets = Api\get_latest_tweets( $account, $amount, $retweets );

	if ( ! empty( $tweets ) ) {
		foreach ( $tweets as $tweet ) {
			switch ( $style ) {
				// Oembed widget.
				case 'oembed':
				default:
					global $wp_embed;

					if ( isset( $tweet->retweeted_status->id ) ) {
						$url = "https://twitter.com/{$tweet->retweeted_status->user->screen_name}/status/{$tweet->retweeted_status->id}";
					} else {
						$url = "https://twitter.com/{$tweet->user->screen_name}/status/{$tweet->id}";
					}

					do_action( 'lastweets/before_display_oembed', $url, $tweet );
					echo $wp_embed->shortcode( [], $url );
					do_action( 'lastweets/after_display_oembed', $url, $tweet );
					break;

				// Homemade widget.
				case 'theme':
					if ( isset( $tweet->retweeted_status->id ) ) {
						$tweet_object = $tweet->retweeted_status;
					} else {
						$tweet_object = $tweet;
					}

					do_action( 'lastweets/before_display_theme', $tweet_object );
					display_tweet_with_custom_theme( $tweet_object );
					do_action( 'lastweets/after_display_theme', $tweet_object );

					break;
			}
		}
	}
}

/**
 * Display tweet with custom theme
 *
 * @param object $tweet Tweet data
 * @return void
 */
function display_tweet_with_custom_theme( $tweet ) {
	$file_name                 = 'single_tweet.php';
	$theme_template_file_path  = "templates/lastweets-{$file_name}";
	$plugin_template_file_path = LASTWEETS_DIR . "templates/{$file_name}";

	// Find our template file in theme/child-theme or fallback to plugin template file.
	$template_file_path = locate_template( $theme_template_file_path, false );
	$template_file_path = ( ! empty( $template_file_path ) ) ? $template_file_path : $plugin_template_file_path;
	$template_file_path = apply_filters( 'lastweets/custom_theme_path', $template_file_path );

	if ( ! file_exists( $template_file_path ) ) {
		return;
	}

	include $template_file_path;
}

/**
 * Refactor a tweet object coming from API.
 *
 * @param object $tweet
 * @return object $tweet
 */
function refactor_tweet_object( $tweet ) {
	if ( isset( $tweet->retweeted_status->id ) ) {
		$tweet_object = $tweet->retweeted_status;
	} else {
		$tweet_object = $tweet;
	}

	return (object) [
		'id'              => $tweet_object->id,
		'url'             => "https://twitter.com/{$tweet_object->user->screen_name}/status/{$tweet_object->id}",
		'date'            => strtotime( $tweet_object->created_at ),
		'text'            => enhance_tweet_text( $tweet_object->full_text ),
		'retweets_count'  => $tweet_object->retweet_count,
		'favorites_count' => $tweet_object->favorite_count,
		'user'            => (object) [
			'name'        => $tweet_object->user->name,
			'screen_name' => $tweet_object->user->screen_name,
			'avatar'      => $tweet_object->user->profile_image_url_https,
			'url'         => "https://twitter.com/{$tweet_object->user->screen_name}",
		],
	];
}

/**
 * Enhance tweet text by making links and hashtags clickable
 *
 * @param string $text Tweet text content
 * @return string $text Enhanced tweet text content
 */
function enhance_tweet_text( $text ) {
	$text = preg_replace( '/#+([a-zA-ZÀ-ÖØ-öø-ÿ0-9_]+)/', '<a target="_blank" rel="nofollow" href="https://twitter.com/search?q=$1">$0</a>', $text );
	$text = preg_replace( '/@+([a-zA-Z0-9_]+)/', '<a target="_blank" rel="nofollow" href="https://twitter.com/$1">$0</a>', $text );
	$text = str_replace( [ '>http://', '>https://' ], [ '>', '>' ], make_clickable( $text ) );
	return $text;
}
