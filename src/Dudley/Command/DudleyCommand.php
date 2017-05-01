<?php

namespace Dudley\Command;

use Dudley\Dudley;

/**
 * Class DudleyCommand
 *
 * @package Dudley\Command
 */
class DudleyCommand extends \WP_CLI_Command {
	/**
	 * @var array
	 */
	private $subcommands = [];

	/**
	 * @var Dudley $plugin Main plugin class.
	 */
	private $plugin;

	/**
	 * DudleyCommand constructor.
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
