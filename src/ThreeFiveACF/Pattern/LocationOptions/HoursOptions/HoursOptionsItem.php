<?php

namespace ThreeFiveACF\Pattern\LocationOptions\HoursOptions;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class HoursOptionsItem
 * @package ThreeFiveACF\Pattern\LocationOptions\HoursOptions
 */
class HoursOptionsItem extends AbstractPattern
{
	/**
	 * @var
	 */
	private $heading;

	/**
	 * @var
	 */
	private $description;

	/**
	 * @var
	 */
	private $details;

	/**
	 * @var
	 */
	private $icon;

	/**
	 * @var
	 */
	private $tours = [ ];

	/**
	 * HoursOptionsItem constructor.
	 */
	public function __construct() {
		$this->heading     = get_sub_field( 'info_hours_block_heading', 'options' );
		$this->description = get_sub_field( 'info_hours_block_description', 'options' );
		$this->details     = get_sub_field( 'info_hours_block_details', 'options' );
		$this->icon        = get_sub_field( 'info_hours_block_icon', 'options' );

		if ( $tours = get_sub_field( 'info_hours_tours_with_these', 'options' ) ) {
			array_map( function( $tour ) {
				setup_postdata( $tour );
				array_push( $this->tours, $tour->ID );
			}, $tours );

		}
	}

	/**
	 * @return mixed
	 */
	public function requirements() {
		return [
			$this->heading,
			$this->description,
			$this->icon,
		];
	}

	/**
	 *
	 */
	public function heading() {
		echo esc_html( $this->heading );
	}

	/**
	 *
	 */
	public function description() {
		echo esc_html( $this->description );
	}

	/**
	 *
	 */
	public function details() {
		echo esc_html( $this->details );
	}

	/**
	 *
	 */
	public function icon() {
		echo esc_attr( $this->icon );
	}

	/**
	 * @param $id
	 *
	 * @return mixed
	 */
	public function tour_has_these( $id ) {
		return in_array( $id, $this->tours );
	}
}