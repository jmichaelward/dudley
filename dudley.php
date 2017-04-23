<?php
/**
 * Plugin Name: Dudley
 * Description: Store and render reusable content modules.
 * Plugin URI: http://jmichaelward.com
 * Author: Jeremy Ward
 * Author URI: http://jmichaelward.com
 * textdomain: dudley
 *
 * @package Dudley\Patterns
 */

namespace Dudley\Patterns;

$autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// This plugin requires a Composer autoloader. Exit if we don't have one.
if ( ! file_exists( $autoload ) ) {
	return;
}

// Load plugin dependencies.
require_once $autoload;
require_once 'functions.php';

// Initialize.
$plugin = new Patterns();
$plugin->run();

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, [ $plugin, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin, 'deactivate' ] );
