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

	/**
	 * Options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $options;

	/**
	 * Whether page is in top level menu.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $top_level_menu;

	/**
	 * Parent page.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $parent_page;

	/**
	 * Tabs.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $tabs = array();

	/**
	 * Tab status
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $tab_status = false;

	/**
	 * Fields.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $fields = array();

	/**
	 * Page settings.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	protected $page = array();

	/**
	 * Sidebar status.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	protected $is_sidebar = false;

	/**
	 * Sidebar width.
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	protected $sidebar_width;

	/**
	 * Sidebar callback.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	protected $sidebar_callback = null;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
	}

	/**
	 * Run now.
	 *
	 * @since 1.0.0
	 */
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

		if ( count( $this->tabs ) > 1 ) {
			$this->tab_status = true;
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

	/**
	 * Create menu page.
	 *
	 * @since 1.0.0
	 */
	public function create_menu_page() {
		if ( true === $this->top_level_menu ) {
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

	/**
	 * Render page.
	 *
	 * @since 1.0.0
	 */
	public function render_page() {
		echo '<div class="wrap optioner-wrap">';

		echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';

		$tab_status_class = ( true === $this->tab_status ) ? 'tab-enabled' : 'tab-disabled';

		echo '<div class="wrap-content ' . esc_attr( $tab_status_class ) . '">';

		echo '<div class="wrap-primary">';

		if ( true === $this->tab_status ) {
			$this->render_navigation();
		}

		$this->render_forms();

		echo '</div><!-- .wrap-primary -->';

		if ( true === $this->is_sidebar ) {
			$sidebar_styles = 'flex-basis:' . absint( $this->sidebar_width ) . '%;';

			echo '<div class="wrap-secondary" style="' . esc_attr( $sidebar_styles ) . '">';

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
	public function render_navigation() {
		$html = '<h2 class="nav-tab-wrapper">';

		foreach ( $this->tabs as $tab ) {
			$html .= sprintf( '<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title'] );
		}

		$html .= '</h2>';

		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Render forms.
	 *
	 * @since 1.0.0
	 */
	public function render_forms() {
		echo '<div class="optioner-form-holder">';
		echo '<form action="options.php" method="post">';

		settings_fields( $this->page['option_slug'] . '-group' );

		foreach ( $this->tabs as $tab ) {

			echo '<div id="' . esc_attr( $tab['id'] ) . '" class="tab-content">';

			if ( isset( $tab['render_callback'] ) && is_callable( $tab['render_callback'] ) ) {
				echo '<div class="tab-content-inner tab-content-inner-custom">';
				do_action( 'optioner_form_top_' . $tab['id'], $tab );
				call_user_func( $tab['render_callback'] );
				do_action( 'optioner_form_bottom_' . $tab['id'], $tab );
				echo '</div>';
			} else {
				echo '<div class="tab-content-inner tab-content-inner-fields">';
				do_action( 'optioner_form_top_' . $tab['id'], $tab );
				do_settings_sections( $tab['id'] . '-' . $this->page['menu_slug'] );
				do_action( 'optioner_form_bottom_' . $tab['id'], $tab );
				submit_button( esc_html__( 'Save Changes', 'optioner' ) );
				echo '</div>';
			}

			echo '</div>';
		}

		echo '</form>';
		echo '</div>';
	}

	/**
	 * Register field settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {
		register_setting( $this->page['option_slug'] . '-group', $this->page['option_slug'], array( $this, 'sanitize_fields' ) );

		// Load tabs.
		foreach ( $this->tabs as $tab ) {
			add_settings_section(
				$tab['id'] . '_settings-' . $this->page['menu_slug'],
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
						$tab['id'] . '_settings-' . $this->page['menu_slug'],
						$args
					);
				}
			}
		}
	}

	/**
	 * Sanitize fields.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Raw values.
	 * @return array Sanitized values.
	 */
	public function sanitize_fields( $input ) {
		$output = array();

		foreach ( $this->fields as $tab ) {

			foreach ( $tab as $field ) {
				if ( isset( $input[ $field['id'] ] ) ) {

					if ( isset( $field['sanitize_callback'] ) && is_callable( $field['sanitize_callback'] ) ) {
						// Custom sanitization.
						$output[ $field['id'] ] = call_user_func_array( $field['sanitize_callback'], array( $input[ $field['id'] ] ) );
					} else {
						// Default sanitization.
						switch ( strtolower( $field['type'] ) ) {
							case 'text':
							case 'select':
							case 'radio':
								$output[ $field['id'] ] = sanitize_text_field( $input[ $field['id'] ] );
								break;

							case 'url':
							case 'image':
								$output[ $field['id'] ] = esc_url_raw( $input[ $field['id'] ] );
								break;

							case 'email':
								$output[ $field['id'] ] = sanitize_email( $input[ $field['id'] ] );
								break;

							case 'number':
								$output[ $field['id'] ] = intval( $input[ $field['id'] ] );
								break;

							case 'textarea':
								$output[ $field['id'] ] = sanitize_textarea_field( $input[ $field['id'] ] );
								break;

							case 'editor':
								$output[ $field['id'] ] = wp_kses_post( $input[ $field['id'] ] );
								break;

							case 'checkbox':
								$output[ $field['id'] ] = $input[ $field['id'] ] ? true : false;
								break;

							case 'multicheck':
								$val = array();

								if ( is_array( $input[ $field['id'] ] ) && ! empty( $input[ $field['id'] ] ) ) {
									foreach ( $input[ $field['id'] ] as $v ) {
										$val[] = sanitize_text_field( $v );
									}
								}

								if ( ! empty( $val ) ) {
									$output[ $field['id'] ] = $val;
								}
								break;

							default:
								$output[ $field['id'] ] = sanitize_text_field( $input[ $field['id'] ] );
								break;
						}
					}
				} else {
					$output[ $field['id'] ] = null;
				}
			}
		}

		return $output;
	}

	/**
	 * Render field markup.
	 *
	 * @since 1.0.0
	 *
	 * @param string $html HTML markup.
	 * @param array  $args Arguments.
	 */
	public function render_field_markup( $html, $args ) {
		$html = sprintf( '<div class="form-field-%1$s form-field-%2$s">%3$s</div>', $args['field']['type'], $args['field']['id'], $html );

		do_action( 'optioner_field_top_' . $args['field']['type'], $args['field']['id'], $this->page['menu_slug'], $args );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		do_action( 'optioner_field_bottom_' . $args['field']['type'], $args['field']['id'], $this->page['menu_slug'], $args );
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

		$this->render_field_markup( $html, $args );
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
	 * Render password.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_password( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Render checkbox.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_checkbox( $args ) {
		$attr = array(
			'type'  => 'checkbox',
			'name'  => $args['field_name'],
			'value' => 1,
		);

		$attributes = $this->render_attr( $attr, false );

		$html = '';

		$html .= '<input type="hidden" name="' . esc_attr( $args['field_name'] ) . '" value="0" />';

		$html .= sprintf( '<input %s %s />', $attributes, checked( $this->get_value( $args ), 1, false ) );

		if ( isset( $args['field']['side_text'] ) && ! empty( $args['field']['side_text'] ) ) {
			$html .= esc_html( $args['field']['side_text'] );
		}

		$html .= $this->get_field_description( $args );

		$this->render_field_markup( $html, $args );
	}

	/**
	 * Render multicheck.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_multicheck( $args ) {
		$values = (array) $this->get_value( $args );

		$html = '';

		if ( ! empty( $args['field']['choices'] ) ) {
			$html .= '<ul>';

			foreach ( $args['field']['choices'] as $key => $value ) {
				$attr = array(
					'type'  => 'checkbox',
					'name'  => $args['field_name'] . '[]',
					'value' => $key,
				);

				$attributes = $this->render_attr( $attr, false );

				$html .= '<li>';

				$html .= sprintf( '<input %s %s />', $attributes, checked( in_array( (string) $key, $values, true ), true, false ) );

				$html .= $value;

				$html .= '</li>';
			}

			$html .= '</ul>';
		}

		$html .= $this->get_field_description( $args );

		$this->render_field_markup( $html, $args );
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

		$this->render_field_markup( $html, $args );
	}

	/**
	 * Render editor.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_editor( $args ) {
		$field_value = $this->get_value( $args );

		$editor_settings = array(
			'teeny'          => true,
			'textarea_name'  => $args['field_name'],
			'textarea_rows'  => 10,
			'default_editor' => 'tinymce',
		);

		if ( isset( $args['field']['settings'] ) && is_array( $args['field']['settings'] ) ) {
			$editor_settings = wp_parse_args( $args['field']['settings'], $editor_settings );
		}

		$size = isset( $args['field']['size'] ) && ! empty( $args['field']['size'] ) ? absint( $args['field']['size'] ) : 1024;

		ob_start();

		echo '<div style="max-width: ' . esc_attr( $size . 'px' ) . ';">';

		wp_editor( $field_value, $args['field_id'], $editor_settings );

		echo '</div>';

		$html = ob_get_clean();

		$html .= $this->get_field_description( $args );

		$this->render_field_markup( $html, $args );
	}

	/**
	 * Render image.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function callback_image( $args ) {
		$value = $this->get_value( $args );

		ob_start();
		?>
		<div class="field-image">
			<input type="button" class="select-img button button-primary" value="<?php esc_attr_e( 'Upload', 'optioner' ); ?>" data-uploader_title="<?php esc_attr_e( 'Select Image', 'optioner' ); ?>" data-uploader_button_text="<?php esc_attr_e( 'Choose Image', 'optioner' ); ?>" />
			<?php
			$image_status = false;
			if ( ! empty( $value ) ) {
				$image_status = true;
			}
			$remove_button_style = 'display:none;';
			if ( true === $image_status ) {
				$remove_button_style = 'display:inline-block;';
			}
			?>
			<input type="button" value="<?php echo esc_attr( _x( 'X', 'remove button', 'optioner' ) ); ?>" class="button button-secondary btn-image-remove" style="<?php echo esc_attr( $remove_button_style ); ?>" />
			<input type="hidden" class="img" name="<?php echo esc_attr( $args['field_name'] ); ?>" value="<?php echo esc_attr( $value ); ?>" />
			<div class="image-preview-wrap">
			<?php if ( ! empty( $value ) ) : ?>
				<img src="<?php echo esc_attr( $value ); ?>" alt="" />
			<?php endif; ?>
			</div><!-- .image-preview-wrap -->
		</div>

		<?php
		$html = ob_get_clean();

		$html .= $this->get_field_description( $args );

		$this->render_field_markup( $html, $args );
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

		$this->render_field_markup( $html, $args );
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

		$this->render_field_markup( $html, $args );
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
			'name' => $args['field_name'],
		);

		$attributes = $this->render_attr( $attr, false );

		$html = sprintf( '<select %s>', $attributes );

		if ( isset( $args['field']['allow_null'] ) && true === $args['field']['allow_null'] ) {
			$html .= '<option value="">&mdash; ' . esc_html__( 'Select', 'optioner' ) . ' &mdash;</option>';
		}

		if ( ! empty( $args['field']['choices'] ) ) {
			foreach ( $args['field']['choices'] as $key => $value ) {
				$html .= '<option value="' . esc_attr( $key ) . '"' . selected( $this->get_value( $args ), $key, false ) . '>' . esc_html( $value ) . '</option>';
			}
		}

		$html .= '</select>';

		$html .= $this->get_field_description( $args );

		$this->render_field_markup( $html, $args );
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

			foreach ( $args['field']['choices'] as $key => $value ) {
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

		$this->render_field_markup( $html, $args );
	}

	/**
	 * Return field description.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 * @return string Description markup.
	 */
	public function get_field_description( $args ) {
		$output = '';

		if ( isset( $args['field']['description'] ) && ! empty( $args['field']['description'] ) ) {
			$output = sprintf( '<p class="description">%s</p>', wp_kses_post( $args['field']['description'] ) );
		}

		return $output;
	}

	/**
	 * Return value.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 * @return mixed Value.
	 */
	private function get_value( $args ) {
		$output  = null;
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

	/**
	 * Render section text.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function section_text_callback( $args ) {
	}

	/**
	 * Set page settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
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

	/**
	 * Set sidebar.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Arguments.
	 */
	public function set_sidebar( $args ) {
		$this->is_sidebar = true;

		$defaults = array(
			'render_callback' => '',
			'width'           => 20,
		);

		$args = wp_parse_args( $args, $defaults );

		if ( absint( $args['width'] ) > 0 && absint( $args['width'] ) < 100 ) {
			$this->sidebar_width = absint( $args['width'] );
		} else {
			$this->sidebar_width = 20;
		}

		if ( is_callable( $args['render_callback'] ) ) {
			$this->sidebar_callback = $args['render_callback'];
		}
	}

	/**
	 * Add tab.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Tab arguments.
	 */
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
	}

	/**
	 * Add field.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tab  Tab id.
	 * @param array  $args Field arguments.
	 */
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
		wp_enqueue_script( 'wp-color-picker' );

		wp_enqueue_media();
	}

	/**
	 * Return require screen.
	 *
	 * @since 1.0.0
	 *
	 * @return string Screen slug.
	 */
	public function get_required_screen() {
		$output = '';

		$map_array = array(
			'index.php'               => 'dashboard',
			'edit.php'                => 'posts',
			'upload.php'              => 'media',
			'edit.php?post_type=page' => 'pages',
			'edit-comments.php'       => 'comments',
			'themes.php'              => 'appearance',
			'plugins.php'             => 'plugins',
			'users.php'               => 'users',
			'tools.php'               => 'tools',
			'options-general.php'     => 'settings',
		);

		if ( true === $this->top_level_menu ) {
			$output = 'toplevel';
		} else {
			if ( isset( $map_array[ $this->parent_page ] ) ) {
				$output = $map_array[ $this->parent_page ];
			} else {
				$t = strpos( $this->parent_page, 'edit.php?post_type=' );

				if ( false !== $t ) {
					$output = substr( $this->parent_page, strlen( 'edit.php?post_type=' ) );
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

			.tab-content .tab-content-inner > h2 {
				display: none;
			}

			.wrap-content {
				display: flex;
			}

			.wrap-content.tab-disabled {
				display: flex;
			}

			.wrap-primary {
				flex: 1;
			}

			.wrap-secondary {
				margin-left: 15px;
				margin-top: 43px;
			}

			.tab-disabled .wrap-secondary {
				margin-top: 20px;
			}

			.tab-content .tab-content-inner {
				background-color: #fff;
				box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
				border: 1px solid #ccd0d4;
				border-top: none;
			}

			.tab-disabled .tab-content-inner {
				margin-top: 20px;
				border-top: 1px solid #ccd0d4;
			}

			.tab-content-inner-fields {
				padding: 5px 20px 15px 20px;
			}

			.tab-content-inner-custom {
				padding: 20px;
			}

			.tab-content-inner-custom p:first-child {
				margin-top: 0;
			}
			.tab-content-inner-custom p:last-child {
				margin-bottom: 0;
			}

			.tab-content p.submit {
				padding-bottom: 0;
			}

			.nav-tab-active,
			.nav-tab-active:hover {
				background-color: #fff;
				border-bottom-color: #fff;
			}

			.sidebox {
				position: relative;
				min-width: 200px;
				border: 1px solid #ccd0d4;
				box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
				background: #fff;
				padding: 15px;
				margin-bottom: 15px;
			}

			.sidebox .box-heading {
				font-size: 1.2em;
				margin-top: 0;
			}

			.sidebox .box-content ul,
			.sidebox .box-content ol,
			.sidebox .box-content p {
				margin-top: 0;
			}

			.sidebox .box-content p:last-child {
				margin-bottom: 0;
			}

			.form-field-heading {
				margin-left: -10px;
			}

			.form-field-heading h2 {
				margin-top: 0;
			}

			.form-field-heading .description {
				font-size: 13px;
				font-style: inherit;
				color: #444;
			}

			.form-field-radio .layout-horizontal {
				display: flex;
			}

			.form-field-radio .radio-list {
				margin: 0;
				padding: 0;
			}

			.form-field-radio .radio-list li {
				margin-right: 10px;
			}

			.form-field-image .image-preview-wrap {
				margin-top: 10px;
				max-width: 200px;
			}

			.form-field-image .image-preview-wrap img {
				width: 100%;
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
			var custom_theme_file_frame;

			jQuery( document ).ready( function( $ ) {
				//Initiate Color Picker.
				$('.optioner-color').each(function(){
					$(this).wpColorPicker();
				});

				// Heading fix.
				$('.form-field-heading').each(function(i, el){
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

				// Uploads.
				jQuery(document).on('click', 'input.select-img', function( event ){

					var $this = $(this);

					event.preventDefault();

					var CustomThemeImage = wp.media.controller.Library.extend({
						defaults :  _.defaults({
							id:        'custom-theme-insert-image',
							title:      $this.data( 'uploader_title' ),
							allowLocalEdits: false,
							displaySettings: true,
							displayUserSettings: false,
							multiple : false,
							library: wp.media.query( { type: 'image' } )
						}, wp.media.controller.Library.prototype.defaults )
					});

					// Create the media frame.
					custom_theme_file_frame = wp.media.frames.custom_theme_file_frame = wp.media({
						button: {
							text: jQuery( this ).data( 'uploader_button_text' )
						},
						state : 'custom-theme-insert-image',
						states : [
						new CustomThemeImage()
						],
						multiple: false
					});

					// When an image is selected, run a callback.
					custom_theme_file_frame.on( 'select', function() {
						var state = custom_theme_file_frame.state('custom-theme-insert-image');
						var selection = state.get('selection');
						var display = state.display( selection.first() ).toJSON();
						var obj_attachment = selection.first().toJSON();
						display = wp.media.string.props( display, obj_attachment );

						var image_field = $this.siblings('.img');
						var imgurl = display.src;

						// Copy image URL.
						image_field.val(imgurl);
						image_field.trigger('change');
						// Show in preview.
						var image_preview_wrap = $this.siblings('.image-preview-wrap');
						var image_html = '<img src="' + imgurl+ '" alt="" style="max-width:100%;max-height:200px;" />';
						image_preview_wrap.html( image_html );
						// Show Remove button.
						var image_remove_button = $this.siblings('.btn-image-remove');
						image_remove_button.css('display','inline-block');
					});

					// Finally, open the modal.
					custom_theme_file_frame.open();
				});

				// Remove image.
				jQuery(document).on('click', 'input.btn-image-remove', function( e ) {

					e.preventDefault();
					var $this = $(this);
					var image_field = $this.siblings('.img');
					image_field.val('');
					var image_preview_wrap = $this.siblings('.image-preview-wrap');
					image_preview_wrap.html('');
					$this.css('display','none');
					image_field.trigger('change');

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
	public function get_underscored_string( $title ) {
		return str_replace( '-', '_', sanitize_title_with_dashes( $title ) );
	}

	/**
	 * Return settings page URL.
	 *
	 * @since 1.0.0
	 *
	 * @return string URL.
	 */
	public function get_page_url() {
		$parent = $this->parent_page;

		if ( true === $this->top_level_menu ) {
			$parent = 'admin.php';
		}

		$base_url = admin_url( $parent );

		$output = add_query_arg(
			array(
				'page' => $this->page['menu_slug'],
			),
			$base_url
		);

		return $output;
	}
}

