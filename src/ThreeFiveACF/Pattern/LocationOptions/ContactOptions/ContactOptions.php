<?php

namespace ThreeFiveACF\Pattern\LocationOptions\ContactOptions;

use ThreeFiveACF\Abstracts\AbstractPatternWithRepeater;

/**
 * Class ContactOptions
 * @package ThreeFiveACF\Pattern\LocationOptions\ContactOptions
 */
class ContactOptions extends AbstractPatternWithRepeater
{
	/**
	 * ContactOptions constructor.
	 */
	public function __construct() {
		if ( ! get_field( 'info_contacts_block', 'options' ) ) {
			return;
		}
		
		while ( has_sub_field( 'info_contacts_block', 'options' ) ) {
			$this->add_item( new ContactOptionsItem() );
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
}
