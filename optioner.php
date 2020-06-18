<?php
/**
 * Plugin Name: Optioner
 * Description: Minimal option framework.
 * Version: 1.0.0
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

function optioner_render_features() {
	echo 'I am callback for features tab.';
}

$obj = new Optioner();

$obj->set_page();

$obj->add_tab(
	array(
		'id'    => 'basic_tab',
		'title' => esc_html__( 'First Tab', 'optioner' ),
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
		'id'                  => 'sample_text',
		'type'                => 'text',
		'title'               => esc_html__( 'Sample Text', 'optioner' ),
		'description'         => esc_html__( 'Description for sample text', 'optioner' ),
		'placeholder'         => esc_html__( 'Enter text', 'optioner' ),
		'sanitize_text_field' => 'esc_url_raw',
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
		'title'       => esc_html__( 'Text Regular', 'optioner' ),
		'description' => esc_html__( 'Description of text regular.', 'optioner' ),
		'class'       => 'large-text',
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


// Tab: features_tab.
$obj->add_tab(
	array(
		'id'              => 'features_tab',
		'title'           => esc_html__( 'Features', 'optioner' ),
		'render_callback' => __NAMESPACE__ . '\optioner_render_features',
	)
);

$obj->run();
