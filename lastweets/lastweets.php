<?php
/**
 * Plugin Name: Lastweets
 * Description: Display a Twitter account latest tweets.
 * Author: Pierre Saïkali
 * Author URI: https://saika.li
 * Text Domain: lastweets
 * Domain Path: /languages/
 * Version: 1.0.0
 */

namespace Lastweets;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define plugin constants
 */
define( 'LASTWEETS_VERSION', '1.0.0' );
define( 'LASTWEETS_URL', plugin_dir_url( __FILE__ ) );
define( 'LASTWEETS_DIR', plugin_dir_path( __FILE__ ) );
define( 'LASTWEETS_PLUGIN_DIRNAME', basename( rtrim( dirname( __FILE__ ), '/' ) ) );
define( 'LASTWEETS_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Load our Composer dependencies
 */
function load_dependencies() {
	require_once plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';
	\Carbon_Fields\Carbon_Fields::boot();
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\load_dependencies' );

/**
 * Load translation
 */
load_plugin_textdomain( 'lastweets', false, LASTWEETS_PLUGIN_DIRNAME . '/languages/' );

/**
 * Installation function to do some required things.
 *
 * @return void
 */
function install() {
	require_once LASTWEETS_DIR . 'includes/options.php';
	\Lastweets\Options\set_default_options();
}
register_activation_hook( __FILE__, 'Lastweets\\install' );

/**
 * Register required files.
 */
function fire() {
	$files = [ 'utils', 'functions', 'api', 'assets', 'options', 'gutenblock' ];

	foreach ( $files as $file ) {
		require_once LASTWEETS_DIR . "includes/{$file}.php";
	}
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\fire' );
