<?php

namespace Dudley\Command;

use Dudley\Dudley;
use Dudley\MetaType\AbstractMetaType;

/**
 * Class DudleySetupCommand.
 *
 * @package Dudley\Command
 */
class DudleySetupCommand implements SubCommandInterface {
	/**
	 * The registered meta type.
	 *
	 * @var AbstractMetaType $meta_type
	 */
	private $meta_type;

	/**
	 * DudleySetupCommand constructor.
	 *
	 * @param Dudley $plugin Main plugin class.
	 */
	public function __construct( Dudley $plugin ) {
		$this->meta_type = $plugin->config->meta_type;
	}

	/**
	 * Name of the subcommand.
	 *
	 * @var string
	 */
	public static $name = 'setup';

	/**
	 * Run the subcommand.
	 *
	 * Find the registered meta type and run its setup task.
	 */
	public function run() {
		$this->meta_type->setup();
	}
}
