<?php
/**
 * Plugin Name:       FSD Google Place Block
 * Plugin URI:        https://fullstackdigital.io
 * Description:       A plugin that allows fetching of place data in a gutenberg block
 * Version:           1.0.0
 * Requires at least: 5.9
 * Requires PHP:      7.2
 * Author:            Full Stack Digital
 * Author URI:        https://fullstackdigital.io
 * Text Domain:       fsdgpb
 */

if(!function_exists('add_action')) {
  echo 'Seems like you stumbled here by accident. 😛';
  exit;
}

// Setup
define('FSDGPB_DIR', plugin_dir_path(__FILE__));
define('FSDGPB_FILE', __FILE__);

// Includes
$rootFiles = glob(FSDGPB_DIR . 'includes/*.php');
$subdirectoryFiles = glob(FSDGPB_DIR . 'includes/**/*.php');
$allFiles = array_merge($rootFiles, $subdirectoryFiles);

foreach($allFiles as $filename) {
  include_once($filename);
}

// Hooks
// add_action('init', 'fsdgpb_register_assets');
// add_action('init', 'fsdgpb_register_blocks');
// add_action('init', 'fsdgpb_happy_hour_post_type');
// add_action('admin_menu', 'fsdgpb_register_my_api_keys_page');


// Register Blocks
function fsdgpb_register_blocks(){
  register_block_type( FSDGPB_DIR . '/build/blocks/happy-hour', array(
    'render_callback' => 'fsdgpb_happy_hour_render'
  ) );
}

// API Endpoint

// add_action('rest_api_init', function () {
//   register_rest_route( 'fsdgpb/v1', '/fetch-google-data', array(
//       'methods' => 'POST',
//       'callback' => 'fetch_google_place_data',
//       'permission_callback' => '__return_true'
//   ));
// });

