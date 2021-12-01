<?php
/**
 * Plugin Name: Anything Block
 * Plugin URI:  https://wplemon.com
 * Author:      Ari Stathopoulos
 * Author URI:  http://aristath.github.io
 * Version:     1.0.2
 * Description: Print any kind of data, any way you want it.
 * Text Domain: anything-block
 *
 * @package   anything-block
 * @category  Core
 * @author    Ari Stathopoulos
 * @copyright Copyright (c) 2020, Ari Stathopoulos
 * @license   https://opensource.org/licenses/MIT
 * @since     1.0.0
 */

/**
 * Register the block.
 *
 * @since 1.0.0
 * @return void
 */
function wplemon_anything_block_init() {
	wp_register_script(
		'anything-block',
		plugins_url( 'block.js', __FILE__ ),
		[ 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components', 'wp-editor', 'wp-server-side-render', 'wp-data' ],
		filemtime( __DIR__ . '/block.js' ),
		false
	);

	register_block_type(
		'wplemon/anything',
		[
			'attributes'      => [
				'htmlData'       => [
					'type'    => 'text',
					'default' => '',
				],
				'dataSourceName' => [ // Backwards-compatibility.
					'type'    => 'text',
					'default' => '',
				],
				'dataSource'     => [ // Backwards-compatibility.
					'type'    => 'text',
					'default' => 'anything',
				],
			],
			'editor_script'   => 'anything-block',
			'render_callback' => 'wplemon_anything_block_render_callback',
		]
	);
}

/**
 * Render callback for dynamic block.
 *
 * @since 1.0.0
 * @param array  $atts The block attributes.
 * @param string $content The block content.
 * @return string
 */
function wplemon_anything_block_render_callback( $atts, $content ) {
	if ( ! isset( $atts['dataSource'] ) || ! isset( $atts['htmlData'] ) ) {
		return '';
	}

	$value = '';
	$html  = $atts['htmlData'];

	if ( isset( $atts['dataSource'] ) && 'anything' !== $atts['dataSource'] ) { // Backwards-compatibility.
		if ( isset( $atts['dataSourceName'] ) && '' !== $atts['dataSourceName'] ) {
			$html = str_replace( '{data}', "{data.{$atts['dataSource']}.{$atts['dataSourceName']}}", $html );
		} else {
			$html = str_replace( '{data.', "{data.{$atts['dataSource']}.", $html );
		}
	}

	$value = [
		'setting'  => json_decode( wp_json_encode( wp_load_alloptions() ), true ),
		'themeMod' => json_decode( wp_json_encode( get_theme_mods() ), true ),
		'post'     => json_decode( wp_json_encode( get_post( get_the_ID() ) ), true ),
	];

	$value['post']['meta'] = json_decode( wp_json_encode( get_post_meta( get_the_ID() ) ), true );

	$string_parts = wplemon_anything_get_string_parts( $html );
	foreach ( $string_parts as $string_part ) {
		$search  = str_replace( 'anythingData', 'data', $string_part );
		$replace = wplemon_anything_get_part_value( $string_part, $value );
		if ( 'data' !== $search && is_string( $replace ) ) {
			$html = str_replace(
				'{' . $search . '}',
				$replace,
				$html
			);
		}
	}
	return $html;
}

add_action( 'init', 'wplemon_anything_block_init' );

/**
 * Get search strings from our HTML.
 *
 * @since 1.1.0
 * @param string $html The HTML.
 * @return array
 */
function wplemon_anything_get_string_parts( $html ) {
	$the_parts = [];

	$parts = explode( '{data', $html );
	foreach ( $parts as $part ) {
		$the_parts[] = 'anythingData' . explode( '}', $part )[0];
	}
	return $the_parts;
}

/**
 * Get the value of a part.
 *
 * @since 1.1.0
 * @param string $part The part we're looking for.
 * @param array  $values Where we're looking for the part.
 * @return string
 */
function wplemon_anything_get_part_value( $part, $values ) {
	if ( is_string( $values ) || is_bool( $values ) || is_numeric( $values ) ) {
		return (string) $values;
	}
	$values = (array) $values;

	$part = str_replace( 'anythingData.', '', $part );
	if ( isset( $values[ $part ] ) && ! is_array( $values[ $part ] ) && ! is_object( $values[ $part ] ) ) {
		return $values[ $part ];
	}

	if ( false !== strpos( $part, '.' ) ) {
		$fragment = explode( '.', $part )[0];

		if ( isset( $values[ $fragment ] ) ) {
			return wplemon_anything_get_part_value( str_replace( "$fragment.", '', $part ), $values[ $fragment ] );
		}
	}

	if ( ! is_array( $values ) ) {
		return $values;
	}

	if ( current_user_can( 'update_core' ) ) {
		$debug  = '<table style="font-size:0.6em;background:#fef8ee;color:#333;border:1px solid #f0b849;white-space:pre-wrap" border="1">';
		$debug .= '<thead><tr><td>' . esc_html__( 'Name', 'anything-block' ) . '</td><td>' . esc_html__( 'Type', 'anything-block' ) . '</td><td>' . esc_html__( 'Value', 'anything-block' ) . '</td></tr</thead>';
		foreach ( $values as $key => $val ) {
			$debug .= '<tr>';
			$debug .= "<td style='vertical-align:top'><code>$key</code></td>";
			$debug .= '<td style="vertical-align:top"><code>' . gettype( $val ) . '</td>';
			if ( is_array( $val ) || is_object( $val ) ) {
				$val    = (array) $val;
				$debug .= '<td style="vertical-align:top"><ul><li><code>' . implode( '</code></li><li><code>', array_keys( $val ) ) . '</code></li></ul></td>';
			} else {
				$debug .= "<td style='vertical-align:top'><code>$val</code></td>";
			}
			$debug .= '</tr>';
		}
		$debug .= '</table>';

		return $debug;
	}
	return '';
}
