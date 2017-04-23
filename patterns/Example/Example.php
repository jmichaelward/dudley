<?php
namespace Dudley\Patterns\Pattern\Example;

use Dudley\Patterns\Abstracts\AbstractPattern;
use Dudley\Patterns\Traits\HeadingTrait;

/**
 * Class Example
 *
 * @package Dudley\Patterns\Pattern\Example
 */
class Example extends AbstractPattern {
	use HeadingTrait;

	/**
	 * Base name of the module, which gets used to create the action.
	 * This will also be the name you will use for your view file. If your action name contains an underscore,
	 * such as 'example_module', its related view file will use hyphens and become 'example-module.php'.
	 *
	 * @var string
	 */
	public static $action_name = 'example';

	/**
	 * Base name of the meta field type being used. For example, 'acf', 'cmb2', and so on. This can be called anything -
	 * it simply gets used to generate the full action name, but also allows you to extend a package that might be
	 * using a different meta type for your own purposes.
	 *
	 * @var string
	 */
	public static $meta_type = 'example';

	/**
	 * Example constructor.
	 *
	 * The constructor simply provides the module with all of the data it needs. Our example module simply outputs
	 * a single heading. We'll assign it here.
	 *
	 * In an actual implementation, you would call `get_field` (for ACF), `get_post_meta` (for CMB2 and standard
	 * WordPress meta), or another helper function if your particular site has on one.
	 */
	public function __construct() {
		$this->heading = 'Thank you for checking out this example from Dudley!';
	}

	/**
	 * A simple indexed array of data values this item requires to render itself. This lets the module determine whether
	 * it can and should render its markup.
	 *
	 * @return array
	 */
	public function requirements() {
		return [
			$this->heading
		];
	}
}
