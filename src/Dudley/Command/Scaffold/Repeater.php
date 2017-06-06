<?php

namespace Dudley\Command\Scaffold;

use function Dudley\plugin_root;

/**
 * Class Repeater
 *
 * @package Dudley\Command\Scaffold
 */
class Repeater {
	/**
	 * The name of the concrete Repeater class to generate.
	 *
	 * @var string $name
	 */
	private $name;

	/**
	 * The $action_name for the generated concrete Repeater class.
	 *
	 * @var string $action
	 */
	private $action;

	/**
	 * The $meta_type for the generated concrete Repeater class.
	 *
	 * @var string $meta
	 */
	private $meta;

	/**
	 * Repeater constructor.
	 *
	 * @param array $args Command arguments.
	 */
	public function __construct( $args ) {
		$this->name   = ucwords( isset( $args['name'] ) ? $args['name'] : $args['action'] );
		$this->action = sanitize_title_with_dashes( strtolower( isset( $args['action'] ) ? $args['action'] : $args['name'] ) );
		$this->meta   = isset( $args['meta'] ) ? $args['meta'] : 'acf';

		// Repeaters contain items, and we'll want to scaffold that, too.
		$item = new Item( $args );
		$item->scaffold();
	}

	/**
	 * Attempt to scaffold the necessary files for the pattern, if not already present.
	 *
	 * @return bool
	 */
	public function scaffold() {
		/*
		 * First, generate the Pattern directory and class file.
		 */
		$dir  = trailingslashit( plugin_root() . "patterns/{$this->name}" );
		$file = $dir . "{$this->name}.php";

		if ( file_exists( $dir ) && file_exists( $file ) ) {
			\WP_CLI::error( "File {$this->name}.php already exists at {$dir}" );

			return false;
		}

		if ( ! file_exists( plugin_root() . '/patterns/' . $this->name ) ) {
			mkdir( plugin_root() . '/patterns/' . $this->name ); // @codingStandardsIgnoreLine
		}

		// @TODO Use the Filesystem API instead.
		if ( ! file_exists( plugin_root() . "/patterns/{$this->name}/{$this->name}.php" ) ) {
			// @codingStandardsIgnoreStart
			$template = fopen( plugin_root() . "/patterns/{$this->name}/{$this->name}.php", 'w' );

			fwrite( $template, $this->template() );
			fclose( $template );
			// @codingStandardsIgnoreEnd
		}

		\WP_CLI::success( "File {$this->name}.php scaffolded at {$dir}" );

		/*
		 * Next, generate the view file.
		 */
		$view_dir = trailingslashit( plugin_root() . 'views' );
		$file     = $view_dir . "{$this->action}.php";

		if ( ! file_exists( $file ) ) {
			// @codingStandardsIgnoreStart
			$template = fopen( $file, 'w' );

			fwrite( $template, $this->view_template() );
			fclose( $template );
			// @codingStandardsIgnoreEnd

			\WP_CLI::success( "View template for {$this->name} scaffolded at {$file}" );
		}

		return true;
	}

	/**
	 * Template for scaffolding a concrete Pattern class.
	 *
	 * @return string
	 */
	private function template() {
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
	 * Template for scaffolding a Pattern view file.
	 */
	private function view_template() {
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
