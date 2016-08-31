<?php
namespace Tfive\ACF;

/**
 * Class AdminNotifier
 * @package Tfive\ACF
 */
class AdminNotifier {
	/**
	 *
	 */
	public function missing_acf_requirement() {
		add_action( 'admin_notices', function() {
			echo wp_kses( '<p>' . esc_html__( '3five ACF Patterns requires Advanced Custom Fields Pro v5.0 or higher.' ) . '</p>', array( 'p' => array() ) );
		} );
		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	/**
	 * Throw a notice if the plugin doensn't have a Composer autoload_classmap, because otherwise we can't identify
	 * which patterns are available (and consequently, no actions get registered with WordPress).
	 */
	public function missing_autoload_classmap() {
		add_action( 'admin_notices', function() {
			$class   = 'notice notice-error';
			$message = __(
				'3five ACF Patterns is installed, but patterns cannot be located. In the terminal, from the plugin\'s 
				root directory, please run "composer update -a" to generate a classmap and dismiss this message.',
				'tf_acf'
			);

			printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
		} );
	}
}
