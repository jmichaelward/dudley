<?php

namespace ThreeFiveACF\Pattern\SocialMediaAccounts;

use ThreeFiveACF\Abstracts\AbstractPatternWithRepeater;

/**
 * Class SocialMediaAccounts
 * @package ThreeFiveACF\Pattern\SocialMediaAccounts
 */
class SocialMediaAccounts extends AbstractPatternWithRepeater
{
	/**
	 * SocialMediaAccounts constructor.
	 */
	public function __construct() {
		if ( ! get_field( 'social_media_accounts', 'options' ) ) {
			return;
		}
		
		while ( has_sub_field( 'social_media_accounts', 'options' ) ) {
			$this->add_item( new SocialMediaAccountsItem() );
		}
	}

	/**
	 * @return int
	 */
	public function requirements() {
		return [
			count( $this->items ),
		];
	}

	/**
	 * @param $account
	 *
	 * @return bool|SocialMediaAccountsItem
	 */
	public function get_account( $account ) {
		/** @var $item SocialMediaAccountsItem */
		foreach ( $this->items as $item ) {
			if ( strtolower( $account ) === $item->service ) {
				return $item;
			}
		}

		return false;
	}
}
