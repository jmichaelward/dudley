<?php

namespace Tfive\ACF\Abstracts;

use Tfive\ACF\Traits\ActionTrait;
use Tfive\ACF\Template\Template;

/**
 * Class AbstractPattern
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractPattern
{
	use ActionTrait;

	/**
	 * @return array
	 */
	abstract public function requirements();

	/**
	 * @return bool
	 */
	public function has_required() {
		$req = $this->requirements();

		return count( $req ) === count( array_filter( $req ) );
	}

	/**
	 *
	 */
	public static function get_template() {
		$class    = get_called_class();
		$template = new Template( new $class );
		$template->render();
	}
}
