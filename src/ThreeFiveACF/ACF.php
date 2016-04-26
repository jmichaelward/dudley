<?php

namespace ThreeFiveACF;

/**
 * Class ACF
 * @package ThreeFiveACF
 */
class ACF
{
	/**
	 * ACF constructor.
	 */
	public function __construct()
	{
		spl_autoload_register( [ $this, 'autoload' ] );

		add_filter( 'acf/settings/save_json', array( $this, 'acf_save_path' ) );
		add_filter( 'acf/settings/load_json', array( $this, 'acf_load_path' ) );

		$this->add_options_page();
	}

	/**
	 * @return string Set the ACF save path for new custom fields.
	 */
	public function acf_save_path()
	{
		return TFP_ROOT . 'acf-json';
	}

	/**
	 * @param $paths
	 *
	 * @return array
	 */
	public function acf_load_path( $paths )
	{
		unset( $paths[0] );

		$paths[] = TFP_ROOT . 'acf-json';

		return $paths;
	}

	private function add_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			acf_add_options_page();
		}
	}

	/**
	 *
	 */
	private function autoload( $class ) {
		$class = str_replace( '\\', '/', $class );
		$parts = explode( '/', $class );

		if ( ! in_array( 'ThreeFiveACF', $parts ) ) {
			return;
		}

		if ( in_array( 'Pattern', $parts ) ) {
			$path = TFP_SRC . '/' . $class . '.php';
		} else {
			$file = array_pop( $parts );
			$path = TFP_SRC . '/' . implode( '/', $parts ) . '/' . $file . '.php';
		}

		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}
}
