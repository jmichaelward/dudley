<?php
namespace Dudley\Patterns\Abstracts;

use Dudley\Patterns\Traits\RepeaterTrait;

/**
 * Class AbstractRepeater
 *
 * @package Dudley\Patterns\Abstracts
 */
abstract class AbstractRepeater extends AbstractPattern {
	use RepeaterTrait;

	/**
	 * Collection of items in the module.
	 *
	 * @var array $items
	 */
	protected $items = [];
}
