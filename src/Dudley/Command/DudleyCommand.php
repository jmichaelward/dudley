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

		foreach ( [ DudleySetupCommand::class ] as $subcommand ) {
			$this->subcommands[ $subcommand::$name ] = new $subcommand( $plugin );
		}
	}

	/**
	 * Re-runs meta field setup tasks, such as copying JSON field group definitions from packages to their correct directory.
	 */
	public function setup() {
		$this->subcommands[ DudleySetupCommand::$name ]->run();
	}
}
