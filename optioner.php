<?php
/**
 * Initialize
 *
 * @package Optioner
 */

namespace Nilambar\Optioner;

define( 'OPTIONER_VERSION', '2.0.2' );
define( 'OPTIONER_SLUG', 'optioner' );

define( 'OPTIONER_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'OPTIONER_BASEFILE', plugin_basename( __FILE__ ) );

if ( ! defined( 'OPTIONER_DIR' ) ) {
	define( 'OPTIONER_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
}

if ( ! defined( 'OPTIONER_URL' ) ) {
	define( 'OPTIONER_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
}

/**
 * Init class.
 *
 * @since 1.0.0
 */
class Init {

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
	}

	/**
	 * Load assets.
	 *
	 * @since 1.0.0
	 */
	public function load_assets() {
		$ce_settings['css'] = wp_enqueue_code_editor(array('type' => 'css'));
		$ce_settings['javascript'] = wp_enqueue_code_editor(array('type' => 'javascript'));
	  wp_localize_script('jquery', 'ce_settings', $ce_settings);

	  wp_enqueue_style('wp-codemirror');

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_media();

		wp_enqueue_style( 'optioner-style', OPTIONER_URL . '/assets/optioner.css', array(), OPTIONER_VERSION );

		wp_enqueue_script( 'optioner-scripts', OPTIONER_URL . '/assets/optioner.js', array( 'jquery', 'wp-color-picker', 'code-editor' ), OPTIONER_VERSION, true );

		$localized_array = array(
			'storage_key' => wp_unique_id( 'optioner-' ) . '-activetab',
		);

		wp_localize_script( 'optioner-scripts', 'OPTIONER_OBJ', $localized_array );
	}
}

new Init();
