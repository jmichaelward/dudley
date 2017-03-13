<?php
/**
 * Plugin Name: 3five Patterns
 * Description: Store and render reusable content modules.
 * Plugin URI: http://3five.com
 * Author: Jeremy Ward, 3five
 * Author URI: http://3five.com
 * textdomain: tf_patterns
 *
 * @package Tfive\Patterns
 */

namespace Tfive\Patterns;

$autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// This plugin requires a Composer autoloader. Exit if we don't have one.
if ( ! file_exists( $autoload ) ) {
	return;
}

// Load plugin dependencies.
require_once $autoload;
require_once 'functions.php';
require_once plugin_root() . 'src/Patterns.php';

// Initialize.
$plugin = new Patterns();
$plugin->run();

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, [ $plugin, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin, 'deactivate' ] );
