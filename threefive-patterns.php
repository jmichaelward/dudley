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
	 * Path to the acf-json directory.
	 *
	 * @var string
	 */
	public $acf_json_path;

	/**
	 * ACF_Patterns constructor.
	 */
	public function __construct() {
		$autoload_file = plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

		if ( ! file_exists( $autoload_file ) ) {
			return;
		}

		require_once $autoload_file;

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		$this->acf_json_path = plugin_dir_path( __FILE__ ) . '/acf-json';
		$this->acf           = new ACF( $this->locate_patterns() );

		// Set ACF save and load paths
		add_filter( 'acf/settings/save_json', array( $this, 'acf_save_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'acf_load_path' ) );

		add_action( 'admin_init', array( $this, 'check_requirements' ) );
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
	 * Run on plugin activation.
	 */
	public function activate() {
		if ( ! class_exists( 'acf' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			wp_die( 'Advanced Custom Fields Pro v5.0 or greater must be installed to use the 3five ACF Patterns plugin.' );
		}
	}

	/**
	 * Run on plugin deactivation.
	 */
	public function deactivate() {}

	/**
	 *
	 */
	public function requirements_error() {
		echo wp_kses( '<p>' . esc_html__( '3five ACF Patterns requires Advanced Custom Fields Pro v5.0 or higher.' ) . '</p>', array( 'p' => array() ) );
	}

	/**
	 * @return string Set the ACF save path for new custom fields.
	 */
	public function acf_save_path()
	{
		return $this->acf_json_path;
	}

	/**
	 * @param $paths
	 *
	 * @return array
	 */
	public function acf_load_path( $paths )
	{
		unset( $paths[0] );

		$paths[] = $this->acf_json_path;

		return $paths;
	}

	/**
	 * @return array
	 */
	private function locate_patterns() {
		return [ ];
	}
}

new Patterns();
