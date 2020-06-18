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

$obj = new Optioner();

$obj->set_page();

$obj->add_tab(
	array(
		'id'    => 'first_tab',
		'title' => esc_html__( 'First Tab', 'optioner' ),
	)
);

// Field: basic_heading.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'basic_heading',
		'type'        => 'heading',
		'title'       => esc_html__( 'Basic Section', 'optioner' ),
		'description' => esc_html__( 'This is description of basic section.', 'optioner' ),
	)
);

// Field: sample_text.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_text',
		'type'        => 'text',
		'title'       => esc_html__( 'Sample Text', 'optioner' ),
		'description' => esc_html__( 'Description for sample text', 'optioner' ),
		'placeholder' => esc_html__( 'Enter text', 'optioner' ),
	)
);

// Field: sample_checkbox.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_checkbox',
		'type'        => 'checkbox',
		'default'     => true,
		'title'       => esc_html__( 'Sample Checkbox', 'optioner' ),
		'side_text'   => esc_html__( 'Enable sample checkbox', 'optioner' ),
		'description' => esc_html__( 'Description for sample checkbox field.', 'optioner' ),
	)
);

// Field: sample_checkboxes.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_checkboxes',
		'type'        => 'checkboxes',
		'title'       => esc_html__( 'Sample Checkboxes', 'optioner' ),
		'description' => esc_html__( 'Description of sample checkboxes.', 'optioner' ),
		'allow_null'  => true,
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: sample_select.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_select',
		'type'        => 'select',
		'title'       => esc_html__( 'Sample Select', 'optioner' ),
		'description' => esc_html__( 'Description of sample select.', 'optioner' ),
		'allow_null'  => true,
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: sample_select_no_null.
$obj->add_field(
	'first_tab',
	array(
		'id'      => 'sample_select_no_null',
		'type'    => 'select',
		'title'   => esc_html__( 'Sample Select No Null', 'optioner' ),
		'choices' => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: radio_horizontal.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'radio_horizontal',
		'type'        => 'radio',
		'default'     => '1',
		'layout'      => 'horizontal',
		'title'       => esc_html__( 'Radio Horizontal', 'optioner' ),
		'description' => esc_html__( 'Description of radio horizontal.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: radio_vertical.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'radio_vertical',
		'type'        => 'radio',
		'title'       => esc_html__( 'Radio Vertical', 'optioner' ),
		'description' => esc_html__( 'Description of radio vertical.', 'optioner' ),
		'choices'     => array(
			'1' => esc_html__( 'First', 'optioner' ),
			'2' => esc_html__( 'Second', 'optioner' ),
			'3' => esc_html__( 'Third', 'optioner' ),
		),
	)
);

// Field: sample_color.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_color',
		'type'        => 'color',
		'title'       => esc_html__( 'Sample Color', 'optioner' ),
		'description' => esc_html__( 'Description of sample color.', 'optioner' ),
		'default'     => '#8224e3',
	)
);

// Field: extra_heading.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'extra_heading',
		'type'        => 'heading',
		'title'       => esc_html__( 'Extra Section', 'optioner' ),
		'description' => esc_html__( 'This is description of extra section.', 'optioner' ),
	)
);

// Field: sample_url.
$obj->add_field(
	'first_tab',
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
	'first_tab',
	array(
		'id'          => 'sample_number',
		'type'        => 'number',
		'title'       => esc_html__( 'Sample Number', 'optioner' ),
		'description' => esc_html__( 'Description of sample number.', 'optioner' ),
	)
);

// Field: sample_email.
$obj->add_field(
	'first_tab',
	array(
		'id'          => 'sample_email',
		'type'        => 'number',
		'title'       => esc_html__( 'Sample Email', 'optioner' ),
		'description' => esc_html__( 'Description of sample email.', 'optioner' ),
	)
);

$obj->add_tab(
	array(
		'id'    => 'second_tab',
		'title' => esc_html__( 'Second Tab', 'optioner' ),
	)
);

// Field: sample_textarea.
$obj->add_field(
	'second_tab',
	array(
		'id'          => 'sample_textarea',
		'type'        => 'textarea',
		'title'       => esc_html__( 'Sample Textarea', 'optioner' ),
		'description' => esc_html__( 'Description of sample textarea.', 'optioner' ),
		'placeholder' => esc_html__( 'Enter content.', 'optioner' ),

	)
);

$obj->run();
