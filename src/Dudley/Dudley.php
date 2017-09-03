<?php
namespace Dudley;

use Dudley\Admin\Notifier;
use Dudley\Command\DudleyCommand;
use Dudley\Patterns\Abstracts\AbstractPattern;
use Dudley\Patterns\Traits\ActionTrait;

/**
 * Class Dudley
 *
 * @author Jeremy Ward <jeremy@jmichaelward.com>
 * @since 1.0.0
 *
 * @package Dudley\Patterns
 */
final class Dudley {
	/**
	 * Dudley configuration class.
	 *
	 * @var $config Config
	 */
	public $config;

	/**
	 * Admin notifier class.
	 *
	 * @var $notifier Notifier
	 */
	private $notifier;

	/**
	 * Collection of found patterns.
	 *
	 * @var array $patterns
	 */
	private $patterns;

	/**
	 * Set of registered WP-CLI commands.
	 *
	 * @var array
	 */
	private $commands;

	/**
	 * Patterns constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->config   = new Config( $this );
		$this->notifier = new Notifier();
	}

	/**
	 * Run the plugin and initialize hooks.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		$this->hooks();
		$this->config->meta_type->hooks();
	}

	/**
	 * Register plugin hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		// Register activation and deactivation hooks.
		register_activation_hook( plugin_root() . '/dudley.php', [ $this, 'activate' ] );
		register_deactivation_hook( plugin_root() . '/dudley.php', [ $this, 'deactivate' ] );

		// Setup the plugin.
		add_action( 'plugins_loaded', [ $this, 'init' ] );

		// Initialize WP-CLI commands.
		add_action( 'init', [ $this, 'register_commands' ] );
	}

	/**
	 * Setup ACF patterns and register hooks.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		$this->patterns = $this->load_patterns();
		$this->register_actions();
	}

	/**
	 * Register WP-CLI commands.
	 */
	public function register_commands() {
		if ( ! class_exists( 'WP_CLI_Command' ) ) {
			return;
		}

		$this->commands = [ new DudleyCommand( $this ) ];
	}

	/**
	 * Enable access to private class properties that are needed within the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @param string $field The property to access.
	 *
	 * @return mixed
	 * @throws \Exception Not allowed to access property.
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
	 *
	 * @since 1.0.0
	 */
	private function register_actions() {
		/**
		 * Filters the prefix for the framework-registered actions.
		 *
		 * Allows developers to fully customize the names of their hooks.
		 *
		 * @since 1.1.3
		 */
		$action_prefix = apply_filters( 'dudley_action_prefix', 'dudley' );

		// Add actions for each class.
		foreach ( $this->patterns as $class_name ) {
			/**
			 * The ActionTrait of an AbstractPattern. Dynamically registers actions for use in templates based on the
			 * meta field type and registered action name for the pattern.
			 *
			 * @var $class_name ActionTrait
			 */
			if ( ! $class_name::$meta_type ) {
				continue;
			}

			// Add the action for patterns with a meta_type. e.g., dudley_acf_banner.
			add_action( "{$action_prefix}_{$class_name::$meta_type}_{$class_name::$action_name}", [
				$class_name,
				'render_view',
			], 10, 1 );
		}
	}

	/**
	 * Iterate through the plugin to get all of the registered class files. Used by filter
	 * Ripped from here: http://stackoverflow.com/questions/22761554/php-get-all-class-names-inside-a-particular-namespace
	 *
	 * Thanks, Internet!
	 *
	 * @since 1.0.0
	 */
	private function get_patterns_classes() {
		$fqcns = []; // Fully qualified class namespace.

		$all_files = new \RecursiveIteratorIterator( new \RecursiveDirectoryIterator( plugin_root() ) );
		$php_files = new \RegexIterator( $all_files, '/\.php$/' );

		foreach ( $php_files as $php_file ) {
			$content   = file_get_contents( $php_file->getRealPath() ); // @codingStandardsIgnoreLine
			$tokens    = token_get_all( $content );
			$namespace = '';

			for ( $index = 0; isset( $tokens[ $index ] ); $index++ ) {
				if ( ! isset( $tokens[ $index ][0] ) ) {
					continue;
				}

				if ( T_NAMESPACE === $tokens[ $index ][0] ) {
					$index += 2; // Skip namespace keyword and whitespace.

					while ( isset( $tokens[ $index ] ) && is_array( $tokens[ $index ] ) ) {
						$namespace .= $tokens[ $index++ ][1];
					}
				}

				if ( T_CLASS === $tokens[ $index ][0] ) {
					$index   += 2; // Skip class keyword and whitespace.

					if ( isset( $tokens[ $index ][1] ) ) {
						$fqcns[] = $namespace . '\\' . $tokens[ $index ][1];
					}
				}
			}
		}

		return $fqcns;
	}

	/**
	 * Process the array in the Composer autoload classmap in order to automatically include our selected patterns.
	 *
	 * @since 1.0.0
	 */
	public function load_patterns() {
		$pattern_classes = $this->get_patterns_classes();
		$pattern_classes = $this->filter_classes_for_patterns( $pattern_classes );

		if ( ! $pattern_classes ) {
			$this->notifier->patterns_not_found();
		}

		return $pattern_classes;
	}

	/**
	 * Filter through the set of Dudley Patterns classes and return those with registered actions.
	 *
	 * @since 1.0.0
	 *
	 * @param array $patterns Array of found classes in this plugin.
	 *
	 * @return array
	 */
	private function filter_classes_for_patterns( array $patterns ) {
		return array_filter( array_map( function ( $pattern_class ) {
			// \WP_CLI_Command class is not found on front-end. This guards against a fatal error.
			if ( false !== strpos( $pattern_class, 'Dudley\\Command' ) ) {
				return false;
			}

			if ( is_subclass_of( $pattern_class, AbstractPattern::class ) ) {
				return $pattern_class;
			}

			return false;
		}, array_values( $patterns ) ) );
	}

	/**
	 * Run on plugin activation.
	 *
	 * @since 1.0.0
	 */
	public function activate() {
		if ( $this->config->meta_type ) {
			$this->config->meta_type->setup();
			$this->config->meta_type->hooks();
		}
	}

	/**
	 * Run on plugin deactivation.
	 *
	 * @since 1.0.0
	 */
	public function deactivate() {}
}
