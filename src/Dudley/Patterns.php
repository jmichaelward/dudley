<?php
namespace Dudley\Patterns;

use Dudley\Patterns\MetaType\ACF;
use function Dudley\plugin_root;

/**
 * Class Patterns
 *
 * @package Dudley\Patterns
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
		$this->notifier = new AdminNotifier();
	}

	/**
	 * Run the plugin and initialize hooks.
	 */
	public function run() {
		$this->hooks();
	}

	public function hooks() {
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
				add_action( 'dudley_' . $class_name::$meta_type . '_' . $class_name::$action_name, [
					$class_name,
					'render_view',
				], 10, 1 );
			}
		}
	}

	/**
	 * Iterate through the plugin to get all of the registered class files. Used by filter
	 * Ripped from here: http://stackoverflow.com/questions/22761554/php-get-all-class-names-inside-a-particular-namespace
	 *
	 * Thanks, Internet!
	 */
	private function get_patterns_classes() {
		$path = plugin_root();
		$fqcns = []; // Fully qualified class namespace.

		$allFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
		$phpFiles = new \RegexIterator($allFiles, '/\.php$/');

		foreach ( $phpFiles as $phpFile ) {
			$content   = file_get_contents( $phpFile->getRealPath() );
			$tokens    = token_get_all( $content );
			$namespace = '';

			for ( $index = 0; isset( $tokens[ $index ] ); $index++ ) {
				if ( ! isset( $tokens[ $index ][0] ) ) {
					continue;
				}

				if ( T_NAMESPACE === $tokens[ $index ][0] ) {
					$index += 2; // Skip namespace keyword and whitespace

					while ( isset( $tokens[ $index ] ) && is_array( $tokens[ $index ] ) ) {
						$namespace .= $tokens[ $index++ ][1];
					}
				}

				if ( T_CLASS === $tokens[ $index ][0] ) {
					$index   += 2; // Skip class keyword and whitespace
					$fqcns[] = $namespace . '\\' . $tokens[ $index ][1];
				}
			}
		}

		return $fqcns;
	}

	/**
	 * Process the array in the Composer autoload classmap in order to automatically include our selected patterns.
	 */
	public function load_patterns() {
		if ( ! $pattern_classes = $this->get_patterns_classes() ) {
			$this->notifier->patterns_not_found();
		}

		return $this->filter_classes_for_patterns( $pattern_classes );
	}

	/**
	 * Filter through the set of Dudley Patterns classes and return those with registered actions.
	 *
	 * @param array $patterns
	 *
	 * @return array
	 */
	private function filter_classes_for_patterns( array $patterns ) {
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
		}, array_values( $patterns ) ) );
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
