<?php

namespace ThreeFiveACF\Abstracts;

/**
 * Class AbstractFlexibleContent
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractFlexibleContent extends AbstractPatternWithRepeater
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
	 * @param $layout
	 *
	 * @return mixed
	 */
	abstract protected function include_class( $layout );

	/**
	 * @return array
	 */
	abstract protected function acf_field_assoc();

	/**
	 *
	 */
	protected function init() {
		if ( ! have_rows( $this->field_name ) ) {
			return;
		}

		$this->paths = $this->acf_field_assoc();

		$this->add_layouts( $this->field_name );
	}

	/**
	 * @param $rows_name
	 */
	protected function add_layouts( $rows_name ) {
		while ( have_rows( $rows_name ) ) {
			the_row();

			$layout = get_row_layout();

			if ( ! isset( $this->paths[ $layout ] ) ) {
				continue;
			}

			$this->add_item( $layout );
		}
	}

	/**
	 * @param AbstractPattern $layout
	 */
	protected function add_item( $layout ) {
		$class = $this->include_class( $layout );
		$item  = new $class;

		/** @var $item AbstractPattern */
		if ( ! $item->has_required() ) {
			return;
		}

		// Add module to collection
		$this->items[] = $item;
	}
}