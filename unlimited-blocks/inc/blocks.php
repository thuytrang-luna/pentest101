<?php
if (!defined('ABSPATH')) exit;
// Register your block 
// unlimited_blocks_register_block_fn('test-block', $extraFeature = []);
unlimited_blocks_register_block_fn('icon-image-content');
unlimited_blocks_register_block_fn('icon-block');
unlimited_blocks_register_block_fn('block-column');
unlimited_blocks_register_block_fn('block-column-parent');
// unlimited_blocks_register_block_fn('pricing-table-section');
unlimited_blocks_register_block_fn('pricing-table-table');
unlimited_blocks_register_block_fn('progress-block');
unlimited_blocks_register_block_fn('progress-bar-pie');
unlimited_blocks_register_block_fn('slide');
unlimited_blocks_register_block_fn('ubl-column-block-column');
unlimited_blocks_register_block_fn('ubl-column-block-wrapper');
// unlimited_blocks dynamic blocks
include_once "block-callback/post-list-layout.php";
include_once "block-callback/post-slider-block.php";
include_once "block-callback/post-category-layout.php";
include_once "block-callback/post-grid-layout.php";
// post image layout
include_once "block-callback/post-image-layout.php";
include_once "block-callback/post-image-layout-two-post.php";
include_once "block-callback/post-image-layout-three-post.php";
include_once "block-callback/post-image-layout-four-post.php";
include_once "block-callback/post-image-layout-five-post.php";
include_once "block-callback/post-image-layout-six-post.php";
