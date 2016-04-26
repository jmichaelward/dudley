<?php

namespace ThreeFive;

use ThreeFiveACF\ACF;

/**
 * Plugin Name: 3five Patterns Library
 * Description: Register ACF modular content for use in custom themes.
 * Author: J. Michael Ward, 3five
 * Author URI: http://3five.com
 * Version: 0.1.0
 *
 * Class Patterns
 * @package ThreeFive
 */
final class Patterns
{
	/**
	 * @var $acf ACF
	 */
	private $acf;

	/**
	 * Patterns constructor.
	 */
	public function __construct() {
		// 3five Content Plugin Root Path
		if ( ! defined( 'TFP_ROOT' ) ) {
			define( 'TFP_ROOT', plugin_dir_path( __FILE__ ) );
		}

		// 3five Content Plugin Model Source
		if ( ! defined( 'TFP_SRC' ) ) {
			define( 'TFP_SRC', plugin_dir_path( __FILE__ ) . '/src' );
		}

		// 3five Content Plugin ThreeFiveACF Source
		if ( ! defined( 'ACFP_SRC' ) ) {
			define( 'ACFP_SRC', plugin_dir_path( __FILE__ ) . '/src/ThreeFiveACF' );
		}

		include_once ACFP_SRC . '/ACF.php';

		$this->acf = new ACF();
	}
}

new Patterns();
