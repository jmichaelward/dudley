<?php
/**
 * Plugin Name: 3five Patterns
 * Description: Store and render reusable content modules.
 * Plugin URI: http://3five.com
 * Author: Jeremy Ward, 3five
 * Author URI: http://3five.com
 */
namespace Tfive\Patterns;

/**
 * Class Patterns
 * @package Tfive\Patterns
 */
final class Patterns {
	/**
	 * Advanced Custom Fields patterns class.
	 *
	 * @var $acf ACF
	 */
	public $acf;

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

		// Load dependencies.
		require_once $autoload_file;
		require_once 'functions.php';
	}

	/**
	 * Register plugin hooks.
	 */
	public function hooks() {
		// Setup the plugin.
		add_action( 'plugins_loaded', [ $this, 'run' ] );

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
	}

	/**
	 * Setup ACF patterns and register hooks.
	 */
	public function run() {
		include_once plugin_dir_path( __FILE__ ) . 'src/AdminNotifier.php';
		$this->notifier = new AdminNotifier();

		if ( ! class_exists( 'acf' ) ) {
			$this->notifier->missing_acf_requirement();
		}

		$this->load_acf_patterns();

		if ( $this->acf ) {
			$this->acf->hooks();
		}
	}

	/**
	 * Process the array in the Composer autoload classmap in order to automatically include our selected patterns.
	 *
	 * @return bool
	 */
	public function load_acf_patterns() {
		$pattern_classes = ( include plugin_dir_path( __FILE__ ) . 'vendor/composer/autoload_classmap.php' );

		if ( ! $pattern_classes ) {
			$this->notifier->missing_autoload_classmap();

			return false;
		}

		$this->acf = new ACF( $this->get_patterns( $pattern_classes ) );

		return true;
	}

	/**
	 * Filter through the set of 3five ACF Patterns classes and return those with registered actions.
	 *
	 * @param array $patterns
	 *
	 * @return array
	 */
	private function get_patterns( array $patterns ) {
		return array_filter( array_map( function( $pattern_class ) {
			// Check for patterns that also have a set action name so we can register them.
			if ( ! ( strpos( $pattern_class, '\\Pattern\\' ) && property_exists( $pattern_class, 'action_name' ) ) ) {
				return false;
			}

			// Return the pattern if it has an associated action.
			if ( $pattern_class::$action_name ) {
				return $pattern_class;
			}

			// All patterns must have the $action_name property. Something went wrong.
			throw new \LogicException( 'No action defined for ' . $pattern_class );
		}, array_keys( $patterns ) ) );
	}

	/**
	 * Scan the vendor/acfpatterns/ directory for ACF field group JSON files and copy them into ./acf-json/ if
	 * they don't already exist (we don't want to overwrite anything that's already there).
	 *
	 * TODO: Create a user interaction that allows devs to do this from the Dashboard. Currently runs on activation
	 * only.
	 */
	private function copy_acf_field_groups() {
		if ( ! file_exists( plugin_dir_path( __FILE__ ) . 'vendor/acfpatterns' ) ) {
			return;
		}

		// This section derived from http://php.net/manual/en/class.recursivedirectoryiterator.php#114504
		$directory = new \RecursiveDirectoryIterator(
			plugin_dir_path( __FILE__ ) . 'vendor/acfpatterns/',
			\FilesystemIterator::FOLLOW_SYMLINKS
		);

		$filter = new \RecursiveCallbackFilterIterator( $directory, function( \SplFileInfo $current ) {
			// Skip hidden files and directories.
			if ( $current->getFilename()[0] === '.' ) {
				return false;
			}

			if ( $current->isDir() ) {
				// Only recurse into intended subdirectories.
				return $current->getFilename() !== 'acfpatterns';
			}

			// Get all of the ACF JSON files
			$test = preg_match( '/group_[a-z0-9]*.json/', $current->getFilename() );

			return $test;
		} );

		$iterator = new \RecursiveIteratorIterator( $filter );
		$files    = [];

		foreach ( $iterator as $info ) {
			array_push( $files, $info->getPathname() );
		}

		foreach ( $files as $file ) {
			$filename_parts = explode( '/', $file );
			$filename       = array_pop( $filename_parts );

			if ( ! file_exists( plugin_dir_path( __FILE__ ) . 'acf-json/' . $filename ) ) {
				copy( $file, plugin_dir_path( __FILE__ ) . 'acf-json/' . $filename );
			}
		}
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
	public function deactivate() {
	}
}

$plugin = new Patterns();
$plugin->hooks();
