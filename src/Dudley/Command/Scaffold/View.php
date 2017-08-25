<?php

namespace Dudley\Command\Scaffold;

/**
 * Class View
 *
 * @package Dudley\Scaffold
 */
class View extends AbstractViewScaffold {
	/**
	 * The name of the concrete View class to generate.
	 *
	 * @var string $name
	 */
	protected $name;

	/**
	 * The $action_Name for the generated concrete View class.
	 *
	 * @var string $action
	 */
	protected $action;

	/**
	 * The $meta_type for the generated concrete View class.
	 *
	 * @var string $meta
	 */
	protected $meta;

	/**
	 * View constructor.
	 *
	 * @param array $args Command arguments.
	 */
	public function __construct( $args ) {
		$this->name   = ucwords( isset( $args['name'] ) ? $args['name'] : $args['action'] );
		$this->action = sanitize_title_with_dashes( strtolower( isset( $args['action'] ) ? $args['action'] : $args['name'] ) );
		$this->meta   = isset( $args['meta'] ) ? $args['meta'] : 'acf';
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

use Dudley\Patterns\Abstracts\AbstractPattern;

/**
 * {$this->name} class.
 *
 * @package Dudley\Patterns\Pattern\\$this->name
 */
class {$this->name} extends AbstractPattern {
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
		// @TODO: Set data values within the constructor.
	}

	/**
	 * Requirements to render this item.
	 * TODO: Add requirements to the array in this method.
	 *
	 * @return array
	 */
	public function requirements() {
		return [];
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
 */
?>
<h1>Temporary view for the {$this->name} class pattern.</h1>

TEMPLATE;
	}
}
