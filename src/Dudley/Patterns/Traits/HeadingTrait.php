<?php

namespace Dudley\Patterns\Traits;

/**
 * Class HeadingTrait
 *
 * @package Dudley\Patterns\Traits
 */
trait HeadingTrait {
	/**
	 * The heading.
	 *
	 * @var string $heading
	 */
	protected $heading;

	/**
	 * Getter method for the heading.
	 *
	 * @return string
	 */
	public function get_heading() {
		return $this->heading;
	}

	/**
	 * Print the heading to the template.
	 */
	public function heading() {
		$meta_type   = self::$meta_type;
		$action_name = self::$action_name;

		/**
		 * Dynamic filter for the output of a module's heading.
		 *
		 * Filter uses the same setup as a module's action registration.
		 *
		 * Example: dudley_acf_banner_heading.
		 */
		echo esc_html( apply_filters( "dudley_{$meta_type}_{$action_name}_heading", $this->heading ) );
	}
}
