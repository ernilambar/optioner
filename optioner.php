<?php
/**
 * Plugin Name: Optioner
 * Description: Minimal option framework.
 * Version: 1.0.9
 * Author: Nilambar Sharma
 * Author URI: https://www.nilambar.net
 * Text Domain: optioner
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package Optioner
 */

namespace Nilambar\Optioner;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'OPTIONER_BASENAME', basename( dirname( __FILE__ ) ) );
define( 'OPTIONER_DIR', rtrim( plugin_dir_path( __FILE__ ), '/' ) );
define( 'OPTIONER_URL', rtrim( plugin_dir_url( __FILE__ ), '/' ) );

require_once OPTIONER_DIR . '/src/Optioner.php';

$obj = new Optioner();

$obj->set_page();

$obj->add_tab(
	array(
		'id'    => 'basic_tab',
		'title' => esc_html__( 'Basic', 'optioner' ),
	)
);

// Field: sample_heading.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_heading',
		'type'        => 'heading',
		'title'       => esc_html__( 'Sample Heading', 'optioner' ),
		'description' => esc_html__( 'This is description.', 'optioner' ),
	)
);

// Field: sample_text.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_text',
		'type'        => 'text',
		'title'       => esc_html__( 'Sample Text', 'optioner' ),
		'description' => esc_html__( 'Description for sample text.', 'optioner' ),
		'placeholder' => esc_html__( 'Enter text', 'optioner' ),
	)
);

// Field: sample_checkbox.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_checkbox',
		'type'        => 'checkbox',
		'default'     => true,
		'title'       => esc_html__( 'Sample Checkbox', 'optioner' ),
		'side_text'   => esc_html__( 'Enable sample checkbox', 'optioner' ),
		'description' => esc_html__( 'Description for sample checkbox field.', 'optioner' ),
	)
);

// Field: sample_select.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_select',
		'type'        => 'select',
		'title'       => esc_html__( 'Sample Select', 'optioner' ),
		'description' => esc_html__( 'Description of sample select.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: sample_radio.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_radio',
		'type'        => 'radio',
		'title'       => esc_html__( 'Sample Radio', 'optioner' ),
		'description' => esc_html__( 'Description of sample radio.', 'optioner' ),
		'default'     => '1',
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
		),
	)
);

// Field: sample_image.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_image',
		'type'        => 'image',
		'title'       => esc_html__( 'Sample Image', 'optioner' ),
		'description' => esc_html__( 'Description for sample image.', 'optioner' ),
	)
);

// Field: sample_color.
$obj->add_field(
	'basic_tab',
	array(
		'id'          => 'sample_color',
		'type'        => 'color',
		'title'       => esc_html__( 'Sample Color', 'optioner' ),
		'description' => esc_html__( 'Description of sample color.', 'optioner' ),
		'default'     => '#8224e3',
	)
);

$obj->add_tab(
	array(
		'id'    => 'text_tab',
		'title' => esc_html__( 'Text', 'optioner' ),
	)
);

// Field: text_regular.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'text_regular',
		'type'        => 'text',
		'title'       => esc_html__( 'Text Regular', 'optioner' ),
		'description' => esc_html__( 'Description of text regular.', 'optioner' ),
	)
);

// Field: text_small.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'text_small',
		'type'        => 'text',
		'title'       => esc_html__( 'Text Small', 'optioner' ),
		'description' => esc_html__( 'Description of text small.', 'optioner' ),
		'class'       => 'small-text',
	)
);

// Field: text_tiny.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'text_tiny',
		'type'        => 'text',
		'title'       => esc_html__( 'Text Tiny', 'optioner' ),
		'description' => esc_html__( 'Description of text tiny.', 'optioner' ),
		'class'       => 'tiny-text',
	)
);

// Field: text_large.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'text_large',
		'type'        => 'text',
		'title'       => esc_html__( 'Text Large', 'optioner' ),
		'description' => esc_html__( 'Description of text large.', 'optioner' ),
		'class'       => 'large-text',
	)
);

// Field: text_password.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'text_password',
		'type'        => 'password',
		'title'       => esc_html__( 'Text Password', 'optioner' ),
		'description' => esc_html__( 'Description of text password.', 'optioner' ),
	)
);

