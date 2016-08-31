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
	 * @var $notifier AdminNotifier
	 */
	private $notifier;

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

		// Set ACF save and load paths
		add_filter( 'acf/settings/save_json', array( $this, 'acf_save_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'acf_load_path' ) );

		add_action( 'init', array( $this, 'check_requirements' ) );
	}

	/**
	 * Run on plugin activation.
	 */
	public function activate() {
		if ( ! class_exists( 'acf' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
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
	public function check_requirements() {
		include_once plugin_dir_path( __FILE__ ) . '/src/AdminNotifier.php';
		$this->notifier = new AdminNotifier();

		if ( ! class_exists( 'acf' ) ) {
			$this->notifier->missing_acf_requirement();
		}

		$this->locate_patterns();
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
	 *
	 * @return bool
	 */
	public function locate_patterns() {
		$patterns = ( include plugin_dir_path( __FILE__ ) . 'vendor/composer/autoload_classmap.php' );

		if ( ! $patterns ) {
			$this->notifier->missing_autoload_classmap();
			return false;
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

		return true;
	}

	/**
	 * Scan the vendor/acfpatterns/ directory for ACF field group JSON files and copy them into ./acf-json/ if
	 * they don't already exist (we don't want to overwrite anything that's already there).
	 *
	 * TODO: Create a user interaction that allows devs to do this from the Dashboard. Currently runs on activation only.
	 */
	private function copy_acf_field_groups() {
		// This section derived from http://php.net/manual/en/class.recursivedirectoryiterator.php#114504
		$directory = new \RecursiveDirectoryIterator(
			plugin_dir_path( __FILE__ ) . 'vendor/acfpatterns/',
			\FilesystemIterator::FOLLOW_SYMLINKS
		);
		$filter = new \RecursiveCallbackFilterIterator($directory, function( $current ) {
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
