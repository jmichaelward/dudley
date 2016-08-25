<?php

namespace Tfive\ACF\Abstracts;

use Tfive\ACF\Traits\RepeaterTrait;

/**
 * Class AbstractRepeater
 * @package ThreeFiveACF\Abstracts
 */
abstract class AbstractRepeater extends AbstractPattern {
	use RepeaterTrait;

	/**
	 * @var array $items
	 */
	protected $items = [ ];
}
