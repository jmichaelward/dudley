<?php
/**
 * Plugin Name: 3five ACF Patterns
 * Description: Store and render modular ACF content
 * Plugin URI: http://3five.com
 * Author: Jeremy Ward, 3five
 * Author URI: http://3five.com
 */
namespace Tfive\ACF;

/**
 * Class ACFPatterns
 * @package ACFPatterns
 */
final class Patterns
{
	/**
	 * @var $acf
	 */
	public $acf;

	/**
	 * ACF_Patterns constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->load_dependencies();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'admin_init', array( $this, 'check_requirements' ) );

		$this->acf = new ACF();
	}

	/**
	 *
	 */
	public function check_requirements() {
		if ( ! class_exists( 'acf' ) ) {
			add_action( 'admin_notices', array( $this, 'requirements_error' ) );
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}
	}

	/**
	 * Define constants used by the plugin
	 */
	private function define_constants() {
		// 3five Content Plugin Root Path
		if ( ! defined( 'ACFP_ROOT' ) ) {
			define( 'ACFP_ROOT', plugin_dir_path( __FILE__ ) );
		}

		// 3five Content Plugin Model Source
		if ( ! defined( 'ACFP_SRC_ROOT' ) ) {
			define( 'ACFP_SRC_ROOT', plugin_dir_path( __FILE__ ) . 'src' );
		}

		// 3five Content Plugin ThreeFiveACF Source
		if ( ! defined( 'ACFP_SRC' ) ) {
			define( 'ACFP_SRC', plugin_dir_path( __FILE__ ) . 'src/ACFPatterns' );
		}
	}


	/**
	 * Bootstrap needed dependencies
	 */
	public function load_dependencies() {
		include_once ACFP_SRC . '/ACF.php';
	}

	/**
	 *
	 */
	public function activate() {
		if ( ! class_exists( 'acf' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'Advanced Custom Fields Pro v5.0 or greater must be installed to use the 3five ACF Patterns plugin.' );
		}
	}

	/**
	 *
	 */
	public function deactivate() {}

	/**
	 *
	 */
	public function requirements_error() {
		echo wp_kses( '<p>' . esc_html__( '3five ACF Patterns requires Advanced Custom Fields Pro v5.0 or higher.' ) . '</p>', array( 'p' => array() ) );
	}
}

new Patterns();
