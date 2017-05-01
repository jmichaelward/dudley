<?php

namespace Dudley\Command;

/**
 * Interface SubCommandInterface
 *
 * @package Dudley\Command
 */
interface SubCommandInterface {
	/**
	 * Run the subcommand.
	 */
	public function run();
}
