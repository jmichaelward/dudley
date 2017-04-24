<?php
namespace Dudley\Admin;

/**
 * Class Notifier
 * @package Dudley\Patterns
 */
class Notifier {
	/**
	 *
	 */
	public function missing_acf_requirement() {
		add_action( 'admin_notices', function() {
			$this->print_error_notice(
				__(
					'Dudley requires Advanced Custom Fields Pro v5.0 or higher. Please activate ACF or
					deactivate the Dudley plugin to dismiss this message.',
					'dudley'
				)
			);
		} );
	}

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
	 * @param $message
	 */
	public function print_error_notice( $message ) {
		printf( '<div class="notice notice-error"><p>%1$s</p></div>', $message );
	}
}
