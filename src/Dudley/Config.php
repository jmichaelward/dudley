<?php
namespace Dudley;

use Dudley\MetaType\AbstractMetaType;
use Dudley\MetaType\ACF;
use Dudley\MetaType\MetaTypeInterface;

/**
 * Class Config
 *
 * @package Dudley
 */
class Config {
	/**
	 * The main plugin class.
	 *
	 * @var Dudley $plugin
	 */
	public $plugin;

	/**
	 * The primary user interface for generating meta fields in WordPress. Defaults to acf for Advanced Custom Fields.
	 *
	 * @var $meta_type AbstractMetaType|bool
	 */
	public $meta_type;

	/**
	 * Config constructor.
	 *
	 * @param Dudley $plugin The main plugin object.
	 */
	public function __construct( Dudley $plugin ) {
		$this->plugin    = $plugin;
		$this->meta_type = $this->get_meta_type();
	}

	/**
	 * Method for setting the meta_type property in the Config class.
	 *
	 * @return AbstractMetaType|bool
	 */
	private function get_meta_type() {
		if ( ! defined( 'DUDLEY_META_TYPE' ) || 'acf' === DUDLEY_META_TYPE ) {
			return new ACF( $this->plugin );
		}

		return false;
	}
}
