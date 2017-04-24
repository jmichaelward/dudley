<?php

namespace Dudley\Patterns\Template;

use Dudley\Patterns\Abstracts\AbstractPattern;

/**
 * Class Template
 *
 * @package Dudley\Patterns\Template
 */
class Template {
	/**
	 * Pattern that will be rendered.
	 *
	 * @var AbstractPattern
	 */
	private $pattern;

	/**
	 * Path to the template view.
	 *
	 * @var string
	 */
	private $filepath;

	/**
	 * Template constructor.
	 *
	 * @param string|AbstractPattern $pattern Pattern to render.
	 * @param array                  $args    Optional arguments to pass to patterns that use them.
	 */
	public function __construct( $pattern, $args = null ) {
		$this->pattern  = $this->get_pattern_object( $pattern, $args );
		$this->filepath = $this->get_filepath();
	}

	/**
	 * Check whether the provided pattern class has already been instantiated, or if it needs to be created now.
	 *
	 * @param string|AbstractPattern $pattern The pattern to render.
	 * @param array                  $args    Optional arguments to pass to patterns that use them.
	 *
	 * @return mixed
	 */
	private function get_pattern_object( $pattern, $args = null ) {
		if ( 'string' === gettype( $pattern ) ) {
			return $args ? new $pattern( $args ) : new $pattern;
		}

		return $pattern;
	}

	/**
	 * Render a module if it has its requirements. Declares the $module variable to expose it for use by the template
	 * view.
	 */
	public function render() {
		if ( $this->pattern->has_required() ) {
			$module = $this->pattern;

			include $this->filepath;
		}
	}

	/**
	 * Get the include path for a pattern's view file.
	 *
	 * Includes two filters:
	 * - dudley_patterns_file_paths: Allows developers to customize a path for views they want to be checked before rendering modules.
	 * - dudley_pattern_template_override_dir: Allows developers to simply change the directory name used within the theme.
	 *
	 * TODO: Make this configurable. Currently, views in Composer packages are only found in dudley packages.
	 *
	 * @return string
	 */
	private function get_filepath() {
		$package_name = str_replace( '_', '-', $this->pattern->get_action_name() );
		$filepaths   = apply_filters( 'dudley_patterns_file_paths', [
			'theme'            => trailingslashit( get_stylesheet_directory() )
			                      . trailingslashit( apply_filters( 'dudley_pattern_template_override_dir', 'dudley-modules' ) ),
			'custom_in_plugin' => trailingslashit( \Dudley\Patterns\plugin_root() . 'views' ),
			'composer_package' => trailingslashit( \Dudley\Patterns\plugin_root() . "vendor/dudley/$package_name/views" ),
		] );

		// Return the first available file path from the $file_paths array.
		foreach ( $filepaths as $path ) {
			if ( file_exists( $path . "$package_name.php" ) ) {
				return $path . "$package_name.php";
			}
		}

		// No files exist to include.
		return '';
	}
}
