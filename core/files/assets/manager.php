<?php
namespace Elementor\Core\Files\Assets;

use Elementor\Core\Files\Assets\Svg\Svg_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Classic Elements files manager.
 *
 * Classic Elements files manager handler class is responsible for creating files.
 *
 * @since 2.6.0
 */
class Manager {

	/**
	 * Holds registered asset types
	 * @var array
	 */
	protected $asset_types = [];

	/**
	 * Assets manager constructor.
	 *
	 * Initializing the Classic Elements assets manager.
	 *
	 * @access public
	 */
	public function __construct() {
		$this->register_asset_types();
		/**
		 * Classic Elements files assets registered.
		 *
		 * Fires after Classic Elements registers assets types
		 *
		 * @since 2.6.0
		 */
		do_action( 'elementor/core/files/assets/assets_registered', $this );
	}

	public function get_asset( $name ) {
		return isset( $this->asset_types[ $name ] ) ? $this->asset_types[ $name ] : false;
	}

	/**
	 * Add Asset
	 * @param $instance
	 */
	public function add_asset( $instance ) {
		$this->asset_types[ $instance::get_name() ] = $instance;
	}


	/**
	 * Register Asset Types
	 *
	 * Registers Classic Elements Asset Types
	 */
	private function register_asset_types() {
		$this->add_asset( new Svg_Handler() );
	}
}
