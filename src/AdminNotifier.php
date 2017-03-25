<?php
namespace Tfive\Patterns;

/**
 * Class AdminNotifier
 * @package Tfive\Patterns
 */
class AdminNotifier {
	/**
	 *
	 */
	public function missing_acf_requirement() {
		add_action( 'admin_notices', function() {
			$this->print_error_notice(
				__(
					'3five ACF Patterns requires Advanced Custom Fields Pro v5.0 or higher. Please activate ACF or
					deactivate the 3five ACF Patterns plugin to dismiss this message.',
					'tfacf'
				)
			);
		} );
	}

	/**
	 * Throw a notice if the plugin doensn't have a Composer autoload_classmap, because otherwise we can't identify
	 * which patterns are available (and consequently, no actions get registered with WordPress).
	 */
	public function missing_autoload_classmap() {
		add_action( 'admin_notices', function() {
			$this->print_error_notice(
				__(
					'3five ACF Patterns is installed, but patterns cannot be located. In the terminal, from the plugin\'s 
				root directory, please run "composer update -a" to generate a classmap and dismiss this message.',
					'tf_acf'
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
