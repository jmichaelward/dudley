<?php
namespace Dudley\MetaType;

use Dudley\Dudley;
use function Dudley\plugin_root;

/**
 * Class ACF
 *
 * @package Dudley\Patterns\ACF
 */
class ACF {
	/**
	 * Patterns that can generate module output for use by a theme. Populate to configure.
	 *
	 * @var Dudley $plugin The main patterns plugin class.
	 */
	private $plugin;

	/**
	 * Path to the acf-json directory.
	 *
	 * @var string
	 */
	private $json_path;

	/**
	 * ACF constructor.
	 *
	 * @param Dudley $plugin The main patterns plugin class.
	 */
	public function __construct( Dudley $plugin ) {
		$this->plugin    = $plugin;
		$this->json_path = plugin_root() . 'acf-json';

		$this->add_options_page();
	}

	/**
	 * Set up plugin hooks
	 */
	public function hooks() {
		// Set ACF save and load paths.
		add_filter( 'acf/settings/save_json', array( $this, 'save_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'load_path' ) );
	}

	/**
	 * Set the ACF save path for new custom fields.
	 *
	 * @return string
	 */
	public function save_path() {
		return $this->json_path;
	}

	/**
	 * Set the load path for ACF.
	 *
	 * @param array $paths
	 *
	 * @return array
	 */
	public function load_path( $paths ) {
		unset( $paths[0] );

		$paths[] = $this->json_path;

		return $paths;
	}

	/**
	 * Create an ACF options page.
	 */
	private function add_options_page() {
		if ( function_exists( 'acf_add_options_page' ) && $this->has_options() ) {
			acf_add_options_page();
		}
	}

	/**
	 * Check to see if any patterns require us to register an options page with ACF.
	 *
	 * @return bool
	 */
	private function has_options() {
		return array_filter( $this->plugin->patterns, function ( $pattern ) {
			return property_exists( $pattern, 'has_global_options' )
			       && $pattern::$has_global_options
			       && property_exists( $pattern, 'meta_type' )
			       && 'acf' === $pattern::$meta_type;
		} );
	}

	/**
	 * Scan the vendor/dudley/ directory for ACF field group JSON files and copy them into ./acf-json/ if
	 * they don't already exist (we don't want to overwrite anything that's already there).
	 *
	 * TODO: Create a user interaction that allows devs to do this from the Dashboard. Currently runs on activation only.
	 */
	public static function copy_acf_field_groups() {
		if ( ! file_exists( plugin_root() . 'vendor/dudley' ) ) {
			return;
		}

		// This section derived from http://php.net/manual/en/class.recursivedirectoryiterator.php#114504
		$directory = new \RecursiveDirectoryIterator(
			plugin_root() . 'vendor/dudley/',
			\FilesystemIterator::FOLLOW_SYMLINKS
		);

		$filter = new \RecursiveCallbackFilterIterator( $directory, function( \SplFileInfo $current ) {
			// Skip hidden files and directories.
			if ( $current->getFilename()[0] === '.' ) {
				return false;
			}

			if ( $current->isDir() ) {
				// Only recurse into intended subdirectories.
				return $current->getFilename() !== 'dudley';
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

			if ( ! file_exists( plugin_root() . 'acf-json/' . $filename ) ) {
				copy( $file, plugin_root() . 'acf-json/' . $filename );
			}
		}
	}
}
