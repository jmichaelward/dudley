<?php
namespace Dudley\Patterns\Abstracts;

use Dudley\Patterns\Template\Template;

/**
 * Class AbstractFlexibleContent
 *
 * @package Dudley\Patterns\Abstracts
 */
abstract class AbstractFlexibleContent extends AbstractPattern {
	/**
	 * Name of the ACF Flexible Content meta field key.
	 *
	 * @var string $field_name
	 */
	protected $field_name;

	/**
	 * Key/value array of ACF Flexible Content layout meta keys and associated class names.
	 * e.g., [ 'example_acf_flex_layout' => 'ExampleAcfFlexLayout' ]
	 *
	 * @var array $paths
	 */
	protected $paths;

	/**
	 * Namespace of the concrete flexible content instance.
	 *
	 * @var string $namespace
	 */
	protected $namespace;

	/**
	 * Array of individual layout objects.
	 *
	 * @var array $layouts
	 */
	protected $layouts = [];

	/**
	 * Convert ACF field names for layouts into class objects.
	 */
	public function __construct() {
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
	 * Basic flex content modules need only their set of layouts. This can be overridden if it's a more complex
	 * module with extra fields in addition to the individual layouts.
	 *
	 * @return array
	 */
	public function requirements() {
		return [
			$this->layouts,
		];
	}

	/**
	 * Concrete classes should declare a key/value array of ACF Flexible Content layout meta keys and
	 * associated class names. This will be assigned to $this->paths on instantiation.
	 *
	 * Example: [ 'example_acf_flex_layout' => 'ExampleAcfFlexLayout' ].
	 *
	 * @return array
	 */
	abstract protected function acf_field_assoc();

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
	 * Instantiate an instance of a layout and add it to this module's collection if it has all requirements.
	 *
	 * @param AbstractPattern $layout
	 */
	private function add_layout( $layout ) {
		$class = $this->include_class( $layout );
		$item  = new $class;

		/** @var $item AbstractPattern */
		if ( $item->has_required() ) {
			$this->layouts[] = $item;
		}
	}

	/**
	 * Compare layout name with registered class associations. If they exist, create a new instance of the layout class.
	 *
	 * @param $rows_name
	 *
	 * @return AbstractFlexibleContent For method chaining.
	 */
	private function add_layouts( $rows_name ) {
		while ( have_rows( $rows_name ) ) {
			the_row();

			$layout = get_row_layout();

			if ( array_key_exists( $layout, $this->paths ) ) {
				$this->add_layout( $layout );
			}
		}

		return $this;
	}

	/**
	 * Do the action for each flexible content module.
	 *
	 * @param $layouts array
	 */
	private function do_layouts( $layouts ) {
		foreach ( $layouts as $layout ) {
			$template = new Template( $layout );
			$template->render();
		}
	}

	/**
	 * Initialize instance of this class when do_action is called in a theme.
	 * Overrides the AbstractPattern's method.
	 */
	public static function render_view( $args = null ) {
		$class  = get_called_class();
		$module = new $class;

		/** @var $module AbstractFlexibleContent */
		if ( $module->has_required() ) {
			$module->do_layouts( $module->layouts );
		}
	}
}
