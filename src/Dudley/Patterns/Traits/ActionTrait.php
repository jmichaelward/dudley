<?php
namespace Dudley\Patterns\Traits;

/**
 * Class ActionTrait
 *
 * @author Jeremy Ward <jeremy@jmichaelward.com>
 * @since 1.0.0
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
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public static function get_action_name() {
		$class = get_called_class();

		return $class::$action_name;
	}
}
