<?php
/**
 * Plugin Name: Boxer Block
 * Description: Boxer puts your WordPress posts into boxes.
 * Version: 1.0
 * Author: CK Lee
 * Text Domain: boxer
 *
 * @package boxer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function boxer_register_block() {

	if ( ! function_exists( 'register_block_type' ) ) {
		// Gutenberg is not active.
		return;
	}

	wp_register_script(
		'boxer',
		plugins_url( 'build/index.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ),
		filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )
	);

	wp_register_style(
		'boxer',
		plugins_url( 'style.css', __FILE__ ),
		array(),
		filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
	);

	wp_register_script(
		'boxer',
		plugins_url( 'block.js', __FILE__ ),
		array( 'wp-blocks', 'wp-element', 'wp-data' )
	);

	register_block_type( 'boxer/boxer', array(
		'style' => 'boxer',
		'editor_script' => 'boxer',
		'render_callback' => 'boxer_render_callback'
	) );
}

add_action( 'init', 'boxer_register_block' );


function boxer_react_view_assets() {

	wp_enqueue_script(
		'boxer/view',
		plugins_url( 'build/view.js', __FILE__ ),
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' )
	);
}

add_action( 'enqueue_block_assets', 'boxer_react_view_assets' );
