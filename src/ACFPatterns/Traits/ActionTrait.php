<?php

namespace Tfive\ACF\Traits;

/**
 * Class ActionTrait
 * @package ThreeFiveACF\Traits
 */
trait ActionTrait
{
	/**
	 * @var string
	 */
	public static $action_name;

	/**
	 * @return string
	 */
	public static function get_action_name() {
		$class = get_called_class();

		return $class::$action_name;
	}
}