// Field: sample_url.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'sample_url',
		'type'        => 'url',
		'title'       => esc_html__( 'Sample URL', 'optioner' ),
		'description' => esc_html__( 'Description of sample URL.', 'optioner' ),
		'placeholder' => esc_html__( 'Enter full URL.', 'optioner' ),
	)
);

// Field: sample_number.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'sample_number',
		'type'        => 'number',
		'title'       => esc_html__( 'Sample Number', 'optioner' ),
		'description' => esc_html__( 'Description of sample number.', 'optioner' ),
	)
);

// Field: sample_email.
$obj->add_field(
	'text_tab',
	array(
		'id'          => 'sample_email',
		'type'        => 'email',
		'title'       => esc_html__( 'Sample Email', 'optioner' ),
		'description' => esc_html__( 'Description of sample email.', 'optioner' ),
	)
);

// Tab: textarea_tab.
$obj->add_tab(
	array(
		'id'    => 'textarea_tab',
		'title' => esc_html__( 'Textarea', 'optioner' ),
	)
);

// Field: textarea_regular.
$obj->add_field(
	'textarea_tab',
	array(
		'id'          => 'textarea_regular',
		'type'        => 'textarea',
		'title'       => esc_html__( 'Textarea Regular', 'optioner' ),
		'description' => esc_html__( 'This is regular textarea.', 'optioner' ),
		'placeholder' => esc_html__( 'Enter content.', 'optioner' ),
	)
);

// Field: textarea_large.
$obj->add_field(
	'textarea_tab',
	array(
		'id'          => 'textarea_large',
		'type'        => 'textarea',
		'title'       => esc_html__( 'Textarea Large', 'optioner' ),
		'description' => esc_html__( 'This is large textarea.', 'optioner' ),
		'placeholder' => esc_html__( 'Enter content.', 'optioner' ),
		'class'       => 'large-text',
	)
);

// Tab: checkbox_tab.
$obj->add_tab(
	array(
		'id'    => 'checkbox_tab',
		'title' => esc_html__( 'Checkbox', 'optioner' ),
	)
);

// Field: checkbox_single.
$obj->add_field(
	'checkbox_tab',
	array(
		'id'          => 'checkbox_single',
		'type'        => 'checkbox',
		'default'     => true,
		'title'       => esc_html__( 'Checkbox Simple', 'optioner' ),
		'side_text'   => esc_html__( 'Enable simple checkbox', 'optioner' ),
		'description' => esc_html__( 'Description for simple checkbox field.', 'optioner' ),
	)
);

// Field: checkbox_multi.
$obj->add_field(
	'checkbox_tab',
	array(
		'id'          => 'checkbox_multi',
		'type'        => 'multicheck',
		'title'       => esc_html__( 'Checkbox Multiple', 'optioner' ),
		'description' => esc_html__( 'Description for checkbox multiple.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
			'4' => esc_html__( 'Fourth', 'optioner' ),
		),
	)
);

// Tab: selection_tab.
$obj->add_tab(
	array(
		'id'    => 'selection_tab',
		'title' => esc_html__( 'Selection', 'optioner' ),
	)
);

