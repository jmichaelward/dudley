<?php

namespace Tfive\ACF\Template;

use Tfive\ACF\Abstracts\AbstractPattern;

/**
 * Class Template
 * @package ThreeFiveACF\Template
 */
class Template
{
	/**
	 * @var string
	 */
	private $path;

	/**
	 * Template constructor.
	 *
	 * @param AbstractPattern $pattern Pattern to render
	 * @param string $path Optional path override
	 */
	public function __construct( $pattern, $path = '' ) {
		$this->path    = str_replace( '_', '-', $pattern::$action_name );
		$this->pattern = $pattern;
	}

	/**
	 * Get the include path of the pattern's view (filterable for override by theme).
	 */
	private function path_include() {
		$module = $this->pattern; // Variable used by the include files.

		include apply_filters( 'tf_acf_template_path', $this->path );
	}

	/**
	 * Render a module if it has its requirements.
	 */
	public function render() {
		if ( $this->pattern->has_required() ) {
			$this->path_include();
		}
	}
}
