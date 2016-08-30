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

		$this->acf_json_path = plugin_dir_path( __FILE__ ) . '/acf-json';

		$this->locate_patterns();

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

		$this->copy_acf_field_groups();
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
	 * Process the array in the Composer autoload classmap in order to automatically include our selected patterns.
	 */
	private function locate_patterns() {
		$patterns = ( include plugin_dir_path( __FILE__ ) . 'vendor/composer/autoload_classmap.php' );

		if ( ! $patterns ) {
			$error = new \WP_Error( 'broke', _e( 'No patterns installed! Did you remember to run "composer install -a"?' ) );
			wp_die( $error->get_error_message() );
			exit;
		}

		$acf_patterns = array_filter( array_map( function( $key ) {
			if ( strpos( $key, '\Pattern\\' ) ) {
				$namespace = explode( '\\', $key );

				/**
				 * Note: "Actual" patterns look like Tfive\ACF\Pattern\[Pattern Name]\[Pattern Name]
				 *
				 * Some patterns have child elements, but they shouldn't get included here because they don't need
				 * to have an associated WordPress action.
				 */
				if ( array_pop( $namespace ) === array_pop( $namespace ) ) {
					return $key;
				}
			}

			return false;
		}, array_keys( $patterns ) ) );

		$this->acf = new ACF( $acf_patterns );
	}

	private function copy_acf_field_groups() {
		// This section derived from http://php.net/manual/en/class.recursivedirectoryiterator.php#114504
		$directory = new \RecursiveDirectoryIterator(
			plugin_dir_path( __FILE__ ) . 'vendor/acfpatterns/',
			\FilesystemIterator::FOLLOW_SYMLINKS
		);
		$filter = new \RecursiveCallbackFilterIterator($directory, function( $current, $key, $iterator ) {
			// Skip hidden files and directories.
			if ($current->getFilename()[0] === '.') {
				return false;
			}

			if ($current->isDir()) {
				// Only recurse into intended subdirectories.
				return $current->getFilename() !== 'acfpatterns';
			}

			// Get all of the ACF JSON files
			$test = preg_match( '/group_[a-z0-9]*.json/', $current->getFilename() );
			return $test;
		} );

		$iterator  = new \RecursiveIteratorIterator( $filter );
		$files = [];

		foreach ( $iterator as $info ) {
			array_push( $files, $info->getPathname());
		}

		foreach ( $files as $file ) {
			$filename_parts = explode( '/', $file );
			$filename = array_pop( $filename_parts );

			if ( ! file_exists( plugin_dir_path( __FILE__ ) . 'acf-json/' . $filename ) ) {
				copy( $file, plugin_dir_path( __FILE__ ) . 'acf-json/' . $filename );
			}
		}
	}
}

$plugin = new Patterns();

register_activation_hook( __FILE__, array( $plugin, 'activate' ) );
register_deactivation_hook( __FILE__, array( $plugin, 'deactivate' ) );
