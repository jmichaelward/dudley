<?php
/**
 * Admin Notifier class.
 *
 * Responsible for printing alerts to the admin when something isn't right with the plugin.
 *
 * @author Jeremy Ward <jeremy@jmichaelward.com>
 * @package Dudley\Admin
 */

namespace Dudley\Admin;

/**
 * Class Notifier
 *
 * @package Dudley\Admin
 */
class Notifier {
	/**
	 * Throw a notice if Dudley cannot find any installed patterns.
	 */
	public function patterns_not_found() {
		add_action( 'admin_notices', function() {
			$this->print_error_notice(
				__(
					'Dudley is installed, but patterns cannot be located. You can install them via Composer or write your own!',
					'dudley'
				)
			);
		} );
	}

	/**
	 * Print the error notice.
	 *
	 * @param string $message The error message to print.
	 */
	public function print_error_notice( $message ) {
		printf( '<div class="notice notice-error"><p>%1$s</p></div>', esc_html( $message ) );
	}
}
