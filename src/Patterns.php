<?php
namespace Tfive\Patterns;

/**
 * Class Patterns
 *
 * @package Tfive\Patterns
 */
final class Patterns {
	/**
	 * Advanced Custom Fields patterns class.
	 *
	 * @var $acf ACF
	 */
	public $acf;

	/**
	 * @var $notifier AdminNotifier
	 */
	private $notifier;

	/**
	 * @var array $patterns
	 */
	private $patterns;

	public function __construct() {
		require 'AdminNotifier.php';
		$this->notifier = new AdminNotifier();
	}

	/**
	 * Run the plugin and initialize hooks.
	 */
	public function run() {
		// Setup the plugin.
		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Setup ACF patterns and register hooks.
	 */
	public function init() {
		if ( ! class_exists( 'acf' ) ) {
			$this->notifier->missing_acf_requirement();
		}

		$this->patterns = $this->load_patterns();
		$this->register_actions();

		$this->acf = new ACF( $this );

		if ( $this->acf ) {
			$this->acf->hooks();
		}
	}

	/**
	 * Enable access to private class properties that are needed within the plugin.
	 *
	 * @param $field
	 *
	 * @return mixed
	 * @throws \Exception
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'patterns':
				return $this->$field;
			default:
				throw new \Exception( 'Access denied to property in ' . __CLASS__ . ', or property does not exist.' );
		}
	}

	/**
	 * Bootstrap modules and assign an action to each component for use in templates.
	 */
	private function register_actions() {
		// Add actions for each class.
		foreach ( $this->patterns as $class_name ) {
			/**
			 * The ActionTrait of an AbstractPattern. Dynamically registers actions for use in templates based on the
			 * meta field type and registered action name for the pattern.
			 *
			 * @var $class_name ActionTrait
			 */
			if ( $class_name::$meta_type ) {
				add_action( 'tf_' . $class_name::$meta_type . '_' . $class_name::$action_name, [
					$class_name,
					'render_view',
				], 10, 1 );
			}
		}
	}

	/**
	 * Process the array in the Composer autoload classmap in order to automatically include our selected patterns.
	 */
	public function load_patterns() {
		$pattern_classes = require plugin_root() . 'vendor/composer/autoload_classmap.php';

		if ( ! $pattern_classes ) {
			$this->notifier->missing_autoload_classmap();
		}

		return $this->get_patterns( $pattern_classes );
	}

	/**
	 * Filter through the set of 3five ACF Patterns classes and return those with registered actions.
	 *
	 * @param array $patterns
	 *
	 * @return array
	 */
	private function get_patterns( array $patterns ) {
		return array_filter( array_map( function( $pattern_class ) {
			// Check for patterns that also have a set action name so we can register them.
			if ( ! ( strpos( $pattern_class, '\\Pattern\\' ) && property_exists( $pattern_class, 'action_name' ) ) ) {
				return false;
			}

			// Return the pattern if it has an associated action.
			if ( $pattern_class::$action_name ) {
				return $pattern_class;
			}

			// All patterns must have the $action_name property. Something went wrong.
			throw new \LogicException( 'No action defined for ' . $pattern_class );
		}, array_keys( $patterns ) ) );
	}

	/**
	 * Run on plugin activation.
	 */
	public function activate() {
		if ( ! class_exists( 'acf' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );

			return;
		}

		ACF::copy_acf_field_groups();
	}

	/**
	 * Run on plugin deactivation.
	 */
	public function deactivate() {
	}
}
