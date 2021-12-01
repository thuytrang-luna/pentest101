<?php

namespace Lastweets\Assets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts and styles
 */
function enqueue_assets() {
	if ( ! \Lastweets\Options\get( 'lastweets_load_css' ) || apply_filters( 'lastweets/disable_default_css_loading', false ) ) {
		return;
	}

	wp_enqueue_style( 'lastweets/theme', LASTWEETS_URL . 'assets/css/theme.css', [], LASTWEETS_VERSION );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets' );
