<?php
namespace Dudley\Patterns\Traits;

/**
 * Class ActionTrait
 *
 * @package Dudley\Patterns\Traits
 */
trait ActionTrait {
	/**
	 * Name of meta type (e.g., acf, cmb2). Used for action creation.
	 *
	 * @var string
	 */
	public static $meta_type;

	/**
	 * Base name for the action (e.g., banner).
	 *
	 * @var string
	 */
	public static $action_name;

	/**
	 * Returns the action name.
	 *
	 * @return string
	 */
	public static function get_action_name() {
		$class = get_called_class();

		return $class::$action_name;
	}
}
