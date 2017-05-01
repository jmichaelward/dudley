<?php

namespace Dudley\Command;

interface SubCommandInterface {
	/**
	 * Run the subcommand.
	 */
	public function run();
}
