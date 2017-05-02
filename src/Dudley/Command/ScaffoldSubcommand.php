<?php

namespace Dudley\Command;

use Dudley\Dudley;
use \WP_CLI;

/**
 * Class ScaffoldSubcommand
 *
 * @package Dudley\Command
 */
class ScaffoldSubcommand implements SubcommandInterface {
	/**
	 * @var Dudley
	 */
	private $plugin;

	/**
	 * @var array
	 */
	private $abstract_patterns = [
		'item'     => 'Dudley\Scaffold\Item',
		'single'   => 'Dudley\Scaffold\Single',
		'repeater' => 'Dudley\Scaffold\Repeater',
	];

	/**
	 * ScaffoldSubcommand constructor.
	 *
	 * @param Dudley $plugin Main plugin class.
	 */
	public function __construct( Dudley $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * SubCommand name.
	 *
	 * @var string $name
	 */
	public static $name = 'scaffold';

	/**
	 * Run the command.
	 *
	 * @TODO: Add layout option for FlexibleContent.
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function run( $args, $assoc_args ) {
		// Check for valid pattern type.
		if ( ! $this->valid_pattern( $args[0] ) ) {
			return WP_CLI::error( 'Invalid pattern type entered. Must be one of item, single, or repeater.' );
		}

		if ( ! isset( $assoc_args['name'] ) && ! isset( $assoc_args['action'] ) ) {
			return WP_CLI::error( 'Without a name or an action, we don\'t know what to call your pattern. Please provide one or the other.' );
		}

		if ( $this->create( $args[0], $assoc_args ) ) {
			return $this->success_message( $args[0] );
		}

		return WP_CLI::error( 'Something went wrong' );
	}

	/**
	 * Check whether the selected pattern is a valid type.
	 *
	 * @param string $pattern Type of pattern.
	 *
	 * @return bool
	 */
	private function valid_pattern( $pattern ) {
		return array_key_exists( $pattern, $this->abstract_patterns );
	}

	/**
	 * @param $args
	 */
	private function create( $item, $args ) {
		$class_name = $this->abstract_patterns[ $item ];

		return ( new $class_name( $args ) )->scaffold();
	}

	/**
	 * @param $pattern
	 *
	 * @return mixed
	 */
	private function success_message( $pattern ) {
		return WP_CLI::success( ucwords( $pattern ) . ' created.' );
	}
}
