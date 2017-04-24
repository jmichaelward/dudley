<?php
namespace Dudley\MetaType;

use Dudley\Dudley;

/**
 * WordPress hooks that need to run against this meta type.
 *
 * @return mixed
 */
abstract class AbstractMetaType {
	/**
	 * Patterns that can generate module output for use by a theme. Populate to configure.
	 *
	 * @var Dudley $plugin The main patterns plugin class.
	 */
	public $plugin;

	/**
	 * AbstractMetaType constructor.
	 *
	 * @param Dudley $plugin The main patterns plugin class.
	 */
	public function __construct( Dudley $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Any installation operations that need to be run for this Meta Type's fields to be accessible.
	 */
	abstract public function setup();

	/**
	 * Hooks to run on this meta type.
	 */
	abstract public function hooks();
}
