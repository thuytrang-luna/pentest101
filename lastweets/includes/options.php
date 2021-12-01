<?php

namespace Lastweets\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Carbon_Fields\Carbon_Fields;
use Carbon_Fields\Container;
use Carbon_Fields\Field;

/**
 * Register our admin settings page, tabs and fields.
 *
 * @return void
 */
function options_initialize_admin_page() {
	$tabs = apply_filters( 'lastweets/options_tabs', [] );

	if ( empty( $tabs ) ) {
		return;
	}

	// Register our admin page.
	$theme_options = Container::make( 'theme_options', __( 'Lastweets', 'lastweets' ) );
	$theme_options->set_page_parent( 'options-general.php' );
	$theme_options->set_page_file( 'lastweets' );
	$theme_options->set_page_menu_title( 'Lastweets' );

	// Add tabs and fields.
	foreach ( $tabs as $tab_slug => $tab_title ) {
		$theme_options->add_tab(
			esc_html( $tab_title ),
			apply_filters( "lastweets/options_fields_tab_{$tab_slug}", [] )
		);
	}
}
add_action( 'carbon_fields_register_fields', __NAMESPACE__ . '\\options_initialize_admin_page' );

/**
 * Options tabs list
 *
 * @param array $tabs [] Initial (filtered) tabs array.
 * @return array $tabs Array of tabs.
 */
function options_set_tabs( $tabs ) {
	return [
		'general'  => __( 'General', 'lastweets' ),
		'theme' => __( 'Theme', 'lastweets' ),
	];
}
add_filter( 'lastweets/options_tabs', __NAMESPACE__ . '\\options_set_tabs' );

/**
 * "General" option tab fields
 *
 * @return array $fields The fields array
 */
function options_general_option_tab_fields() {
	$fields = [];

	// API keys.
	$fields[] = Field::make( 'text', 'lastweets_consumer_key', __( 'Consumer API Key', 'lastweets' ) )->set_required();
	$fields[] = Field::make( 'text', 'lastweets_consumer_secret', __( 'Consumer API secret key', 'lastweets' ) )->set_required();
	$fields[] = Field::make( 'text', 'lastweets_access_token', __( 'Access token ', 'lastweets' ) )->set_required();
	$fields[] = Field::make( 'text', 'lastweets_access_token_secret', __( 'Access token secret', 'lastweets' ) )->set_required();

	return $fields;
}
add_filter( 'lastweets/options_fields_tab_general', __NAMESPACE__ . '\\options_general_option_tab_fields', 10 );

/**
 * "Theme" option tab fields
 *
 * @return array $fields The fields array
 */
function options_theme_option_tab_fields() {
	$fields = [];

	// Load CSS styles?
	$fields[] = Field::make( 'checkbox', 'lastweets_load_css', __( 'Load plugin CSS styles to enhance tweets design.', 'lastweets' ) )->set_option_value( 'yes' )->set_default_value( 'yes' );
	$fields[] = Field::make( 'text', 'lastweets_fetch_every', __( 'Duration (in minutes) of tweets data cache', 'lastweets' ) )->set_required()->set_default_value( 30 );

	return $fields;
}
add_filter( 'lastweets/options_fields_tab_theme', __NAMESPACE__ . '\\options_theme_option_tab_fields', 10 );

/**
 * Display support / doc content in the sidebar
 *
 * @return void
 */
function display_content_after_sidebar() {
	?>
	<style>
		#lastweets-promo-box {
			background: #fff;
			border:1px solid #DADADA;
			padding:2rem;
			border-radius:5px;
			text-align: center;
		}

		#lastweets-promo-box h2 {
			font-size: 3rem;
			margin:0;
		}

		#lastweets-promo-box cite, #lastweets-promo-box .cite {
			font-style: italic;
		}

		#lastweets-promo-box hr {
			margin: 2rem 0;
		}

		#lastweets-promo-box footer {
			
		}
	</style>

	<div id="lastweets-promo-box" class="wp-core-ui">
		<h2>ℹ</h2>
		<h3><?php _e( 'Questions? Ideas?', 'lastweets' ); ?></h3>
		<p><?php _e( 'Feel free to use the plugin WordPress.org forum or GitHub repository issues tracker to ask for help or share ideas and suggestions.', 'lastweets' ); ?></p>
		<p><?php _e( 'I\'d be happy to help you out!', 'lastweets' ); ?></p>
		<ul>
			<li><a href="https://wordpress.org/support/plugin/lastweets" target="_blank"><?php _e( 'WordPress.org forum', 'lastweets' ); ?></a></li>
			<li><a href="https://github.com/psaikali/lastweets/issues" target="_blank"><?php _e( 'GitHub issues tracker', 'lastweets' ); ?></a></li>
		</ul>
		<p class="cite"><cite><a href="https://saika.li">— Pierre</a></cite></p>
		<hr>
		<footer>
			<p><a href="https://wordpress.org/support/plugin/lastweets/reviews/#new-post" target="_blank" class="button"><span class="dashicons dashicons-star-filled"></span> <?php _e( 'Leave a review', 'lastweets' ); ?></a></p>
		</footer>
	</div>
	<?php
}
add_action( 'carbon_fields_container_lastweets_after_sidebar', __NAMESPACE__ . '\\display_content_after_sidebar' );

/**
 * Get option value.
 *
 * @param string $option_name
 * @return mixed Option value
 */
function get( $option_name ) {
	return \carbon_get_theme_option( $option_name );
}

/**
 * Set default options when activating the plugin
 */
function set_default_options() {
	$load_css    = get_option( '_lastweets_load_css', null );
	$fetch_every = get_option( '_lastweets_fetch_every', null );

	if ( is_null( $load_css ) ) {
		update_option( '_lastweets_load_css', 'yes' );
	}

	if ( is_null( $fetch_every ) ) {
		update_option( '_lastweets_fetch_every', 30 );
	}
}

/**
 * Output the Settings link on the Plugins admin page
 *
 * @param array $links A list of existing links.
 * @return array A new list of links.
 */
function plugin_settings_link( $links ) {
	$settings_url = admin_url( 'options-general.php?page=lastweets' );
	$settings_link_tag = sprintf(
		'<a href="%1$s">%2$s</a>',
		esc_url( $settings_url ),
		__( 'Settings', 'lastweets' )
	);

	array_unshift( $links, $settings_link_tag );

	return $links;
}
add_filter( 'plugin_action_links_' . LASTWEETS_BASENAME, __NAMESPACE__ . '\\plugin_settings_link' );
