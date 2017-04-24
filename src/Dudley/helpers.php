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

/**
 * Alert the administrator that a Composer autoloader has not yet been generated.
 */
function notify_no_autoloader() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p>%1$s</p></div>',
		esc_html__(
			'The Dudley plugin does not have a Composer autoloader. Please navigate to the plugin directory and run "composer install".',
			'dudley'
		)
	);
}
