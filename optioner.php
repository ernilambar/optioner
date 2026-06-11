<?php
/**
 * Initialize
 *
 * @package Optioner
 */

namespace Nilambar\Optioner;

if ( ! class_exists( Init_3_1_4::class, false ) ) {

	/**
	 * Init class.
	 *
	 * @since 1.0.0
	 */
	class Init_3_1_4 {

		/**
		 * Version.
		 *
		 * @since 1.0.0
		 *
		 * @var string
		 */
		const VERSION = '3.1.4';

		/**
		 * Priority.
		 *
		 * @since 1.0.0
		 *
		 * @var int
		 */
		const PRIORITY = 9975;

		/**
		 * Instance.
		 *
		 * @since 1.0.0
		 *
		 * @var object
		 */
		public static $single_instance = null;

		/**
		 * Create singleton instance.
		 *
		 * @since 1.0.0
		 */
		public static function initiate() {
			if ( null === self::$single_instance ) {
				self::$single_instance = new self();
			}
			return self::$single_instance;
		}

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			if ( ! defined( 'OPTIONER_LOADED' ) ) {
				define( 'OPTIONER_LOADED', self::PRIORITY );
			}

			add_action( 'init', [ $this, 'include_lib' ], self::PRIORITY );
			add_action( 'admin_enqueue_scripts', [ $this, 'load_assets' ] );
		}

		/**
		 * Converts an absolute filesystem path to a URL.
		 *
		 * Works regardless of whether the package lives in a plugin, theme, or elsewhere under ABSPATH.
		 *
		 * @since 1.0.0
		 *
		 * @param string $path Absolute filesystem path.
		 * @return string URL with no trailing slash.
		 */
		private static function path_to_url( $path ) {
			$path        = wp_normalize_path( $path );
			$content_dir = wp_normalize_path( WP_CONTENT_DIR );

			if ( 0 === strpos( $path, $content_dir ) ) {
				return rtrim( content_url( substr( $path, strlen( $content_dir ) ) ), '/' );
			}

			$abspath = wp_normalize_path( ABSPATH );
			if ( 0 === strpos( $path, $abspath ) ) {
				return rtrim( site_url( substr( $path, strlen( $abspath ) ) ), '/' );
			}

			return '';
		}

		/**
		 * Includes library files.
		 *
		 * @since 1.0.0
		 */
		public function include_lib() {
			if ( class_exists( Optioner::class, false ) ) {
				return;
			}

			if ( ! defined( 'OPTIONER_VERSION' ) ) {
				define( 'OPTIONER_VERSION', self::VERSION );
			}

			if ( ! defined( 'OPTIONER_DIR' ) ) {
				define( 'OPTIONER_DIR', __DIR__ );
			}

			if ( ! defined( 'OPTIONER_URL' ) ) {
				define( 'OPTIONER_URL', self::path_to_url( __DIR__ ) );
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
			$ce_settings['css']        = wp_enqueue_code_editor( [ 'type' => 'css' ] );
			$ce_settings['javascript'] = wp_enqueue_code_editor( [ 'type' => 'javascript' ] );
			$ce_settings['text']       = wp_enqueue_code_editor( [ 'type' => 'text/plain' ] );

			wp_localize_script( 'jquery', 'codeEditorSettings', $ce_settings );

			wp_enqueue_style( 'wp-codemirror' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_media();

			wp_enqueue_style( 'optioner-style', OPTIONER_URL . '/assets/optioner.css', [], OPTIONER_VERSION );

			wp_enqueue_script( 'optioner-scripts', OPTIONER_URL . '/assets/optioner.js', [ 'jquery', 'wp-color-picker', 'code-editor' ], OPTIONER_VERSION, true );

			$localized_array = [
				'storage_key' => $this->get_unique_id( 'optioner-' ) . '-activetab',
			];

			wp_localize_script( 'optioner-scripts', 'optionerObject', $localized_array );
		}

		/**
		 * Gets unique ID.
		 *
		 * @since 2.0.7
		 *
		 * @param string $prefix Prefix for the returned ID.
		 * @return string Unique ID.
		 */
		public function get_unique_id( $prefix = '' ) {
			static $optioner_counter = 0;
			return $prefix . (string) ++$optioner_counter;
		}
	}

	Init_3_1_4::initiate();
}
