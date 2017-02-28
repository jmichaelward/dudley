<?php
namespace Tfive\Patterns;

/**
 * Some namespaced helper functions for the plugin, to minimize use of global constants.
 */

/**
 * Root file path for the plugin.
 *
 * @return string
 */
function plugin_root() {
	return plugin_dir_path( __FILE__ );
}
