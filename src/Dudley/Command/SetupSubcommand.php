<?php

namespace Dudley\Command;

use Dudley\Dudley;
use Dudley\MetaType\AbstractMetaType;

/**
 * Class SetupSubcommand.
 *
 * @package Dudley\Command
 */
class SetupSubcommand implements SubcommandInterface {
	/**
	 * The registered meta type.
	 *
	 * @var AbstractMetaType $meta_type
	 */
	private $meta_type;

	/**
	 * SetupSubcommand constructor.
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
	 *
	 * @param array $args
	 * @param array $assoc_args
	 */
	public function run( $args, $assoc_args ) {
		$this->meta_type->setup();
	}
}
