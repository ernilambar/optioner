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

	var $tabs = array();

	var $fields = array();

	var $page = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	}

	public function run() {
		if ( empty( $this->page ) ) {
			return;
		}

		$this->options = get_option( $this->page['option_slug'] );

		// Check if top level page.
		if ( isset( $this->page['top_level_menu'] ) && $this->page['top_level_menu'] ) {
			$this->top_level_menu = true;
		} else {
			$this->top_level_menu = false;
		}

		// Set submenu page.
		if ( isset( $this->page['parent_page'] ) && ! empty( $this->page['parent_page'] ) ) {
			$this->parent_page = $this->page['parent_page'];
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
				$this->page['page_title'],
				$this->page['menu_title'],
				$this->page['capability'],
				$this->page['menu_slug'],
				array( $this, 'render_page' )
			);
		} else {
			add_submenu_page(
				$this->parent_page,
				$this->page['page_title'],
				$this->page['menu_title'],
				$this->page['capability'],
				$this->page['menu_slug'],
				array( $this, 'render_page' )
			);
		}
	}

	function render_page() {
		echo '<div class="wrap">';

		echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';

		echo '<form action="options.php" method="post">';

		settings_fields( $this->page['option_slug'] . '-group' );

		foreach ( $this->tabs as $tab ) {

			echo '<div id="npf-' . $tab['id'] . '" class="single-tab-content">';
			do_settings_sections( $tab['id'] . '-' . $this->page['menu_slug'] );
			echo '</div>';

		}

		submit_button( esc_html__( 'Save Changes' ) );

		echo '</form>';

		echo '</div>';
	}

	function register_settings() {
		register_setting( $this->page['option_slug'] . '-group', $this->page['option_slug'], array( $this, 'sanitize_callback' ) );

		// Load tabs.
		foreach ( $this->tabs as $tab ) {
			add_settings_section(
				$tab['id'] . '_settings' . '-' . $this->page['menu_slug'],
				$tab['title'],
				array( $this, 'section_text_callback' ),
				$tab['id'] . '-' . $this->page['menu_slug']
			);

			if ( isset( $this->fields[ $tab['id'] ] ) && ! empty( $this->fields[ $tab['id'] ] ) ) {
				foreach ( $this->fields[ $tab['id'] ] as $field_key => $field ) {
					$args = array(
						'field'       => $field,
						'field_id'    => $field['id'],
						'field_name'  => $this->page['option_slug'] . '[' . $field['id'] . ']',
						'field_value' => ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '',
						'options'     => $this->options,
					);
					// nspre( $field );
					add_settings_field(
						$field_key,
						$field['title'],
						array( $this, 'field_callback' ),
						$tab['id'] . '-' . $this->page['menu_slug'],
						$tab['id'] . '_settings' . '-' . $this->page['menu_slug'],
						$args
					);
				}
			}
		}

		return;

		nspre( $this->fields );

		foreach ( $this->fields as $field_key => $field ) {
			$args = array(
				'field'       => $field,
				'field_id'    => $field['id'],
				'field_name'  => $this->page['option_slug'] . '[' . $field['id'] . ']',
				'field_value' => ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '',
				'options'     => $this->options,
			);
			// nspre( $field );
			add_settings_field(
				$field_key,
				$field['title'],
				array( $this, 'field_callback' ),
				$tab['id'] . '-' . $this->page['menu_slug'],
				$tab['id'] . '_settings' . '-' . $this->page['menu_slug'],
				$args
			);
		}

		return;



		foreach ( $this->tabs as $tab ) {
			add_settings_section(
				$tab['id'] . '_settings' . '-' . $this->page['menu_slug'],
				$tab['title'],
				array( $this, 'section_text_callback' ),
				$tab['id'] . '-' . $this->page['menu_slug']
			);

			foreach ( $tab['fields'] as $field_key => $field ) {
				$args = array(
					'field'       => $field,
					'field_id'    => $field['id'],
					'field_name'  => $this->page['option_slug'] . '[' . $field['id'] . ']',
					'tab'         => $tab,
					// 'base_args'   => $this->base_args,
					'field_value' => ( isset( $this->options[ $field['id'] ] ) ) ? $this->options[ $field['id'] ] : '',
					'options'     => $this->options,
				);

				add_settings_field(
					$field_key,
					$field['title'],
					array( $this, 'field_callback' ),
					$tab['id'] . '-' . $this->page['menu_slug'],
					$tab['id'] . '_settings' . '-' . $this->page['menu_slug'],
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

	public function set_page( $args = array() ) {
		$defaults = array(
			'page_title'  => esc_html__( 'Optioner', 'optioner' ),
			'menu_title'  => esc_html__( 'Optioner', 'optioner' ),
			'capability'  => 'manage_options',
			'menu_slug'   => 'optioner',
			'option_slug' => 'optioner',
		);

		$this->page = wp_parse_args( $args, $defaults );
	}

	public function add_tab( $args ) {
		// Bail if not array.
		if ( ! is_array( $args ) ) {
			return false;
		}

		$defaults = array(
			'id'       => '',
			'title'    => '',
			'subtitle' => '',
		);

		$args = wp_parse_args( $args, $defaults );

		$this->tabs[ $args['id'] ] = $args;

		return $this;
	}

	public function add_field( $tab, $args ) {
		// Bail if not array.
		if ( ! is_array( $args ) ) {
			return false;
		}

		// Set the defaults.
		$defaults = array(
			'id'   => '',
			'name' => '',
			'desc' => '',
			'type' => 'text',
		);

		$arg = wp_parse_args( $args, $defaults );

		$this->fields[ $tab ][ $args['id'] ] = $args;

		return $this;
	}
}
