<?php
namespace Dudley\Patterns\Traits;

/**
 * Trait ImageTrait
 *
 * @package Dudley\Patterns\Traits
 */
trait ImageTrait {
	/**
	 * Image asset.
	 *
	 * @var array
	 */
	protected $img;

	/**
	 * Size of the image asset.
	 *
	 * @var string
	 */
	protected $img_size;

	/**
	 * Return this object's image, if assigned.
	 *
	 * @return array
	 */
	public function get_img() {
		return $this->img;
	}

	/**
	 * Get the URL for an image. Defaults to full-size if no size is passed, or an empty string if
	 * the image is not an array.
	 *
	 * @throws \Exception Must declare image size in the module.
	 *
	 * @return string
	 */
	public function get_img_url() {
		if ( is_null( $this->img_size ) ) {
			throw new \Exception( '$img_size property not declared in ' . get_called_class() );
		}

		if ( is_array( $this->img ) ) {
			return $this->img_size ? $this->img['sizes'][ $this->img_size ] : $this->img['url'];
		}

		return '';
	}

	/**
	 * Print the URL to an image asset. Defaults to full-size if no parameter is passed.
	 */
	public function img_url() {
		echo esc_url( $this->get_img_url() );
	}

	/**
	 * Print the alternate text attribute for an image.
	 */
	public function img_alt() {
		echo esc_attr( $this->img['alt'] );
	}

	/**
	 * Print the srcset attribute for an image.
	 */
	public function img_srcset() {
		echo esc_attr( wp_get_attachment_image_srcset( $this->img['ID'], $this->img_size ) );
	}

	/**
	 * Print the image sizes attribute for an image.
	 */
	public function img_sizes() {
		echo esc_attr( wp_get_attachment_image_sizes( $this->img['ID'], $this->img_size ) );
	}

	/**
	 * Set the size of the image for this object. Creates a filter to override in the theme.
	 *
	 * @param string $size        Size of the image.
	 * @param string $action_name (Optional) Name of the pattern's action.
	 *
	 * @return string Image size.
	 */
	protected function set_img_size( $size, $action_name = '' ) {
		if ( ! $action_name ) {
			$pattern     = get_called_class();
			$action_name = $pattern::$action_name;
		}

		return apply_filters( 'dudley_' . $action_name . '_image_size', $size );
	}
}
