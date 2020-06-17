<?php
/**
 * Main class
 *
 * @author    Nilambar Sharma <nilambar@outlook.com>
 * @copyright 2020 Nilambar Sharma
 *
 * @package Optioner
 */

namespace Nilambar\Optioner;

/**
 * Optioner class.
 *
 * @since 1.0.0
 */
class Optioner {

	var $base_args;

	var $options;

	var $top_level_menu;

	var $parent_page;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $args ) {
		$this->base_args = $args;
		$this->options   = get_option( $this->base_args['option_slug'] );

		// Check if top level page.
		if ( isset( $this->base_args['top_level_menu'] ) && $this->base_args['top_level_menu'] ) {
			$this->top_level_menu = true;
		} else {
			$this->top_level_menu = false;
		}
		// Set submenu page.
		if ( isset( $this->base_args['parent_page'] ) && ! empty( $this->base_args['parent_page'] ) ) {
			$this->parent_page = $this->base_args['parent_page'];
		} else {
			$this->parent_page = 'options-general.php';
		}

		add_action( 'admin_menu', array( $this, 'create_menu_page' ) );
	}

	function create_menu_page() {
		if ( true == $this->top_level_menu ) {
			add_menu_page(
				$this->base_args['page_title'],
				$this->base_args['menu_title'],
				$this->base_args['capability'],
				$this->base_args['menu_slug'],
				array( $this, 'render_page' )
			);
		} else {
			add_submenu_page(
				$this->parent_page,
				$this->base_args['page_title'],
				$this->base_args['menu_title'],
				$this->base_args['capability'],
				$this->base_args['menu_slug'],
				array( $this, 'render_page' )
			);
		}
	}

	function render_page() {
		echo '<div class="wrap">';
		echo '<h1>' . get_admin_page_title() . '</h1>';
		echo 'This is page callback';
		echo '</div>';
	}
}
