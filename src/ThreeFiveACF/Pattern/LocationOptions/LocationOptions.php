<?php

namespace ThreeFiveACF\Pattern\LocationOptions;

use ThreeFiveACF\Abstracts\AbstractPattern;
use ThreeFiveACF\Pattern\LocationOptions\AddressOptions\AddressOptions;
use ThreeFiveACF\Pattern\LocationOptions\ContactOptions\ContactOptions;
use ThreeFiveACF\Pattern\LocationOptions\HoursOptions\HoursOptions;

/**
 * Class LocationOptions
 * @package ThreeFiveACF\Pattern\LocationOptions
 */
class LocationOptions extends AbstractPattern
{
	/**
	 * @var
	 */
	public $address;

	/**
	 * @var
	 */
	public $contact;

	/**
	 * @var
	 */
	public $description;

	/**
	 * @var
	 */
	public $hours;

	/**
	 * LocationOptions constructor.
	 */
	public function __construct() {
		$this->address     = new AddressOptions();
		$this->contact     = new ContactOptions();
		$this->hours       = new HoursOptions();
	}

	/**
	 * Only display the module if we have any one of its sub-sections.
	 *
	 * @return mixed
	 */
	public function requirements() {
		return [
			$this->address || $this->hours || $this->contact,
		];
	}

	/**
	 * @return AddressOptions
	 */
	public function get_address() {
		return $this->address;
	}

	/**
	 * @return ContactOptions
	 */
	public function get_contact() {
		return $this->contact;
	}

	/**
	 * @return HoursOptions
	 */
	public function get_hours() {
		return $this->hours;
	}

	/**
	 * @return bool
	 */
	public function has_address_and_hours() {
		return $this->address || $this->hours;
	}
}