<?php
namespace Dudley\Patterns\Abstracts;

use Dudley\Patterns\Traits\ActionTrait;
use Dudley\Template\Template;

/**
 * The primary abstract class for rendering an ACF View. All patterns should define a public static $action_name
 * property (e.g., public static $action_name = 'faq_list'). This gets used to register an action within WordPress, and
 * to define the expected name of the view file (e.g., faq-list.php).
 *
 * If the concrete class you're writing does not power an element that needs to render its own view, extend AbstractItem
 * instead.
 *
 * Class AbstractPattern
 *
 * @package Dudley\Patterns\Abstracts
 */
abstract class AbstractPattern extends AbstractItem {
	use ActionTrait;

	/**
	 * @param $args Possible arguments to pass to the pattern instantiated by the template.
	 */
	public static function render_view( $args = null ) {
		$template = new Template( get_called_class(), $args );
		$template->render();
	}
}