// Field: select_simple.
$obj->add_field(
	'selection_tab',
	array(
		'id'          => 'select_simple',
		'type'        => 'select',
		'title'       => esc_html__( 'Select Simple', 'optioner' ),
		'description' => esc_html__( 'Description of select simple.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: select_null_allowed.
$obj->add_field(
	'selection_tab',
	array(
		'id'          => 'select_null_allowed',
		'type'        => 'select',
		'title'       => esc_html__( 'Select Null Allowed', 'optioner' ),
		'description' => esc_html__( 'Description of select null allowed.', 'optioner' ),
		'allow_null'  => true,
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: radio_horizontal.
$obj->add_field(
	'selection_tab',
	array(
		'id'          => 'radio_horizontal',
		'type'        => 'radio',
		'layout'      => 'horizontal',
		'title'       => esc_html__( 'Radio Horizontal', 'optioner' ),
		'description' => esc_html__( 'Description of radio horizontal.', 'optioner' ),
		'default'     => '1',
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: radio_vertical.
$obj->add_field(
	'selection_tab',
	array(
		'id'          => 'radio_vertical',
		'type'        => 'radio',
		'title'       => esc_html__( 'Radio Vertical', 'optioner' ),
		'description' => esc_html__( 'Description of radio vertical.', 'optioner' ),
		'default'     => '1',
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Tab: editor_tab.
$obj->add_tab(
	array(
		'id'    => 'editor_tab',
		'title' => esc_html__( 'Editor', 'optioner' ),
	)
);

// Field: editor_visual_only.
$obj->add_field(
	'editor_tab',
	array(
		'id'          => 'editor_visual_only',
		'type'        => 'editor',
		'title'       => esc_html__( 'Editor Visual Mode Only', 'optioner' ),
		'description' => esc_html__( 'Description for editor visual mode only.', 'optioner' ),
		'size'        => 460, // Max width, in px.
		'settings'    => array(
			'textarea_rows' => 5,
			'media_buttons' => false,
			'quicktags'     => false,
		),
	)
);

// Field: editor_text_only.
$obj->add_field(
	'editor_tab',
	array(
		'id'          => 'editor_text_only',
		'type'        => 'editor',
		'title'       => esc_html__( 'Editor Text Mode Only', 'optioner' ),
		'description' => esc_html__( 'Description for editor text mode only.', 'optioner' ),
		'size'        => 460, // Max width, in px.
		'settings'    => array(
			'textarea_rows' => 5,
			'media_buttons' => false,
			'tinymce'       => false,
		),
	)
);

// Field: editor_small.
$obj->add_field(
	'editor_tab',
	array(
		'id'          => 'editor_small',
		'type'        => 'editor',
		'title'       => esc_html__( 'Editor Small', 'optioner' ),
		'description' => esc_html__( 'Description for editor small.', 'optioner' ),
		'size'        => 460, // Max width, in px.
		'settings'    => array(
			'textarea_rows' => 5,
			'media_buttons' => false,
		),
	)
);

// Field: editor_large.
$obj->add_field(
	'editor_tab',
	array(
		'id'          => 'editor_large',
		'type'        => 'editor',
		'title'       => esc_html__( 'Editor Large', 'optioner' ),
		'description' => esc_html__( 'Description for editor large.', 'optioner' ),
	)
);

// Tab: features_tab.
$obj->add_tab(
	array(
		'id'              => 'features_tab',
		'title'           => esc_html__( 'Features', 'optioner' ),
		'render_callback' => __NAMESPACE__ . '\optioner_render_features_tab',
	)
);

// Set sidebar.
$obj->set_sidebar(
	array(
		'render_callback' => __NAMESPACE__ . '\optioner_render_sidebar',
	)
);

// Render now.
$obj->run();

/**
 * Render features tab.
 *
 * @since 1.0.0
 */
function optioner_render_features_tab() {
	?>
	<p>This is a demonstration of custom tab. You can add anything here. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eos quia eveniet fugiat nihil voluptatum, laborum alias reprehenderit, modi dicta repudiandae, officia repellat fuga cum optio sit culpa nemo quibusdam. Perspiciatis.</p>
	<p>Ipsum dolor sit amet, consectetur adipisicing elit. Eos quia eveniet fugiat nihil voluptatum, laborum alias reprehenderit, modi dicta repudiandae, officia repellat fuga cum optio sit culpa nemo quibusdam. Perspiciatis.</p>
	<?php
}

/**
 * Render features tab.
 *
 * @since 1.0.0
 */
function optioner_render_sidebar() {
	?>
	<div class="sidebox">
		<h3 class="box-heading">Help &amp; Support</h3>
		<div class="box-content">
			<ul>
				<li><strong>Questions, bugs, or great ideas?</strong></li>
				<li><a href="https://github.com/ernilambar/optioner/issues" target="_blank">Create issue in the repo</a></li>
			</ul>
		</div>
	</div>
	<div class="sidebox">
		<h3 class="box-heading">Sample Links</h3>
		<div class="box-content">
			<p>Lorem ipsum dolor sit amet, conse ctetur adipiscing elit.</p>
			<ul>
				<li><strong>Important links</strong></li>
				<li><a href="#">Sample Link One</a></li>
				<li><a href="#">Sample Link Two</a></li>
				<li><a href="#">Sample Link Three</a></li>
				<li><a href="#">Sample Link Four</a></li>
				<li><a href="#">Sample Link Five</a></li>
			</ul>
		</div>
	</div>
	<?php
}
