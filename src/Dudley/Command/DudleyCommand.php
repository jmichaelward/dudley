<?php

namespace Dudley\Command;

use Dudley\Dudley;

/**
 * Manage the Dudley Patterns Framework.
 *
 * @package Dudley\Command
 */
class DudleyCommand extends \WP_CLI_Command {
	/**
	 * Collection of subcommands.
	 *
	 * @var array
	 */
	private $subcommands = [];

	/**
	 * Main plugin class.
	 *
	 * @var Dudley $plugin
	 */
	private $plugin;

	/**
	 * DudleyCommand constructor.
	 *
	 * Add the plugin's primary command and all of its subcommands.
	 *
	 * @param Dudley $plugin Main plugin class.
	 */
	public function __construct( Dudley $plugin ) {
		$this->plugin = $plugin;

		\WP_CLI::add_command( 'dudley', $this );

		foreach (
			[
				SetupSubcommand::class,
				ScaffoldSubcommand::class,
			] as $subcommand
		) {
			$this->subcommands[ $subcommand::$name ] = new $subcommand( $plugin );
		}
	}

	/**
	 * Scaffold a new pattern class and view.
	 *
	 * ## OPTIONS
	 * <pattern>
	 * : Type of pattern to create (must be one of: item, single, or repeater)
	 *
	 * [--name=<name>]
	 * : Optional if an action name is provided. Used to give a class a custom name different from the action name.
	 *
	 * [--action=<action_name>]
	 * : Action name for the pattern. Used for creating a directory and class. Will also be used for the class name if none is passed.
	 *
	 * [--meta=<meta_type>]
	 * : Meta field name to use.
	 *
	 * ## EXAMPLESr
	 *
	 *  wp dudley scaffold item
	 *  wp dudley scaffold single --action=banner
	 *  wp dudley scaffold single --name=CTABanner --action=banner_with_cta --meta=acf
	 *  wp dudley scaffold repeater --action=faqs --meta=cmb2
	 */
	public function scaffold( $args, $assoc_args ) {
		$this->subcommands[ ScaffoldSubcommand::$name ]->run( $args, $assoc_args );
	}

	/**
	 * Re-runs meta field setup tasks, such as copying JSON field group definitions from packages to their correct directory.
	 */
	public function setup( $args, $assoc_args ) {
		$this->subcommands[ SetupSubcommand::$name ]->run( $args, $assoc_args );
	}
}
