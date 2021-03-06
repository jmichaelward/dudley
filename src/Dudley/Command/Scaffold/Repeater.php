<?php
/**
 * This class is responsible for scaffolding a Repeater class and related view file.
 *
 * @package Dudley\Command\Scaffold
 */

namespace Dudley\Command\Scaffold;

/**
 * Class Repeater
 *
 * @package Dudley\Command\Scaffold
 */
class Repeater extends AbstractViewScaffold {
	/**
	 * The name of the concrete Repeater class to generate.
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * The $action_name for the generated concrete Repeater class.
	 *
	 * @var string $action
	 */
	protected $action;

	/**
	 * The $meta_type for the generated concrete Repeater class.
	 *
	 * @var string $meta
	 */
	protected $meta;

	/**
	 * Repeater constructor.
	 *
	 * @param array $args Command arguments.
	 */
	public function __construct( $args ) {
		$this->name   = ucwords( isset( $args['name'] ) ? $args['name'] : $args['action'] );
		$this->action = sanitize_title_with_dashes( strtolower( isset( $args['action'] ) ? $args['action'] : $args['name'] ) );
		$this->meta   = isset( $args['meta'] ) ? $args['meta'] : 'acf';

		// Repeaters contain their own items, so we'll scaffold a class for them, too.
		$item = new Item( $args );
		$item->scaffold();
	}

	/**
	 * Template for scaffolding a concrete View class.
	 *
	 * @return string
	 */
	public function scaffold_class_template() {
		return <<<TEMPLATE
<?php
namespace Dudley\Patterns\Pattern\\$this->name;

use Dudley\Patterns\Abstracts\AbstractRepeater;

/**
 * {$this->name} class.
 *
 * @package Dudley\Patterns\Pattern\\$this->name
 */
class {$this->name} extends AbstractRepeater {
	/**
	 * Action name for this pattern. Corresponds with view file with similar name located in /views.
	 *
	 * @var string \$action_name
	 */
	public static \$action_name = '{$this->action}';

	/**
	 * Meta fields type for this pattern.
	 *
	 * @var string \$meta_type
	 */
	public static \$meta_type = '{$this->meta}';

	/**
	 * {$this->name} constructor.
	 */
	public function __construct() {
		// Bail early if the repeater doesn't have any items.
		/*
		 * ACF example: 
		 * if ( ! get_field( '{$this->action}' ) ) {
		 *      return;
		 * }
		 */
		// @TODO: Set data values within the constructor.
				
	    // Iteratively instantiate {$this->name}Item instances with each item's necessary data.
	    /*
	     * ACF example:
	     * while ( has_sub_field( '{$this->action}' ) { // Whatever the repeater field is named
	     *     \$this->add_item( new {$this->name}Item(
	     *         // Item data requirements
	     *     ) );
	     * }
	     */
	}

	/**
	 * Requirements to render this repeater pattern.
	 * TODO: Add requirements to the array in this method. Repeaters generally require items to render themselves.
	 *
	 * @return array
	 */
	public function requirements() {
		return [
			\$this->items,
		];
	}
}

TEMPLATE;
	}

	/**
	 * Template for scaffolding a View view file.
	 */
	public function scaffold_pattern_view() {
		return <<< TEMPLATE
<?php
/**
 * @var \$module Dudley\Patterns\Pattern\\$this->name\\$this->name
 * @var \$item Dudley\Patterns\Pattern\\$this->name\\{$this->name}Item
 */
?>
<h1>Temporary view for the {$this->name} class pattern.</h1>

TEMPLATE;
	}
}
