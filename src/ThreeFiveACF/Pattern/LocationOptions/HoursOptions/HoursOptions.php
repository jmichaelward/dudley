<?php

namespace ThreeFiveACF\Pattern\LocationOptions\HoursOptions;

use ThreeFiveACF\Abstracts\AbstractPatternWithRepeater;

/**
 * Class HoursOptions
 * @package ThreeFiveACF\Pattern\LocationOptions\HoursOptions
 */
class HoursOptions extends AbstractPatternWithRepeater
{
	/**
	 * HoursOptions constructor.
	 */
	public function __construct() {
		if ( ! get_field( 'info_hours_block', 'options' ) ) {
			return;
		}

		while ( has_sub_field( 'info_hours_block', 'options' ) ) {
			$this->add_item( new HoursOptionsItem() );
		}
	}

	/**
	 * @return array
	 */
	public function requirements() {
		return [
			count( $this->items ),
		];
	}

	/**
	 * Get only the hours that are applicable to a specific tour.
	 *
	 * @return array
	 */
	public function get_for_tour() {
		return array_filter( array_map( function( $hours ) {
			/** @var $hours \ThreeFiveACF\Pattern\LocationOptions\HoursOptions\HoursOptionsItem */
			return $hours->tour_has_these( get_the_ID() ) ? $hours : [];
		}, $this->get_items() ) );
	}
}
