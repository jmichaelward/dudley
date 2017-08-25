<?php
namespace Dudley\Command\Scaffold;

use function Dudley\plugin_root;

/**
 * Class AbstractViewScaffold
 *
 * $name gets used to name the class file, unless it's missing, then $action is used.
 * $action gets used to name the view, unless it's missing, the $name is used.
 * $action and $meta are both used to generate the name of the action that gets used to request the view.
 *
 * @package Dudley\Command\Scaffold
 */
abstract class AbstractViewScaffold extends AbstractItemScaffold {
	/**
	 * Name of the pattern passed as an argument to the wp dudley scaffold <pattern> command.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Name of the action passed as an argument to the wp dudley scaffold <pattern> command.
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Name of the meta type passed as an argument to the wp dudley scaffold <pattern> command.
	 *
	 * @var string
	 */
	protected $meta;

	/**
	 * Method called by the WP-CLI command.
	 */
	public function scaffold() {
		$this->generate_class_file();
		$this->generate_pattern_view_file();
	}

	/**
	 * Generate a file for this pattern inside the plugin's /views directory.
	 *
	 * @return bool
	 */
	public function generate_pattern_view_file() {
		/*
        * Next, generate the view file.
        */
		$view_dir = trailingslashit( plugin_root() . 'views' );
		$file     = $view_dir . "{$this->action}.php";

		if ( file_exists( $file ) ) {
			\WP_CLI::warning( "View template for {$this->name} already exists." );
			return false;
		}

		// @codingStandardsIgnoreStart
		$template = fopen( $file, 'w' );

		fwrite( $template, $this->scaffold_pattern_view() );
		fclose( $template );
		// @codingStandardsIgnoreEnd

		\WP_CLI::success( "View template for {$this->name} scaffolded at {$file}" );

		return true;
	}

	/**
	 * Scaffold the pattern's view file with boilerplate code.
	 *
	 * This method is implemented by the concrete scaffolding class.
	 */
	abstract public function scaffold_pattern_view();
}
