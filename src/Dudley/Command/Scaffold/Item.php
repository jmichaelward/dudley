<?php

namespace Dudley\Command\Scaffold;

use function Dudley\plugin_root;

/**
 * Class Item
 *
 * @package Dudley\Scaffold
 */
class Item {
	/**
	 * Name of the item to scaffold.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Item constructor.
	 *
	 * @param array $args Arguments to scaffold this file.
	 */
	public function __construct( $args ) {
		$this->name = ucwords( isset( $args['name'] ) ? $args['name'] : $args['action'] );
	}

	/**
	 * Scaffold an concrete implementation of AbstractItem.
	 *
	 * @return bool
	 */
	public function scaffold() {
		$dir  = trailingslashit( plugin_root() . "patterns/{$this->name}" );
		$file = $dir . "{$this->name}Item.php";

		if ( file_exists( $dir ) && file_exists( $file ) ) {
			\WP_CLI::error( "File {$this->name}Item.php already exists at {$dir}" );
			return false;
		}

		if ( ! file_exists( plugin_root() . '/patterns/' . $this->name ) ) {
			mkdir( plugin_root() . '/patterns/' . $this->name ); // @codingStandardsIgnoreLine
		}

		// @TODO Use the Filesystem API instead.
		if ( ! file_exists( plugin_root() . "/patterns/{$this->name}/{$this->name}Item.php" ) ) {
			// @codingStandardsIgnoreStart
			$template = fopen( plugin_root() . "/patterns/{$this->name}/{$this->name}Item.php", 'w' );

			fwrite( $template, $this->template() );
			fclose( $template );
			// @codingStandardsIgnoreEnd
		}

		\WP_CLI::success( "File {$this->name}Item.php scaffolded at {$dir}" );

		return true;
	}

	/**
	 * Template for the Item scaffold.
	 *
	 * @return string
	 */
	private function template() {
		return <<<TEMPLATE
<?php
namespace Dudley\Patterns\Pattern\\$this->name;

use Dudley\Patterns\Abstracts\AbstractItem;

/**
 * {$this->name} class.
 *
 * @package Dudley\Patterns\Pattern\\$this->name
 */
class {$this->name} extends AbstractItem {
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
