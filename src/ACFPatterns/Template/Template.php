<?php

namespace Tfive\ACF\Template;

use Tfive\ACF\Abstracts\AbstractPattern;

/**
 * Class Template
 * @package Tfive\ACF\Template
 */
class Template {
	/**
	 * @var AbstractPattern
	 */
	private $pattern;

	/**
	 * @var string
	 */
	private $filename;

	/**
	 * Template constructor.
	 *
	 * @param AbstractPattern $pattern Pattern to render
	 */
	public function __construct( AbstractPattern $pattern ) {
		$this->pattern  = $pattern;
		$this->filename = str_replace( '_', '-', $pattern::$action_name );
	}

	/**
	 * Get the file include path of the pattern's view (filterable for override by theme).
	 */
	private function file_include() {
		// Standard variable made available to and used by the view file to render output.
		$module = $this->pattern;

		include apply_filters( 'tf_acf_template_path', $this->filename );
	}

	/**
	 * Render a module if it has its requirements.
	 */
	public function render() {
		if ( $this->pattern->has_required() ) {
			$this->file_include();
		}
	}
}
