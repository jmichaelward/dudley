<?php

namespace Tfive\ACF\Abstracts;

use Tfive\ACF\Template\Template;
use Tfive\ACF\Traits\ActionTrait;

/**
 * Class AbstractFlexibleContent
 * @package Tfive\ACF\Abstracts
 */
abstract class AbstractFlexibleContent extends AbstractRepeater
{
	/**
	 * @var
	 */
	protected $field_name;

	/**
	 * @var
	 */
	protected $paths;

	/**
	 * @var
	 */
	protected $namespace;

	/**
	 * @var array
	 */
	protected $layouts = [];

	/**
	 * @return array
	 */
	abstract protected function acf_field_assoc();

	/**
	 * Convert ACF field names for layouts into class objects.
	 */
	protected function init() {
		if ( ! have_rows( $this->field_name ) ) {
			return;
		}

		// Set the namespace for this module so its associated layouts can be included.
		$class           = new \ReflectionClass( get_called_class() );
		$this->namespace = $class->getNamespaceName();
		$this->paths     = $this->acf_field_assoc();

		$this->add_layouts( $this->field_name );
	}

	/**
	 * Add layout modules to this flexible content module.
	 *
	 * @param $rows_name
	 */
	private function add_layouts( $rows_name ) {
		while ( have_rows( $rows_name ) ) {
			the_row();

			$layout = get_row_layout();

			if ( ! isset( $this->paths[ $layout ] ) ) {
				continue;
			}

			$this->add_layout( $layout );
		}
	}

	/**
	 * @param AbstractPattern $layout
	 */
	private function add_layout( $layout ) {
		$class = $this->include_class( $layout );
		$item  = new $class;

		/**	@var $item AbstractPattern */
		if ( $item->has_required() ) {
			$this->layouts[] = $item;
		}
	}

	/**
	 * Namespace for the layout class being loaded.
	 *
	 * @param $layout
	 *
	 * @return mixed
	 */
	private function include_class( $layout ) {
		return $this->namespace . '\\' . $this->paths[ $layout ] . '\\' . $this->paths[ $layout ];
	}

	/**
	 * Get the array of layouts.
	 *
	 * @return array
	 */
	public function get_layouts() {
		return $this->layouts;
	}

	/**
	 * Do the action for each flexible content module.
	 *
	 * @param $layouts array
	 */
	public function do_layouts( $layouts ) {
		foreach ( $layouts as $layout ) {
			$template = new Template( $layout );
			$template->render();
		}
	}

	/**
	 *
	 */
	public static function get_template() {
		$class = get_called_class();
		$module = new $class;

		/** @var $module AbstractFlexibleContent */
		if ( $module->has_required() ) {
			$module->do_layouts( $module->layouts );
		}
	}
}
