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

namespace Dudley;

// This plugin requires a Composer autoloader. Throw a notice and return if we don't have one.
$autoload = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

if ( ! file_exists( $autoload ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'src/Dudley/helpers.php';

	add_action( 'admin_notices', '\Dudley\notify_no_autoloader' );
	return;
}

// Load plugin dependencies.
require_once $autoload;

// Initialize.
$plugin = new Dudley();
$plugin->run();
