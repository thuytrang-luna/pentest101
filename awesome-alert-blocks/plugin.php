<?php
/**
 * Plugin Name: Awesome Alert Blocks
 * Plugin URI: https://wordpress.org/plugins/awesome-alert-blocks
 * Description: awesome-alert-block — is a Gutenberg plugin created via create-guten-block.
 * Author: Raihan islam
 * Author URI: https://raihanislamcse.me/
 * Version: 1.0.0
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package CGB
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block Initializer.
 */
require_once plugin_dir_path( __FILE__ ) . 'src/init.php';
