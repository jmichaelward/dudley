<?php

namespace ThreeFiveACF\Pattern\SocialMediaAccounts;

use ThreeFiveACF\Abstracts\AbstractPattern;

/**
 * Class SocialMediaAccountsItem
 * @package ThreeFiveACF\Pattern\SocialMediaAccounts
 */
class SocialMediaAccountsItem extends AbstractPattern
{
	/**
	 * Slug for the service.
	 *
	 * @var string $service
	 */
	public $service;

	/**
	 * Name of the service.
	 *
	 * @var string $serviceName
	 */
	public $serviceName;

	/**
	 * URL for this social media account.
	 *
	 * @var string $url
	 */
	public $url;

	/**
	 * SocialMediaAccountsItem constructor.
	 */
	public function __construct() {
		$options       = get_sub_field_object( 'social_media_account', 'options' );
		$this->service = get_sub_field( 'social_media_account', 'options' );
		$this->url     = get_sub_field( 'social_media_account_url', 'options' );

		if ( isset( $options['choices'][ $this->service ] ) ) {
			$this->serviceName = $options['choices'][ $this->service ];
		}
	}

	/**
	 * @return array
	 */
	public function requirements() {
		return [
			'service'     => $this->service,
			'serviceName' => $this->serviceName,
			'url'         => $this->url,
		];
	}

	/**
	 * @return string
	 */
	public function url() {
		echo esc_url( $this->url );
	}

	/**
	 * @return string
	 */
	public function slug() {
		echo esc_attr( $this->service );
	}

	/**
	 * @return string
	 */
	public function get_name() {
		return $this->serviceName;
	}

	/**
	 */
	public function name() {
		echo esc_html( $this->serviceName );
	}
}
