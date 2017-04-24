<?php
namespace Dudley;

/**
 * Some namespaced helper functions for the plugin, to minimize use of global constants.
 */

/**
 * Root file path for the plugin.
 *
 * @return string
 */
function plugin_root() {
	$path = explode( '/', plugin_basename( __FILE__ ) );
	return trailingslashit( WP_PLUGIN_DIR . '/' . $path[0] );
}
