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

		// Register admin assets.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_head', array( $this, 'admin_style' ) );
		add_action( 'admin_footer', array( $this, 'footer_scripts' ) );
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

		$this->render_navigation();

		$this->render_forms();

		echo '</div>';
	}

	/**
	 * Render navigation.
	 *
	 * @since 1.0.0
	 */
	function render_navigation() {
		$html = '<h2 class="nav-tab-wrapper">';

		foreach ( $this->tabs as $tab ) {
			$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
		}

		$html .= '</h2>';

		echo $html;
	}

	/**
	 * Render forms.
	 *
	 * @since 1.0.0
	 */
	function render_forms() {
		echo '<div class="optioner-form-holder">';
		echo '<form action="options.php" method="post">';

		settings_fields( $this->page['option_slug'] . '-group' );

		foreach ( $this->tabs as $tab ) {

			echo '<div id="' . $tab['id'] . '" class="tab-content">';
			do_settings_sections( $tab['id'] . '-' . $this->page['menu_slug'] );
			echo '</div>';

		}

		submit_button( esc_html__( 'Save Changes', 'optioner' ) );

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
					);

					add_settings_field(
						$field_key,
						$field['title'],
						array( $this, 'callback_' . $field['type'] ),
						$tab['id'] . '-' . $this->page['menu_slug'],
						$tab['id'] . '_settings' . '-' . $this->page['menu_slug'],
						$args
					);
				}
			}
		}
	}

	/**
	 * Render text.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_text( $args ) {
		$attr = array(
			'type'  => 'text',
			'name'  => $args['field_name'],
			'value' => $this->get_value( $args ),
			'class' => isset( $args['field']['class'] ) ? $args['field']['class'] : 'regular-text',
		);

		if ( isset( $args['field']['placeholder'] ) ) {
			$attr['placeholder'] = $args['field']['placeholder'];
		}

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<input %s />', $attributes );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render textarea.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_textarea( $args ) {
		$attr = array(
			'name'  => $args['field_name'],
			'class' => isset( $args['field']['class'] ) ? $args['field']['class'] : 'regular-text',
			'rows'  => isset( $args['field']['rows'] ) ? $args['field']['rows'] : 5,
		);

		if ( isset( $args['field']['placeholder'] ) ) {
			$attr['placeholder'] = $args['field']['placeholder'];
		}

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<textarea %s>%s</textarea>', $attributes, esc_textarea( $this->get_value( $args ) ) );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render text.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_color( $args ) {
		$attr = array(
			'type'  => 'text',
			'name'  => $args['field_name'],
			'value' => $this->get_value( $args ),
			'class' => isset( $args['field']['class'] ) ? $args['field']['class'] : 'regular-text',
		);

		$attr['class'] = ' code optioner-color';

		if ( isset( $args['field']['placeholder'] ) ) {
			$attr['placeholder'] = $args['field']['placeholder'];
		}

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<input %s />', $attributes );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	private function get_value( $args ) {
		$output = null;

		$default = null;

		if ( isset( $args['field']['default'] ) ) {
			$default = $args['field']['default'];
		}

		if ( isset( $this->options[ $args['field_id'] ] ) ) {
			$output = $this->options[ $args['field_id'] ];
		} else {
			$output = $default;
		}

		return $output;

	}

	function section_text_callback( $args ) {
		$exp = explode( '_settings', $args['id'] );

		$current_tab = array_shift( $exp );

		if ( isset( $this->tabs[ $current_tab ]['subtitle'] ) && ! empty( $this->tabs[ $current_tab ]['subtitle'] ) ) {
			echo '<div class="optioner-subheading">' . esc_html( $this->tabs[ $current_tab ]['subtitle'] ) . '</div>';
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

	/**
	 * Render attributes.
	 *
	 * @since 1.0.0
	 *
	 * @param array $attributes Attributes.
	 * @param bool  $echo Whether to echo or not.
	 */
	private function render_attr( $attributes, $echo = true ) {
		if ( empty( $attributes ) ) {
			return;
		}

		$html = '';

		foreach ( $attributes as $name => $value ) {

			$esc_value = '';

			if ( 'class' === $name && is_array( $value ) ) {
				$value = join( ' ', array_unique( $value ) );
			}

			if ( false !== $value && 'href' === $name ) {
				$esc_value = esc_url( $value );

			} elseif ( false !== $value ) {
				$esc_value = esc_attr( $value );
			}

			$html .= false !== $value ? sprintf( ' %s="%s"', esc_html( $name ), $esc_value ) : esc_html( " {$name}" );
		}

		if ( ! empty( $html ) && true === $echo ) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $html;
		}
	}

	/**
	 * Admin Scripts.
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'jquery' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');

		wp_enqueue_media();
	}

	/**
	 * Admin style.
	 *
	 * @since 1.0.0
	 */
	public function admin_style() {
		?>
		<style>
			.tab-content {
				display: none;
			}
		</style>
		<?php
	}

	/**
	 * Footer Scripts.
	 *
	 * @since 1.0.0
	 */
	public function footer_scripts() {
		?>
		<script>
			jQuery( document ).ready( function( $ ) {
				// Switches tabs.
				$( '.tab-content' ).hide();

				var activetab = '';

				if ( 'undefined' != typeof localStorage ) {
					activetab = localStorage.getItem( 'activetab' );
				}

				if ( '' != activetab && $( activetab ).length ) {
					$( activetab ).fadeIn();
				} else {
					$( '.tab-content:first' ).fadeIn();
				}

				// Tab links.
				if ( '' != activetab && $( activetab + '-tab' ).length ) {
					$( activetab + '-tab' ).addClass( 'nav-tab-active' );
				} else {
					$( '.nav-tab-wrapper a:first' ).addClass( 'nav-tab-active' );
				}

				// Tab switcher.
				$( '.nav-tab-wrapper a' ).click( function( evt ) {
					$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
					$( this )
						.addClass( 'nav-tab-active' )
						.blur();
					var clicked_group = $( this ).attr( 'href' );
					if ( 'undefined' != typeof localStorage ) {
						localStorage.setItem( 'activetab', $( this ).attr( 'href' ) );
					}
					$( '.tab-content' ).hide();
					$( clicked_group ).fadeIn();
					evt.preventDefault();
				});




				//Initiate Color Picker.
				$('.optioner-color').each(function(){
				    $(this).wpColorPicker();
				});


			});

		</script>
		<?php
	}

}

