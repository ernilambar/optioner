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

		// Create admin page.
		add_action( 'admin_menu', array( $this, 'create_menu_page' ) );

		// Register settings.
		add_action( 'admin_init', array( $this, 'register_settings' ) );
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

		echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';

		echo '<form action="options.php" method="post">';

		settings_fields( $this->base_args['option_slug'] . '-group' );

		foreach ( $this->base_args['tabs'] as $tab_key => $tab ) {

			echo '<div id="npf-' . $tab['id'] . '" class="single-tab-content">';
			do_settings_sections( $tab['id'] . '-' . $this->base_args['menu_slug'] );
			echo '</div>';

		}

		submit_button( esc_html__( 'Save Changes' ) );

		echo '</form>';

		echo '</div>';
	}

	function register_settings() {
		register_setting( $this->base_args['option_slug'] . '-group', $this->base_args['option_slug'], array( $this, 'sanitize_callback' ) );

		foreach ( $this->base_args['tabs'] as $tab_key => $tab ) {
			add_settings_section(
				$tab['id'] . '_settings' . '-' . $this->base_args['menu_slug'],
				$tab['title'],
				array( $this, 'section_text_callback' ),
				$tab['id'] . '-' . $this->base_args['menu_slug']
			);

			foreach ( $tab['fields'] as $field_key => $field ) {
				$args = array(
					'field'       => $field,
					'field_id'    => $field['id'],
					'field_name'  => $this->base_args['option_slug'] . '[' . $field['id'] . ']',
					'tab'         => $tab,
					'base_args'   => $this->base_args,
					'field_value' => ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '',
					'options'     => $this->options,
				);

				add_settings_field(
					$field_key,
					$field['title'],
					array( $this, 'field_callback' ),
					$tab['id'] . '-' . $this->base_args['menu_slug'],
					$tab['id'] . '_settings' . '-' . $this->base_args['menu_slug'],
					$args
				);
			}
		}
	}

	function field_callback( $args ) {
		$field_type = $args['field']['type'];
		nspre( $field_type );
		return;
		if ( ! class_exists( 'npf_field_' . $field_type ) ) {
			$ov_file = $field_type . '.php';
			echo 'Class <strong>npf_field_' . $field_type . '</strong> does not exist.';
			return;
		}
		$class    = 'npf_field_' . $field_type;
		$instance = $class::getInstance();
		$instance->render_field( $args );
		$instance->show_description( $args );

	}


	function section_text_callback( $arg ) {
		$id              = $arg['id'];
		$current_section = str_replace( '_settings', '', $id );
		$sub_heading     = '';
		$callback_object = $arg['callback'][0];

		if ( isset( $callback_object->base_args['tabs'][ $current_section ]['sub_heading'] ) ) {
			$sub_heading = $callback_object->base_args['tabs'][ $current_section ]['sub_heading'];
		}

		if ( ! empty( $sub_heading ) ) {
			echo '<div class="optioner-subheading">' . $sub_heading . '</div>';
		}

	}


}
