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
		$class = __CLASS__;

		// Allow filtering of heading at the module level.
		if ( property_exists( $class, 'meta_type' ) && property_exists( $class, 'action_name' ) ) {
			$meta_type   = $class::$meta_type;
			$action_name = $class::$action_name;

			/**
			 * Dynamic filter for the output of a module's heading.
			 *
			 * Filter uses the same setup as a module's action registration.
			 *
			 * Example: dudley_acf_banner_heading.
			 */
			echo esc_html( apply_filters( "dudley_{$meta_type}_{$action_name}_heading", $this->heading ) );
			return;
		}

		echo esc_html( $this->heading );
	}
}
