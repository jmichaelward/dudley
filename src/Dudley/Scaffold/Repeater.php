<?php

namespace Dudley\Scaffold;

class Repeater {
	private $name;

	public function __construct( $args ) {

	}

	public function scaffold() {
		\WP_CLI::success( __CLASS__ . ' scaffolded.' );
		return true;
	}
}
