<?php
/**
 * Plugin Name: Web Recipe Clipper
 * Description: Web Recipe Clipper — Sometimes we want to save a recipe that we find on the web in our own blog post, you can use this plugin to add a block to gutenberg editor, then you can paste the web recipe url. It will generate a recipe in your editor based on the web recipe title, photo, description, ingredients and instructions, you can edit it, change it to your own version then publish it.
 * Author: LeanCodes
 * Author URI: https://www.leancodes.com
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
