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

	var $is_sidebar = false;

	var $sidebar_callback = null;

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
		echo '<div class="wrap optioner-wrap">';

		echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';

		echo '<div class="wrap-content">';

		echo '<div class="wrap-primary">';

		$this->render_navigation();

		$this->render_forms();

		echo '</div><!-- .wrap-primary -->';

		if ( true === $this->is_sidebar ) {
			echo '<div class="wrap-secondary">';

			if ( is_callable( $this->sidebar_callback ) ) {
				call_user_func( $this->sidebar_callback );
			}

			echo '</div><!-- .wrap-secondary -->';
		}


		echo '</div><!-- .wrap-content -->';

		echo '</div><!-- .wrap -->';
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

			do_action( 'optioner_form_top_' . $tab['id'], $tab );
			do_settings_sections( $tab['id'] . '-' . $this->page['menu_slug'] );
			do_action( 'optioner_form_bottom_' . $tab['id'], $tab );

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
			'type'  => $args['field']['type'],
			'name'  => $args['field_name'],
			'value' => $this->get_value( $args ),
			'class' => isset( $args['field']['class'] ) ? $args['field']['class'] : 'regular-text',
		);

		if ( isset( $args['field']['placeholder'] ) ) {
			$attr['placeholder'] = $args['field']['placeholder'];
		}

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<input %s />', $attributes );

		$html .= $this->get_field_description( $args );

		$html = sprintf( '<div class="field-text">%s</div>', $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render URL.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_url( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Render number.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_number( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Render email.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_email( $args ) {
		$this->callback_text( $args );
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

		$html .= $this->get_field_description( $args );

		$html = sprintf( '<div class="field-textarea">%s</div>', $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render color.
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

		$html .= $this->get_field_description( $args );

		$html = sprintf( '<div class="field-color">%s</div>', $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render heading.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_heading( $args ) {
		$attr = array(
			'class' => isset( $args['field']['class'] ) ? $args['field']['class'] : 'optioner-heading',
		);

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<h2 %s>%s</h2>', $attributes, esc_html( $args['field']['title'] ) );

		$html .= $this->get_field_description( $args );

		$html = sprintf( '<div class="field-heading">%s</div>', $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	/**
	 * Render select.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_select( $args ) {
		$attr = array(
			'name'  => $args['field_name'],
		);

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<select %s>', $attributes );

		if ( isset( $args['field']['allow_null'] ) && true === $args['field']['allow_null'] ) {
			$html .= '<option value="">&mdash; ' . esc_html__( 'Select', 'optioner' ) . ' &mdash;</option>';
		}

		if ( ! empty( $args['field']['choices'] ) ) {
			foreach ($args['field']['choices'] as $key => $value ) {
				$html .= '<option value="' . esc_attr( $key ) . '"' . selected( $this->get_value( $args ), $key, false ) . '>' . esc_html( $value ) .'</option>';
			}
		}

		$html .= '</select>';

		$html .= $this->get_field_description( $args );

		$html = sprintf( '<div class="field-select">%s</div>', $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render radio.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_radio( $args ) {
		$html = '';

		if ( ! empty( $args['field']['choices'] ) ) {
			$layout_class = 'layout-vertical';

			if ( isset( $args['field']['layout'] ) && ! empty( $args['field']['layout'] ) ) {
				$layout_class = 'layout-' . $args['field']['layout'];
			}

			$html .= '<ul class="radio-list ' . esc_attr( $layout_class ) . '">';

			foreach ($args['field']['choices'] as $key => $value ) {
				$attr = array(
					'type'  => 'radio',
					'name'  => $args['field_name'],
					'value' => $key,
				);

				$attributes = $this->render_attr( $attr, false );

				$html .= '<li>';

				$html .= sprintf( '<label><input %s %s />%s</label>', $attributes, checked( $this->get_value( $args ), $key, false ), $value );

				$html .= '</li>';
			}

			$html .= '</ul>';
		}

		$html .= $this->get_field_description( $args );

		$html = sprintf( '<div class="field-radio">%s</div>', $html );

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}


	function get_field_description( $args ) {
		$output = '';

		if ( isset( $args['field']['description'] ) && ! empty( $args['field']['description'] ) ) {
			$output = sprintf( '<p class="description">%s</p>', wp_kses_post( $args['field']['description'] ) );
		}

		return $output;
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

	public function set_sidebar( $cb ) {
		$this->is_sidebar = true;

		$this->sidebar_callback = $cb;
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
		$screen = get_current_screen();

		$required_screen = $this->get_required_screen();

		if ( $required_screen !== $screen->id ) {
			return;
		}

		wp_enqueue_script( 'jquery' );

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker');

		wp_enqueue_media();
	}

	function get_required_screen() {
		$output = '';
		$map_array = array(
			'index.php'           => 'dashboard',
			'edit.php'            => 'posts',
			'upload.php'          => 'media',
			'edit.php?post_type=page'  => 'pages',
			'edit-comments.php'   => 'comments',
			'themes.php'          => 'appearance',
			'plugins.php'         => 'plugins',
			'users.php'           => 'users',
			'tools.php'           => 'tools',
			'options-general.php' => 'settings',
			);
		if ( true == $this->top_level_menu ) {
			$output = 'toplevel';
		} else{
			if (isset($map_array[$this->parent_page])) {
				$output = $map_array[$this->parent_page];
			}
			else{
				$t= strpos($this->parent_page, 'edit.php?post_type=');
				if ( false !== $t ) {
					$output = substr($this->parent_page, strlen('edit.php?post_type=') );
				}

			}
		}

		$output .= '_page_';
		$output .= $this->page['menu_slug'];

		return $output;
	}


	/**
	 * Admin style.
	 *
	 * @since 1.0.0
	 */
	public function admin_style() {
		$screen = get_current_screen();

		$required_screen = $this->get_required_screen();

		if ( $required_screen !== $screen->id ) {
			return;
		}
		?>
		<style>
			.tab-content {
				display: none;
			}

			.tab-content > h2 {
				display: none;
			}

			.wrap-content {
				display: flex;
			}

			.wrap-primary {
				flex: 1;
			}

			.wrap-secondary {
				flex-basis: 20%;
				margin-left: 30px;
				background-color: #fff;
				padding: 20px;
				margin-top: 40px;
			}

			.field-heading {
				margin-left: -10px;
			}

			.field-heading h2 {
				margin-top: 0;
			}

			.field-heading .description {
				font-size: 13px;
				font-style: inherit;
				color: #444;
			}

			.field-radio .layout-horizontal {
				display: flex;
			}

			.field-radio .radio-list {
				margin: 0;
				padding: 0;
			}

			.field-radio .radio-list li {
				margin-right: 10px;
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
		$screen = get_current_screen();

		$required_screen = $this->get_required_screen();

		if ( $required_screen !== $screen->id ) {
			return;
		}

		$slug = $this->get_underscored_string( $this->page['menu_slug'] );

		$storage_key = $slug . '_activetab';
		?>
		<script>
			jQuery( document ).ready( function( $ ) {
				//Initiate Color Picker.
				$('.optioner-color').each(function(){
				    $(this).wpColorPicker();
				});

				// Heading fix.
				$('.field-heading').each(function(i, el){
					// console.log( el );
					$el = $(el);

					$tr = $el.parent().parent();

					$tr.find('th').hide();

					$tr.find('td').attr('colspan',2);


				});

				// Switches tabs.
				$( '.tab-content' ).hide();

				var activetab = '';

				if ( 'undefined' != typeof localStorage ) {
					activetab = localStorage.getItem( '<?php echo esc_attr( $storage_key ); ?>' );
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
					$( this ).addClass( 'nav-tab-active' ).blur();

					var clicked_group = $( this ).attr( 'href' );
					if ( 'undefined' != typeof localStorage ) {
						localStorage.setItem( '<?php echo esc_attr( $storage_key ); ?>', $( this ).attr( 'href' ) );
					}
					$( '.tab-content' ).hide();
					$( clicked_group ).fadeIn();
					evt.preventDefault();
				});

			});

		</script>
		<?php
	}

	/**
	 * Get string with underscore.
	 *
	 * @since 1.0.0
	 *
	 * @param array $title Title.
	 * @return array Modified title.
	 */
	function get_underscored_string( $title ) {
		return str_replace( '-', '_', sanitize_title_with_dashes( $title ) );
	}
}

