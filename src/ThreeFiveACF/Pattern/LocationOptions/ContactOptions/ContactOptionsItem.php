<?php

namespace ThreeFiveACF\Pattern\LocationOptions\ContactOptions;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class ContactOptionsItem
 * @package ThreeFiveACF\Pattern\LocationOptions\ContactOptions
 */
class ContactOptionsItem extends AbstractPattern
{
	/**
	 * @var
	 */
	private $heading;

	/**
	 * @var
	 */
	private $email;

	/**
	 * @var
	 */
	private $phone;

	/**
	 * ContactOptionsItem constructor.
	 */
	public function __construct() {
		$this->heading = get_sub_field( 'info_contacts_block_heading', 'options' );
		$this->email   = get_sub_field( 'info_contacts_block_email', 'options' );
		$this->phone   = get_sub_field( 'info_contacts_block_phone', 'options' );
	}

	/**
	 * We need at least an e-mail address or a phone number to output this block.
	 *
	 * @return bool
	 */
	private function has_contact_info() {
		return $this->email || $this->phone;
	}

	/**
	 * @return mixed
	 */
	public function requirements() {
		return [
			$this->heading,
			$this->has_contact_info(),
		];
	}

	/**
	 *
	 */
	public function heading() {
		echo esc_html( $this->heading );
	}
	
	/**
	 * @return mixed
	 */
	public function get_email() {
		return $this->email;
	}

	/**
	 * @return mixed
	 */
	public function get_phone() {
		return $this->phone;
	}

	/**
	 *
	 */
	public function email() {
		echo esc_html( $this->email );
	}

	/**
	 *
	 */
	public function phone() {
		echo esc_html( $this->phone );
	}
}
