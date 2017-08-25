<?php
/**
 * This class is responsible for scaffolding an Item class that can be called and used by a View or Repeater.
 *
 * Items do not have their own view files.
 *
 * @package Dudley\Command\Scaffold
 */

namespace Dudley\Command\Scaffold;

/**
 * Class Item
 *
 * @package Dudley\Scaffold
 */
class Item extends AbstractItemScaffold {
	/**
	 * Name of the item to scaffold.
	 *
	 * @var string
	 */
	public $name;

	/**
	 * Item constructor.
	 *
	 * @param array $args Arguments to scaffold this file.
	 */
	public function __construct( $args ) {
		$this->name = ucwords( isset( $args['name'] ) ? $args['name'] : $args['action'] );
	}

	/**
	 * Template for the Item scaffold.
	 *
	 * @return string
	 */
	public function scaffold_class_template() {
		return <<<TEMPLATE
<?php
namespace Dudley\Patterns\Pattern\\$this->name;

use Dudley\Patterns\Abstracts\AbstractItem;

/**
 * {$this->name} class.
 *
 * @package Dudley\Patterns\Pattern\\$this->name
 */
class {$this->name}Item extends AbstractItem {
	/**
	 * Requirements to render this item.
	 */
	public function requirements() {
		return [];
	}
}

TEMPLATE;
	}
}
