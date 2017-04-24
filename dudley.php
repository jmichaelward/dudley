<?php
/**
 * Plugin Name: Dudley
 * Description: A plugin framework for installing, customizing, and writing reusable content modules powered by meta fields.
 * Plugin URI: http://jmichaelward.com
 * Author: Jeremy Ward
 * Author URI: http://jmichaelward.com
 * Version: 1.0.0
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

// Initialize.
$plugin = new Patterns();
$plugin->run();

// Register activation and deactivation hooks.
register_activation_hook( __FILE__, [ $plugin, 'activate' ] );
register_deactivation_hook( __FILE__, [ $plugin, 'deactivate' ] );
