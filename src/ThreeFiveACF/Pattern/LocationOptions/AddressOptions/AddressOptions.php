<?php

namespace ThreeFiveACF\Pattern\LocationOptions\AddressOptions;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class AddressOptions
 * @package ThreeFiveACF\Pattern\LocationOptions\AddressOptions
 */
class AddressOptions extends AbstractPattern
{
	/**
	 * @var
	 */
	private $street;

	/**
	 * @var
	 */
	private $city;

	/**
	 * @var
	 */
	private $state;

	/**
	 * @var
	 */
	private $zip;

	/**
	 * AddressOptions constructor.
	 */
	public function __construct() {
		$this->street = get_field( 'info_street_address', 'options' );
		$this->city   = get_field( 'info_city', 'options' );
		$this->state  = get_field( 'info_state', 'options' );
		$this->zip    = get_field( 'info_zip_code', 'options' );
	}

	/**
	 * @return mixed
	 */
	public function requirements() {
		return [
			$this->street,
			$this->city,
			$this->state,
			$this->zip,
		];
	}

	/**
	 * @return mixed
	 */
	public function get_address_line_1() {
		return $this->street;
	}

	/**
	 * @return string
	 */
	public function get_address_line_2() {
		return $this->city . ', ' . $this->state . ' ' . $this->zip;
	}

	/**
	 *
	 */
	public function address() {
		echo esc_html( $this->get_address_line_1() . ' ' . $this->get_address_line_2() );
	}

	/**
	 *
	 */
	public function address_line_1() {
		echo esc_html( $this->get_address_line_1() );
	}

	/**
	 *
	 */
	public function address_line_2() {
		echo esc_html( $this->get_address_line_2() );
	}
}
