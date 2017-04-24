<?php
namespace Dudley\MetaType;

use function Dudley\plugin_dirname;
use function Dudley\plugin_root;

/**
 * This class is responsible for making a connection with Advanced Custom Fields. It copies ACF Field Group definitions
 * in JSON format to the user's defined acf-json directory, and establishes an options page should any packages indicate
 * that they are available via global options.
 *
 * Class ACF
 *
 * @package Dudley\Patterns\ACF
 */
class ACF extends AbstractMetaType {
	/**
	 * Set up this meta type.
	 */
	public function setup() {
		if ( ! class_exists( 'acf' ) ) {
			deactivate_plugins( plugin_dirname() );

			return;
		}

		$json_path = array_pop( acf_get_setting( 'load_json' ) );
		$this->maybe_create_directory( $json_path );
		$this->copy_acf_field_groups( $json_path );
	}

	/**
	 * Create a JSON directory to store field groups if one does not already exist.
	 *
	 * @param string $path Path to the acf-json directory.
	 */
	public function maybe_create_directory( $path ) {
		if ( ! file_exists( $path ) ) {
			mkdir( $path, 0755 ); // @codingStandardsIgnoreLine
		}
	}

	/**
	 * Set up plugin hooks
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'add_options_page' ] );

		if ( ! class_exists( 'acf' ) ) {
			add_action( 'admin_notices', function() {
				printf(
					'<div class="notice notice-error"><p>%1$s</p></div>',
					esc_html__(
						'Dudley is configured to use Advanced Custom Fields, but it is not installed. Please activate ACF or configure Dudley to use another meta type to dismiss this message.',
						'dudley'
					)
				);
			} );
		}
	}

	/**
	 * Create an ACF options page.
	 */
	public function add_options_page() {
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
	 * @param string $json_path Path to the ACF JSON directory.
	 *
	 * TODO: Create a user interaction that allows devs to do this from the Dashboard. Currently runs on activation only.
	 */
	public function copy_acf_field_groups( $json_path ) {
		if ( ! file_exists( plugin_root() . 'vendor/dudley' ) ) {
			return;
		}

		// This section derived from http://php.net/manual/en/class.recursivedirectoryiterator.php#114504.
		$directory = new \RecursiveDirectoryIterator(
			plugin_root() . 'vendor/dudley/',
			\FilesystemIterator::FOLLOW_SYMLINKS
		);

		$filter = new \RecursiveCallbackFilterIterator( $directory, function( \SplFileInfo $current ) {
			// Skip hidden files and directories.
			if ( '.' === $current->getFilename()[0] ) {
				return false;
			}

			if ( $current->isDir() ) {
				// Only recurse into intended subdirectories.
				return $current->getFilename() !== 'dudley';
			}

			// Get all of the ACF JSON files.
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

			if ( ! file_exists( $json_path . $filename ) ) {
				copy( $file, $json_path . $filename );
			}
		}
	}
}
