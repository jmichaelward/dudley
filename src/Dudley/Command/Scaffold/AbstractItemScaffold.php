<?php
/**
 * This abstract class provides the methods needed to generate a class file and defines a contract for populating it
 * with boilerplate code.
 *
 * @package Dudley\Command\Scaffold
 */

namespace Dudley\Command\Scaffold;

use function Dudley\plugin_root;
use function Dudley\ds;

/**
 * Class AbstractItemScaffold
 *
 * @package Dudley\Command\Scaffold
 */
abstract class AbstractItemScaffold {
	/**
	 * Name of the pattern passed as an argument to the wp dudley scaffold <pattern> command.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Scaffold an concrete implementation of AbstractItem.
	 */
	public function scaffold() {
		$this->generate_class_file( "{$this->name}Item.php" );
	}

	/**
	 * Generate a directory and class file inside the /patterns directory.
	 *
	 * @param string $file Name of the file to generate.
	 *
	 * @return bool Whether the file was successfully created.
	 */
	public function generate_class_file( $file = '' ) {
		$dir  = trailingslashit( plugin_root() . 'patterns' . ds() . $this->name );
		$file = $dir . ( $file ? $file : "{$this->name}.php" );

		// File already exists, so let's bail.
		if ( file_exists( $dir ) && file_exists( $file ) ) {
			\WP_CLI::log( "File {$file} already exists at {$dir}" );

			return false;
		}

		// Check if the directory exists and create it if it doesn't.
		if ( ! file_exists( $dir ) ) {
			mkdir( $dir ); // @codingStandardsIgnoreLine
		}

		// @TODO Use the Filesystem API instead.
		// Check if the file exists in the new directory and create it if it doesn't.
		if ( ! file_exists( $file ) ) {
			// @codingStandardsIgnoreStart
			$template = fopen( $file, 'w' );

			fwrite( $template, $this->scaffold_class_template() );
			fclose( $template );
			// @codingStandardsIgnoreEnd
		}

		\WP_CLI::success( "File {$file} scaffolded at {$dir}" );

		return true;
	}

	/**
	 * Function that contains the boilerplate code to insert into the class file. Implemented by the concrete scaffolding class.
	 */
	abstract public function scaffold_class_template();
}
