<?php
/**
 * Initialize
 *
 * @package Optioner
 */

namespace Nilambar\Optioner;

if ( ! class_exists( Init_2_0_7::class, false ) ) {

	class Init_2_0_7 {

		const VERSION = '2.0.7';

		const PRIORITY = 9995;

		public static $single_instance = null;

		public static function initiate() {
			if ( null === self::$single_instance ) {
				self::$single_instance = new self();
			}
			return self::$single_instance;
		}

		private function __construct() {
			if ( ! defined( 'OPTIONER_LOADED' ) ) {
				define( 'OPTIONER_LOADED', self::PRIORITY );
			}

			add_action( 'init', array( $this, 'include_lib' ), self::PRIORITY );
			add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
		}

		public function include_lib() {
			if ( class_exists( Optioner::class, false ) ) {
				return;
			}

			if ( ! defined( 'OPTIONER_VERSION' ) ) {
				define( 'OPTIONER_VERSION', self::VERSION );
			}

			if ( ! defined( 'OPTIONER_DIR' ) ) {
				define( 'OPTIONER_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
			}

			if ( ! defined( 'OPTIONER_URL' ) ) {
				define( 'OPTIONER_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );
			}

			if ( ! class_exists( \WPTRT\Autoload\Loader::class, false ) ) {
				require_once __DIR__ . '/Loader.php';
			}

			$loader = new \WPTRT\Autoload\Loader();
			$loader->add( 'Nilambar\\Optioner\\', __DIR__ . '/src' );
			$loader->register();

			require_once __DIR__ . '/bootstrap.php';
			optioner_bootstrap();
		}

		/**
		 * Load assets.
		 *
		 * @since 1.0.0
		 */
		public function load_assets() {
			$ce_settings['css']        = wp_enqueue_code_editor( array( 'type' => 'css' ) );
			$ce_settings['javascript'] = wp_enqueue_code_editor( array( 'type' => 'javascript' ) );

			wp_localize_script( 'jquery', 'codeEditorSettings', $ce_settings );

			wp_enqueue_style( 'wp-codemirror' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_media();

			wp_enqueue_style( 'optioner-style', OPTIONER_URL . '/assets/optioner.css', array(), OPTIONER_VERSION );

			wp_enqueue_script( 'optioner-scripts', OPTIONER_URL . '/assets/optioner.js', array( 'jquery', 'wp-color-picker', 'code-editor' ), OPTIONER_VERSION, true );

			$localized_array = array(
				'storage_key' => wp_unique_id( 'optioner-' ) . '-activetab',
			);

			wp_localize_script( 'optioner-scripts', 'optionerObject', $localized_array );
		}
	}

	Init_2_0_7::initiate();
}
