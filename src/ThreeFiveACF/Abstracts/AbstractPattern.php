<?php

namespace ThreeFiveACF\Abstracts;

/**
 * Class AbstractPattern
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractPattern
{
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
}
