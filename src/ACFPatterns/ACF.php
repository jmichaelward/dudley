<?php

namespace Tfive\ACF;

use Tfive\ACF\Traits\ActionTrait;

/**
 * Class ACF
 * @package ThreeFiveACF
 */
class ACF
{
	/**
	 * @var array $std_patterns Patterns that can generate module output for use by a theme. Populate to configure.
	 */
	private $std_patterns = [
		// Populate with Pattern class names, e.g., Banner, FeaturedLinks
	];

	/**
	 * ACF constructor.
	 */
	public function __construct()
	{
		spl_autoload_register( [ $this, 'autoload' ] );

		add_filter( 'acf/settings/save_json', array( $this, 'acf_save_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'acf_load_path' ) );

		$this->add_options_page();
		$this->add_actions();

		add_filter( 'tf_acf_template_path', [ $this, 'acf_template_filter' ], 10, 1 );
	}

	/**
	 * @return string Set the ACF save path for new custom fields.
	 */
	public function acf_save_path()
	{
		return ACFP_ROOT . '/acf-json';
	}

	/**
	 * @param $paths
	 *
	 * @return array
	 */
	public function acf_load_path( $paths )
	{
		unset( $paths[0] );

		$paths[] = ACFP_ROOT . '/acf-json';

		return $paths;
	}

	/**
	 * Create an ACF options page
	 */
	private function add_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page();
		}
	}

	/**
	 * Autoload all of our needed pattern classes
	 */
	private function autoload( $class ) {
		$class = str_replace( '\\', '/', $class );
		$class = str_replace( 'Tfive/ACF', 'ACFPatterns', $class );

		if ( 0 !== strpos( $class, 'ACFPatterns' ) ) {
			return;
		}

		$parts = explode( '/', $class );

		if ( in_array( 'Pattern', $parts ) ) {
			$path = ACFP_SRC_ROOT . '/' . $class . '.php';
		} else {
			$file = array_pop( $parts );

			$path = ACFP_SRC_ROOT . '/' . implode( '/', $parts ) . '/' . $file . '.php';
		}

		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}

	/**
	 * Bootstrap 3five ACF modules and assign an action to each component for use in templates.
	 */
	private function add_actions() {
		// Map standard content pattern classes to namespace
		$classes = array_map(
			function ( $class_name ) {
				return 'Tfive\\ACF\\Pattern\\' . $class_name . '\\' . $class_name;
			},
			$this->std_patterns
		);

		// Add actions for each class
		foreach ( $classes as $class_name ) {
			/** @var $class_name ActionTrait */
			add_action( 'tf_acf_' . $class_name::get_action_name(), [ $class_name, 'get_template' ] );
		}
	}

	/**
	 * Allow themes to override the markup in this plugin by creating an acf-modules/ directory.
	 *
	 * @param $file
	 *
	 * @return string
	 */
	public function acf_template_filter( $file ) {
		if ( file_exists( get_template_directory() . '/acf-modules/' . $file . '.php' ) ) {
			return get_template_directory() . '/acf-modules/' . $file . '.php';
		}

		if ( file_exists( ACFP_ROOT . 'acf-modules/' . $file . '.php' ) ) {
			return ACFP_ROOT . 'acf-modules/' . $file . '.php';
		}

		return '';
	}
}
