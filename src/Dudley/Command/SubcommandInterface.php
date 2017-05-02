<?php

namespace Dudley\Command;

/**
 * Interface SubcommandInterface
 *
 * @package Dudley\Command
 */
interface SubcommandInterface {
	/**
	 * Run the subcommand.
	 *
	 * @param $args
	 * @param $assoc_args
	 */
	public function run( $args, $assoc_args );
}
